<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Logout extends MY_Controller 
{
	function __construct()
	{
		parent::__construct();
	}
	//Leads directly to the loginscreen and destroying all session-vars.
	function index()
	{
		$data['title'] = "Uitloggen";
		$this->session->unset_userdata('logged_in');
		session_destroy();
		redirect('login', 'refresh');
	}
}

?>