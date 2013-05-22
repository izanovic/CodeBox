<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Administratie extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	function index()
	{
		$data['title'] = "Administratie";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			if($session_data['role'] == 'administrator')
			{
				$data['username'] = $session_data['username'];
				$rolename = $session_data['role'];
				$data['rolename'] = $rolename;
				$this->load->view('templates/header', $data);
				$this->load->view('templates/menu', $data);
				$this->load->view('administratie_view', $data);
				$this->load->view('templates/footer', $data);
			}
			else
			{
				redirect('home', 'refresh');
			}
		}
		else
		{
			redirect('home', 'refresh');
		}
	}
}

?>