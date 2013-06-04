<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Profiel extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	//Loads the profilepage for the user.
	function index()
	{
		$data['title'] = "Profiel";
		$session_data = $this->session->userdata('logged_in');
		$data['username'] = $session_data['username'];
		$rolename = $session_data['role'];
		$data['rolename'] = $rolename;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('profiel_view', $data);
		$this->load->view('templates/footer', $data);
	}
}

?>