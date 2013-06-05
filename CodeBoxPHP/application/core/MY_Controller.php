<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class MY_Controller extends CI_Controller 
{
	function __construct()
    {
        parent::__construct();
        $this->check_login();
    }
    //Checks whetever the user is logged in or not.
    function check_login()
    {
		if(!$this->session->userdata('logged_in'))
		{
			redirect('login', 'refresh');
		}
		else
		{
			$session_data = $this->session->userdata('logged_in');
			if($session_data['activated'] == "nee")
			{
				redirect('activate','refresh');
			}
		}
	}
	//Checks if the specified user is an administratior.
	function admin_auth()
    {
    	$session_data = $this->session->userdata('logged_in');
		if($session_data['role'] != "administrator")
		{
			redirect('home', 'refresh');
		}
	}
}
?>