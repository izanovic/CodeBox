<?php

// constructor, loads the database, the parser library and the file helper. 
class Xmlparser_model extends CI_Model {
	public function __construct()
	{
		$this->load->database();
		$this->load->library('parser');
		$this->load->helper('file');
		$this->load->library('session');
		$this->load->model('globalfunc','',true);
	}
//Gets the info from the latest uploaded file, and stores it into the xml database. 
	/*public function insert()
	{	
		$data = get_filenames("uploads");
		$file = end($data);
		$xml = simplexml_load_file("uploads/".$file);
		foreach($xml as $course)
		{
			
		$data = array(
		'name' => (string)$course->name,
		'description' => (string)$course->description,
		'datee' => (string)$course->datee,
		'year' => (int)$course->year
		);
		
		$this->db->insert('xml', $data);	
		}
		
	}
	*/

	public function insert()
	{
		$data = get_filenames("uploads");
		$file = end($data);
		$xml = simplexml_load_file("uploads/".$file);
		foreach($xml as $course)
		{
			$this->db->select('Shortname');
			$this->db->from('subject');
			$this->db->where('Shortname', (string)$course->shortname);
			$d = $this->db->get();

			if($d->num_rows() == 0)
			{
				$date = date_create($course->expire);
				$studyid = $this->globalfunc->getstudyidfromname((string)$course->study);
				$courses = array (
					'Name' => (string)$course->name,
					'Shortname' => (string) $course->shortname,
					'studyid' => $studyid,
					'expire' => date_timestamp_get($date)
					);
				$this->db->insert('subject', $courses);
			}
		}
	}
}