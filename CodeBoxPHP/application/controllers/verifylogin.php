<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//define('_useLDAP_',false);

class VerifyLogin extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('user','',TRUE);
		$this->load->model('role','',TRUE);
	}
	//Handles the login form.
	function index()
	{
		$this->load->library('form_validation');
	    $this->form_validation->set_rules('username', 'username', 'trim|required|xss_clean');
	    $this->form_validation->set_rules('password', 'password', 'trim|required|xss_clean|callback_check_database');
		if($this->form_validation->run() == FALSE)
		{
			$this->load->view('login_view',array('available' => _useLDAP_)); //$this->user->ldapavailable()
		}
		else
		{
			redirect('home', 'refresh');
		}
	}
	//Checks if the user is valid or not and entered the correct password.
	//Also handles adminaccounts which are being redirected to the local database for auth.
	function check_database($password)
	{
		if(_useLDAP_)
		{
			$username = $this->input->post('username');
			$result = $this->user->login($username, $password);
			if($result)
			{
				$admtest = explode('_',$username);
				if($admtest[0] == 'admin')
				{
					$sess_array = array
					(
						'username' => strtolower($username),
						'activated' => $this->user->isactivated($username),
						'role' => 'administrator'
					);
					$this->session->set_userdata('logged_in', $sess_array);
					return true;
				}
				else
				{
					$sess_array = array();
					$sess_array = array(
						//'id' => $row->id,
						'username' => strtolower($username),
						'activated' => $this->user->isactivated($username),
						'role' => $this->user->getrolefromdb($username)
						);
					$this->session->set_userdata('logged_in', $sess_array);
					$this->user->adduserifnotexists($username,$password);
					return true;
				}
			}	
			else
			{
				$this->form_validation->set_message('check_database', 'Wachtwoord en/of gebruikersnaam is onjuist!');
				return false;
			}
		}
		else
		{
			$username = $this->input->post('username');
			$result = $this->user->loginwithoutldap($username, $password);
			if($result)
			{
				$admtest = explode('_',$username);
				if($admtest[0] == 'admin')
				{
					$sess_array = array
					(
						'username' => strtolower($username),
						'activated' => $this->user->isactivated($username),
						'role' => 'administrator'
					);
					$this->session->set_userdata('logged_in', $sess_array);
					//echo("<script>alert('ZOOI: " . $sess_array['activated'] . "=" . $this->user->isactivated($username) . "');</script>");
					return true;
				}
				else
				{
					$sess_array = array();
					$sess_array = array(
						//'id' => $row->id,
						'username' => strtolower($username),
						'activated' => $this->user->isactivated($username),
						'role' => $this->user->getrolefromdb($username)
						);
					$this->session->set_userdata('logged_in', $sess_array);
					return true;
				}
			}	
			else
			{
				$this->form_validation->set_message('check_database', 'Wachtwoord en/of gebruikersnaam is onjuist!');
				return false;
			}
		}
	}
}
?>