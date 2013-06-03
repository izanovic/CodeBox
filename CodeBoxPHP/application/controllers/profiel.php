<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Profiel extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	//Loads the profilepage for the user.
	function index()
	{
		$data['title'] = "Profiel";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('profiel_view', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
}

?>