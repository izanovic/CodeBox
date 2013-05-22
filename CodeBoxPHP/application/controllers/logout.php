<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Logout extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	function index()
	{
		$data['title'] = "Uitloggen";
		if($this->session->userdata('logged_in'))
		{
			$this->session->unset_userdata('logged_in');
			session_destroy();
			redirect('login', 'refresh');
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
}

?>