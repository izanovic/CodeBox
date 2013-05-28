<?php
Class User extends CI_Model
{
	function login($username, $password)
	{
		$admincheck = explode('_',$username);
		if($admincheck[0] == "admin")
		{
			$this -> db -> select('username, password');
			$this -> db -> from('users');
			$this -> db -> where('username', $username);
			$this -> db -> where('password', SHA1($password));
			$this -> db -> limit(1);
			$query = $this -> db -> get();

		    if($query -> num_rows() == 1)
		    {
				return $query->result();
		    }
		    else
		    {
				return false;
		    }
		}
		else
		{
			$connection = @ldap_connect('ldapmaster.nhl.nl',380) or die(ldap_error());
			if($connection)
			{
				ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
				ldap_bind($connection);
			}
			else
			{
				return false;
				die('Could not connect to LDAP server');
			}

			$search = ldap_search($connection,'o=Noordelijke Hogeschool Leeuwarden,c=nl',"uid=" . $username);
			$result = ldap_get_entries($connection,$search);
			$ldapUserString = $result[0]['dn'];
			$ldapResult = ldap_bind($connection,$ldapUserString,$password);
			$ldapAuthInfo = ($ldapResult? $result : false);
			if(count($ldapAuthInfo) < 2)
			{
				return false;
			}
			else
			{
				return $ldapAuthInfo;
			}
		}
	}
	function userexitsindatabase($username)
	{
		$query = $this->db->query("SELECT * FROM users WHERE username = '$username'");
		$result = $query->result();
		if(count($result) == 1)
		{
			return true;
		}
		else
		{
			return false;
		}

	}
	function getstudyfromldap($username)
	{
		$connection = @ldap_connect('ldapmaster.nhl.nl',380) or die(ldap_error());
		if($connection)
		{
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);
		}
		else
		{
			die('Could not connect to LDAP server');
		}
		$dn = "o=Noordelijke Hogeschool Leeuwarden,c=nl"; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
		$filter = "uid=" . $username;
		$search = ldap_search($connection, $dn, $filter) or die ("Search failed");
		$entries = ldap_get_entries($connection, $search);
		return $entries[0]["ou"][0];
	}
	function getemailfromldap($username)
	{
		$connection = @ldap_connect('ldapmaster.nhl.nl',380) or die(ldap_error());
		if($connection)
		{
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);
		}
		else
		{
			die('Could not connect to LDAP server');
		}
		$dn = "o=Noordelijke Hogeschool Leeuwarden,c=nl"; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
		$filter = "uid=" . $username;
		$search = ldap_search($connection, $dn, $filter) or die ("Search failed");
		$entries = ldap_get_entries($connection, $search);
		return $entries[0]["NHLhomeMail"][0];
	}
	function studyexists($studyname)
	{
		$query = $this->db->query("SELECT id FROM study WHERE name = '$studyname' LIMIT 1");
		$result = $query->result();
		if(count($result) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function userexists($username)
	{
		$query = $this->db->query("SELECT * FROM users WHERE username = '$username' LIMIT 1");
		$result = $query->result();
		if(count($result) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function adduserifnotexists($username)
	{
		if(!$this->userexists($username))
		{
			$studyname = $this->getstudyfromldap($username);
			$studyid = -1;
			if(!$this->studyexists($studyname))
			{
				$this->db->query("INSERT INTO study (name) VALUES ('$studyname')");
			}
			$query = $this->db->query("SELECT id FROM study WHERE name = '$studyname' LIMIT 1");
			$result = $query->result();
			foreach($result as $row)
			{
				$studyid = $row->id;
			}
			$query = $this->db->query("INSERT INTO users (username,password,studyid) VALUES ('$username','geen wachtwoord','$studyid')");
		}
	}
	function getfullnamefromldap($username)
	{
		$splittest = explode('_',$username);
		if($splittest[0] == "admin") return $splittest[1];
		$connection = @ldap_connect('ldapmaster.nhl.nl',380) or die(ldap_error());
		if($connection)
		{
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);
		}
		else
		{
			die('Could not connect to LDAP server');
		}
		$dn = "o=Noordelijke Hogeschool Leeuwarden,c=nl"; //ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,
		$filter = "uid=" . $username;
		$search = ldap_search($connection, $dn, $filter) or die ("Search failed");
		$entries = ldap_get_entries($connection, $search);
		return $entries[0]["cn"][0];
	}
	function getrolefromldap($username)
	{
		$rolename = "gast";
		$connection = @ldap_connect('ldapmaster.nhl.nl',380) or die(ldap_error());
		if($connection)
		{
			ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
			ldap_bind($connection);
		}
		else
		{
			die('Could not connect to LDAP server');
		}
		$attributes = array("dn");
		$search = ldap_search($connection,'o=Noordelijke Hogeschool Leeuwarden,c=nl',"uid=" . $username , $attributes);
		$result = ldap_get_entries($connection,$search);

		$str = $result[0]["dn"];
		$strrole = explode(',',$str)[4];
		$role = explode('=',$strrole)[1];
		$role = substr($role,0,strlen($role) - 2);
		return $role;
	}
	function isalreadysend($username,$subjectid)
	{
		$user = $username;
		$splittest = explode('_',$username);
		if($splittest[0] == "admin")
		{
			$user = $splittest[1];
		}

		$this->load->model('globalfunc','',TRUE);
		$short_name = $this->globalfunc->getshortsubjectnamefromid($subjectid);
		$filename = $short_name . "_" . $user . "_";
		$result = glob("files/$filename*.*");
		if(count($result) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	function getstudyid($username)
	{
		$studyid = -1;
		$query = $this->db->query("SELECT id FROM study,users WHERE study.id = users.studyid AND users.username = '$username'");
		$result = $query->result();
		foreach($result as $row)
		{
			$studyid = $row->id;
		}
		return $studyid;
	}
	/*
	function getuserid($username)
	{
		$userid = -1;
		$this -> db -> select('id');
		$this -> db -> from('users');
		$this -> db -> where('username', $username);
		$query = $this -> db -> get();
		$this -> db -> limit(1);
		foreach ($query->result() as $row)
		{
			$userid = $row->id;
		}
		$query->free_result();
		return $userid;
	}*/
	function subjects($username)
	{
		$studyid = $this->getstudyid($username);
		$subjectquery = $this->db->query("SELECT subjectID,shortname,name FROM subject WHERE studyid = '$studyid' ORDER BY name ASC");
		return $subjectquery->result();
	}
}
?>