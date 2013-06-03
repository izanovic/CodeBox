<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Administratie extends CI_Controller 
{
	//Constructor of the controller
	function __construct()
	{
		parent::__construct();
		$this->load->model('user','',TRUE);
		$this->load->model('globalfunc','',TRUE);
	}
	//Loads the primary view for the administration panel.
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
	//Called to load the users from LDAP into our database. This function is very resource intensive, so use with care.
	function addusers()
	{
		$data['title'] = "Administratie";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			if($session_data['role'] == 'administrator')
			{
				//echo("<script>alert('Een ogenblik geduld, dit proces kan meer dan 10 minuten in beslag nemen. Onderbreek dit proces niet!');</script>");
				$data['username'] = $session_data['username'];
				$rolename = $session_data['role'];
				$data['rolename'] = $rolename;
				$this->load->view('templates/header', $data);
				$this->load->view('templates/menu', $data);
				$result = $this->user->allusers();
				foreach($result as $row)
				{
					if($row["uid"][0] != '')
					{
						$this->user->adduserifnotexists($row["uid"][0]);
					}
				}
				$this->user->removeinactiveusers();
				echo("<script>alert('Gebruikers succesvol geupdate.');</script>");
				redirect('administratie', 'refresh');
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
	//Cleans up entries from the database which are no longer relevant [such as deleted files].
	function cleanupdatabase()
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
				$this->globalfunc->cleanupdbentries();
				$this->user->removeinactiveusers();
				echo("<script>alert('Succesvol opgeschoond!');</script>");
				redirect('administratie', 'refresh');
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
	//Parser using XML to load all subjects into the database.
	function addsubjects()
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
				echo("<script>alert('Deze functie moet nog geimplementeerd worden!');</script>");
				redirect('administratie', 'refresh');
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