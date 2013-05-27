<?php
Class User extends CI_Model
{
	function login($username, $password)
	{
/*
		$this -> db -> select('id, username, password, roleid');
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

		$this->config->load('ldap',true);
		$ldap_ip = "141.252.8.105";//$this->config->item('ldap_ip');
		$ldap_port = 380;//$this->config->item('ldap_port');
		$ldapconnection = ldap_connect($ldap_ip,$ldap_port);
        if(!$ldapconnection)
        {
			return false;
        }
		else
        {
            ldap_set_option($ldapconnection, LDAP_OPT_REFERRALS, 0);
            ldap_set_option($ldapconnection, LDAP_OPT_PROTOCOL_VERSION, 3);
            $result = ldap_bind($ldapconnection);
            if($result)
            {
				return true;
            }
            else
            {
				return false;
			}
      	}
      	ldap_close($ldapconnection);
      	*/
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
	function getfullnamefromldap($username)
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
		$attributes = array("cn");
		$search = ldap_search($connection,'o=Noordelijke Hogeschool Leeuwarden,c=nl',"uid=" . $username, $attributes);
		$result = ldap_get_entries($connection,$search);
/*
		array(2) 
		{ 
			["count"]=> int(1) [0]=> 
				array(4) 
				{ 
					["cn"]=> array(3) 
					{ 
						["count"]=> int(2) [0]=> string(20) "leps1200 - lepstra s" [1]=> string(9) "s lepstra" 
					} 
					[0]=> string(2) "cn" ["count"]=> int(1) ["dn"]=> string(118) "cn=leps1200 - lepstra s,ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,o=Noordelijke Hogeschool Leeuwarden,c=nl" 
				} 
	} 
*/
		$str = $result[0]["cn"][0];
		$strname = explode("-",$str);
		$name = $strname[1];
		return $name;
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
		$result = ldap_get_entries($connection,$search);/*
		array(2) 
		{ 
			["count"]=> int(1) 
			[0]=> array(2) 
				{ 
					["count"]=> int(0) 
					["dn"]=> string(118) "cn=leps1200 - lepstra s,ou=voltijd,ou=Informatica BA,ou=Techniek,ou=studenten,o=Noordelijke Hogeschool Leeuwarden,c=nl" 
				} 
		} */

		$str = $result[0]["dn"];
		$strrole = explode(',',$str)[4];
		$role = explode('=',$strrole)[1];
		$role = substr($role,0,strlen($role) - 2);
		return $role;
	}
	function isalreadysend($username,$subjectid)
	{
		$this->load->model('globalfunc','',TRUE);
		$short_name = $this->globalfunc->getshortsubjectnamefromid($subjectid);
		$filename = $short_name . "_" . $username . "_";
		$result = glob("files/$filename*.*");
		if(count($result) > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
		/*
		$query = $this -> db -> query("SELECT * FROM files,users WHERE files.ownerid = users.id AND users.username = '$username' AND files.subjectid = '$subjectid'");
		$result = $query->num_rows();
		if($result == 0)
		{
			return false;
		}
		else
		{
			return true;
		}
		*/
	}
	function getstudyid($username)
	{
		$studyid = -1;
		$this -> db -> select('studyid');
		$this -> db -> from('users');
		$this -> db -> where('username', $username);
		$query = $this -> db -> get();
		$this -> db -> limit(1);
		foreach ($query->result() as $row)
		{
			$studyid = $row->studyid;
		}
		$query->free_result();
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
		/*
		$this -> db -> select('subjectID');
		$this -> db -> from('enroles');
		$this -> db -> where('userID', $userid);
		$subjectquery = $this->db->get();*/
		$subjectquery = $this->db->query("SELECT subjectID,shortname,name FROM subject WHERE studyid = '$studyid' ORDER BY name ASC");
		return $subjectquery->result();
	}
}
?>