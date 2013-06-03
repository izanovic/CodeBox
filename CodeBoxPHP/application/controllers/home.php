<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Home extends CI_Controller 
{
	//constructor
	function __construct()
	{
		parent::__construct();
		$this->load->model('role','',TRUE);
		$this->load->model('user','',TRUE);
	}
	//Home function, called the home view for a short overview.
	function index()
	{
		$data['title'] = "Home";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('home_view', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
}

?>