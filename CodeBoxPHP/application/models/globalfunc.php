<?php
Class Globalfunc extends CI_Model
{
	//Gets all studies from the database [local]
	function studies()
	{
		$query = $this->db->query("SELECT id, name FROM study ORDER BY name ASC");
		return $query->result();
	}
	//Returns name of a study by its unique id.
	function getstudynamefromid($studyid)
	{
		$query = $this->db->query("SELECT name FROM study WHERE id = '$studyid'");
		foreach($query->result() as $row)
		{
			return $row->name;
		}
	}/*
	function getstudentnamefromid($studentid)
	{
		$query = $this->db->query("SELECT username FROM users WHERE id = '$studentid'");
		foreach($query->result() as $row)
		{
			return $row->username;
		}
	}*/
	//Gets subjectname from the database providing the unique id.
	function getsubjectnamefromid($subjectid)
	{
		$query = $this->db->query("SELECT name FROM subject WHERE subjectid = '$subjectid'");
		foreach($query->result() as $row)
		{
			return $row->name;
		}
	}
	//Checks if a subject is expired or not.
	function expiredsubject($subjectid)
	{
		if($this->todaydateindbformat() >= $this->getexpiredatafromdb($subjectid))
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Checks if a subject exists in our local database.
	function subjectexists($subjectid)
	{
		$query = $this->db->query("SELECT * FROM subject WHERE subjectid = '$subjectid'");
		$count = 0;
		foreach($query->result() as $row)
		{
			$count++;
		}
		if($count > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	//Returns the shortname of a subject from the database.
	function getshortsubjectnamefromid($subjectid)
	{
		$query = $this->db->query("SELECT shortname FROM subject WHERE subjectid = '$subjectid'");
		foreach($query->result() as $row)
		{
			return $row->shortname;
		}
	}
	//Today's date, rather useless, because time() does the same, whoopsie!
	function todaydateindbformat()
	{
		date_default_timezone_set('Europe/Amsterdam');
		$date = date('d-m-Y h:i:s a', time());
		$ts2 = date_create($date)->format('U');
		return $ts2;
	}
	//Returns the expiration date of a subject from the local database.
	function getexpiredatafromdb($subjectid)
	{
		$query = $this->db->query("SELECT expire FROM subject WHERE subjectid = '$subjectid' LIMIT 1");
		foreach($query->result() as $row)
		{
			return $row->expire;
		}
		return -1;
	}
	//Returns all subjects from a specific study.
	function studysubjects($studyid)
	{
		$query = $this->db->query("SELECT subjectid,name FROM subject WHERE studyid = '$studyid' ORDER BY name");
		return $query->result();
	}
	//Returns all students of a specific study.
	function students($studyid)
	{
		$query = $this->db->query("SELECT username,fullname FROM users WHERE studyid = '$studyid' ORDER BY username ASC");
		return $query->result();
	}
	//Returns all students having a specified subject.
	function getstudentsinsubject($studyid,$subjectid)
	{
		$query = $this->db->query("SELECT username,fullname FROM users,subject WHERE users.studyid = '$studyid' AND subject.subjectid = '$subjectid'  ORDER BY username");
		return $query->result();		
	}
	//Cleans up useless database entries. Such as non-existing files.
	function cleanupdbentries()
	{
		$query = $this->db->query("SELECT * FROM files");
		foreach($query->result() as $row)
		{
			$filename = $row->name;
			$count = 0;
			$result = glob ("files/$filename*.*");
			foreach($result as $row2)
			{
				$count++;
			}
			if($count == 0)
			{
				$query = $this->db->query("DELETE FROM files WHERE name = '$row->name'");
			}
		}
	}
}
?>