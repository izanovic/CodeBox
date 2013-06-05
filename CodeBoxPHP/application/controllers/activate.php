<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Activate extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->activated_check();
	}
	function index()
	{
		$this->load->helper(array('form'));
		$this->load->view('activate_view');
	}
	//Checks whetever the registered user is activated or not.
	function activated_check()
	{
		$session_data = $this->session->userdata('logged_in');
		if($session_data['activated'] == "ja")
		{
			redirect('home','refresh');
		}
	}
}