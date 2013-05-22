<?php
Class User extends CI_Model
{
	function login($username, $password)
	{
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
		/*
		$this->config->load('ldap',true);
		$ldap_ip = $this->config->item('ldap_ip');
		$ldap_port = $this->config->item('ldap_port');
		$ldapconnection = ldap_connect($ldap_ip,$ldap_port);
        if(!$ldapconnection)
        {
			return false;
			die();
        }
		else
        {
            ldap_set_option($ldapconnection, LDAP_OPT_REFERRALS, 0);
            $result = ldap_bind($ldapconnection, $username, $password);
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
	}
	function isalreadysend($username,$subjectid)
	{
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
	}
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