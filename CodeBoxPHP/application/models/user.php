<?php
define('_includepath_',';c:/xampp/htdocs/includes');
Class User extends CI_Model
{
	function login($username, $password)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
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
				return true;
		    }
		    else
		    {
				return false;
		    }
		}
		else
		{
			/*
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
			$ldapResult = @ldap_bind($connection,$ldapUserString,$password);
			$ldapAuthInfo = ($ldapResult? $result : false);
			if(!ldapResult)
			{
				return false;
			}
			else
			{
				return $ldapAuthInfo;
			}
			if(count($ldapAuthInfo) < 2)
			{
				return false;
			}
			else
			{
				return $ldapAuthInfo;
			}
			*/
			return LDAP::authenticate($username,$password);
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
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		$studyname = LDAP::getstudy($username);
		if($studyname == '')
		{
			return "[geen naam]";
		}
		return $studyname;
	}
	function getemailfromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::getmail($username);
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
			$this->load->model('globalfunc','',TRUE);
			$datenow = $this->globalfunc->todaydateindbformat();
			$query = $this->db->query("INSERT INTO users (username,password,studyid,lastactive) VALUES ('$username','geen wachtwoord','$studyid', '$datenow')");
		}
	}
	function removeinactiveusers()
	{
		$query = $this->db->query("SELECT Username FROM users");
		$result = $query->result();
		foreach($result as $row)
		{	
			$admntest = explode('_',$row->Username);
			if($admntest[0] != "admin")
			{	
				$activedate_stamp = -1;
				$query = $this->db->query("SELECT lastactive FROM users WHERE username = '$row->Username'");
				$result2 = $query->result();
				foreach($result2 as $row2)
				{
					$activedate_stamp = $row2->lastactive;
				}
				$four_years = strtotime('-4 years'); // 4 jaren inactiviteit
				if($activedate_stamp < $four_years && $activedate_stamp != -1)
				{
					$query = $this->db->query("DELETE FROM users WHERE username = '$row->Username'");
				}
			}
		}
	}
	function getfullnamefromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		$splittest = explode('_',$username);
		if($splittest[0] == "admin") return $splittest[1];
		return LDAP::getfullusername($username);
	}
	function getrolefromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::getldaprole($username);
	}
	function isalreadysend($username,$subjectid)
	{
		$user = $username;
		$splittest = explode('_',$user);
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
	function ldapavailable()
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::isavailable();
	}
	function subjects($username)
	{
		$studyid = $this->getstudyid($username);
		$subjectquery = $this->db->query("SELECT subjectID,shortname,name FROM subject WHERE studyid = '$studyid' ORDER BY name ASC");
		return $subjectquery->result();
	}
	function allusers()
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::ldapallusers();
	}
}
?>