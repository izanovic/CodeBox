<?php
define('_includepath_','../includes');
ini_set('MAX_EXECUTION_TIME', 3600);
Class User extends CI_Model
{
	//Does the same as login, but now with the local database
	function loginwithoutldap($username,$password)
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
	//Activates a local account
	function activateaccount($username,$password)
	{
		$pass = SHA1($password);
		$this->db->query("UPDATE users SET password = '$pass',activated=1 WHERE username = '$username'");
		return true;
	}
	//Returns the role from the local database
	function getrolefromdb($username)
	{
		$query = $this->db->query("SELECT roles.RoleName FROM users,roles WHERE users.username = '$username' AND users.roleid = roles.roleid");
		foreach($query->result() as $row)
		{
			return $row->RoleName;
		}
	}
	//Checks if the user has logged in and changed their password already. Returns a number!
	function isactivated($username)
	{
		$query = $this->db->query("SELECT activated FROM users WHERE username = '$username' LIMIT 1");
		foreach($query->result() as $row)
		{
			if($row->activated == 1)
			{
				return "ja";
			}
			else
			{
				return "nee";
			}
		}
	}
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
	function adduserifnotexists($username,$password)
	{
		if(!$this->userexists($username))
		{
			$studyname = ucfirst(strtolower($this->getstudyfromldap($username)));
			$fullname = $this->getfullnamefromldap($username);
			$studyid = -1;
			$rolename = $this->getrolefromldap($username);
			if(!$this->studyexists($studyname) && $rolename == "student")
			{
				$this->db->query("INSERT INTO study (name) VALUES ('$studyname')");
			}
			$query = $this->db->query("SELECT id FROM study WHERE name = '$studyname' LIMIT 1");
			$result = $query->result();
			foreach($result as $row)
			{
				$studyid = $row->id;
			}
			$roleid = -1;
			$query2 = $this->db->query("SELECT RoleID FROM roles WHERE RoleName = '$rolename' LIMIT 1");
			$result2 = $query2->result();
			foreach($result2 as $row2)
			{
				$roleid = $row->RoleID;
			}
			$this->load->model('globalfunc','',TRUE);
			$datenow = $this->globalfunc->todaydateindbformat();
			$mail = $this->user->getemail($username);
			$pass = SHA1($password);
			$query = $this->db->query("INSERT INTO users (username,fullname,password,studyid,lastactive,email,activated,roleid) VALUES ('$username','$fullname','$pass','$studyid', '$datenow','$mail',0,'$roleid')");
		}
	}
	//Sets a password for an user
	function setuserpassword($username,$password)
	{
		$pass = SHA1($password);
		$this->db->query("UPDATE users SET password='$pass' WHERE username = '$username'");
		return true;
	}
	//Generates a random password.
	function randompassword($length) 
	{
	    $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
	    $pass = array(); //remember to declare $pass as an array
	    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
	    for ($i = 0; $i < $length; $i++) {
	        $n = rand(0, $alphaLength);
	        $pass[] = $alphabet[$n];
	    }
	    return implode($pass); //turn the array into a string
	}
	//Returns a nice and shiny array full of the users in our database, how nice!
	function getallusersfromdb()
	{
		$query = $this->db->query("SELECT * FROM users ORDER BY studyid,username");
		return $query->result();
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
			return ucfirst($row->Fullname);
			//$result = explode(' - ', $result);
		}
	}
	//Returns the user's fullname from LDAP.
	function getfullnamefromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		$splittest = explode('_',$username);
		if($splittest[0] == "admin") return $splittest[1];
		$result = LDAP::getfullusername($username);
		if($result == "") return $username;
		return $result;
	}
	//Returns the role for the user from LDAP.
	function getrolefromldap($username)
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::getldaprole($username);
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
	function allstudents()
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::ldapallstudents();
	}
	//Returns all teachers from LDAP.
	function allteachers()
	{
		ini_set("include_path", _includepath_);
		require_once("ldap.php");
		return LDAP::ldapallteachers();
	}
}
?>