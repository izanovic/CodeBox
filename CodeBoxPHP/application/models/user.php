<?php
define('_includepath_',';c:/xampp/htdocs/includes');
ini_set('MAX_EXECUTION_TIME', 3600);
Class User extends CI_Model
{
	//Checks if the credentials are correct, using LDAP or local database, according to the username.
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
		    	$date = time();
		    	$this->db->query("UPDATE users SET lastactive = '$date' WHERE username = '$username'");
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
	//Checks if the user exists in the local database.
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
	//Returns from a remote class from LDAP the studyname of the specified user.
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
	//Returns the email from a LDAP user.
	function getemailfromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::getmail($username);
	}
	//Returns all users who did not deliver their package yet to CodeBox.
	function returnusersfromsubject($subjectid)
	{
		$query = $this->db->query("SELECT studyid FROM subject WHERE subjectid = '$subjectid'");
		$result = $query->result();
		$studyid = -1;
		foreach($result as $row)
		{
			$studyid = $row->studyid;
		}
		$query2 = $this->db->query("SELECT username FROM users WHERE studyid = '$studyid'");
		return $query2->result();
	}
	//gets email from database
	function getemail($username)
	{
		$query = $this->db->query("SELECT email FROM users WHERE username = '$username' LIMIT 1");
		$result = $query->result();
		foreach($result as $row)
		{
			return $row->email;
		}
	}
	//Checks if a study exists in the database.
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
	//Checks if an user exists in the db. Seems a duplicate, whoops!
	function userexists($username)
	{
		return $this->userexitsindatabase($username);
	}
	//Adds user if it does not exists in our database.
	function adduserifnotexists($username)
	{
		if(!$this->userexists($username))
		{
			$studyname = ucfirst(strtolower($this->getstudyfromldap($username)));
			$fullname = $this->getfullnamefromldap($username);
			$studyid = -1;
			if(!$this->studyexists($studyname) && $this->getrolefromldap($username))
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
			$mail = $this->user->getemail($username);
			$query = $this->db->query("INSERT INTO users (username,fullname,password,studyid,lastactive,email) VALUES ('$username','$fullname','geen wachtwoord','$studyid', '$datenow','$mail')");
		}
	}
	//Removes inactive users from the database.
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
	//Returns full name from database.
	function getfullnamefromdb($username)
	{
		$query = $this->db->query("SELECT Fullname FROM users WHERE username = '$username' LIMIT 1");
		$result = $query->result();
		foreach($result as $row)
		{	
			$result = $row->Fullname;
			$result = explode(' - ', $result);
			return ucfirst($result[1]);
		}
	}
	//Returns the user's fullname from LDAP.
	function getfullnamefromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		$splittest = explode('_',$username);
		if($splittest[0] == "admin") return $splittest[1];
		return LDAP::getfullusername($username);
	}
	//Returns the role for the user from LDAP.
	function getrolefromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		$fullname = LDAP::getldaprole($username);
		$fullname = explode(' - ',$fullname);
		return $fullname[1];
	}
	//Checks if a file is send or not.
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
	//Returns the studyid from the database providing the user's name.
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
	//Checks if LDAP is available or not.
	function ldapavailable()
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::isavailable();
	}
	//Returns all subjects for an user.
	function subjects($username)
	{
		$studyid = $this->getstudyid($username);
		$subjectquery = $this->db->query("SELECT subjectID,shortname,name FROM subject WHERE studyid = '$studyid' ORDER BY name ASC");
		return $subjectquery->result();
	}
	//Returns all users from LDAP.
	function allusers()
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::ldapallusers();
	}
}
?>