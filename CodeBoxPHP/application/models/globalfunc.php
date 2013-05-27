<?php
Class Globalfunc extends CI_Model
{
	function studies()
	{
		$query = $this->db->query("SELECT id, name FROM study ORDER BY name ASC");
		return $query->result();
	}
	function getstudynamefromid($studyid)
	{
		$query = $this->db->query("SELECT name FROM study WHERE id = '$studyid'");
		foreach($query->result() as $row)
		{
			return $row->name;
		}
	}
	function getstudentnamefromid($studentid)
	{
		$query = $this->db->query("SELECT username FROM users WHERE id = '$studentid'");
		foreach($query->result() as $row)
		{
			return $row->username;
		}
	}
	function getsubjectnamefromid($subjectid)
	{
		$query = $this->db->query("SELECT name FROM subject WHERE subjectid = '$subjectid'");
		foreach($query->result() as $row)
		{
			return $row->name;
		}
	}
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
	function getshortsubjectnamefromid($subjectid)
	{
		$query = $this->db->query("SELECT shortname FROM subject WHERE subjectid = '$subjectid'");
		foreach($query->result() as $row)
		{
			return $row->shortname;
		}
	}
	function todaydateindbformat()
	{
		date_default_timezone_set('Europe/Amsterdam');
		$date = date('d-m-Y h:i:s a', time());
		$ts2 = date_create($date)->format('U');
		return $ts2;
	}
	function getexpiredatafromdb($subjectid)
	{
		$query = $this->db->query("SELECT expire FROM subject WHERE subjectid = '$subjectid' LIMIT 1");
		foreach($query->result() as $row)
		{
			return $row->expire;
		}
		return -1;
	}
	function getclassnamefromid($classid)
	{
		$query = $this->db->query("SELECT name FROM class WHERE id = '$classid'");
		foreach($query->result() as $row)
		{
			return $row->name;
		}
	}
	function studysubjects($studyid)
	{
		$query = $this->db->query("SELECT subjectid,name FROM subject WHERE studyid = '$studyid'");
		return $query->result();
	}
	function classes($studyid)
	{
		$query = $this->db->query("SELECT id,name FROM class WHERE studyid = '$studyid' ORDER BY name ASC");
		return $query->result();
	}
	function students($studyid,$classid)
	{
		$query = $this->db->query("SELECT id, username FROM users WHERE studyid = '$studyid' AND classid = '$classid' ORDER BY username ASC");
		return $query->result();
	}
}
?>