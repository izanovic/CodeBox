<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class MY_Controller extends CI_Controller 
{
	function __construct()
    {
        parent::__construct();
        $this->check_login();
    }
    function check_login()
    {
		if(!$this->session->userdata('logged_in'))
		{
			redirect('login', 'refresh');
		}
	}
	function admin_auth()
    {
    	$session_data = $this->session->userdata('logged_in');
		if(!$session_data['role'] == "administrator")
		{
			redirect('home', 'refresh');
		}
	}
}
?>