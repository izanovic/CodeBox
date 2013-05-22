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