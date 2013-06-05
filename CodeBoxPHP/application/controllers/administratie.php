<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Administratie extends MY_Controller 
{
	//Constructor of the controller
	function __construct()
	{
		parent::__construct();
		$this->admin_auth();
		$this->load->model('user','',TRUE);
		$this->load->model('globalfunc','',TRUE);
	}
	//Loads the primary view for the administration panel.
	function index()
	{
		$data['title'] = "Administratie";
		$session_data = $this->session->userdata('logged_in');
		$data['username'] = $session_data['username'];
		$rolename = $session_data['role'];
		$data['rolename'] = $rolename;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('administratie_view', $data);
		$this->load->view('templates/footer', $data);
	}
	//Called to load the users from LDAP into our database. This function is very resource intensive, so use with care.
	function addusers()
	{
		$data['title'] = "Administratie";
		$session_data = $this->session->userdata('logged_in');
		//echo("<script>alert('Een ogenblik geduld, dit proces kan meer dan 10 minuten in beslag nemen. Onderbreek dit proces niet!');</script>");
		$data['username'] = $session_data['username'];
		$rolename = $session_data['role'];
		$data['rolename'] = $rolename;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		//get all students
		$result = $this->user->allstudents();
		foreach($result as $row)
		{
			if($row["uid"][0] != '')
			{
				$this->user->adduserifnotexists($row["uid"][0],'geen wachtwoord');
			}
		}
		//get all teachers
		$result2 = $this->user->allteachers();
		foreach($result2 as $row)
		{
			if($row["uid"][0] != '')
			{
				$this->user->adduserifnotexists($row["uid"][0],'geen wachtwoord');
			}
		}
		$this->user->removeinactiveusers();
		echo("<script>alert('Gebruikers succesvol geupdate.');</script>");
		redirect('administratie', 'refresh');
		$this->load->view('templates/footer', $data);
	}
	//Cleans up entries from the database which are no longer relevant [such as deleted files].
	function cleanupdatabase()
	{
		$data['title'] = "Administratie";
		$session_data = $this->session->userdata('logged_in');
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
	//Parser using XML to load all subjects into the database.
	function addsubjects()
	{
		$data['title'] = "Administratie";
		$data['username'] = $session_data['username'];
		$rolename = $session_data['role'];
		$data['rolename'] = $rolename;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		echo("<script>alert('Deze functie moet nog geimplementeerd worden!');</script>");
		redirect('administratie', 'refresh');
		$this->load->view('templates/footer', $data);
	}
	//This function creates random passwords for every single account. A list will be displayed, sorted by Study. In case LDAP is not being implemented.
	function generaterandompasswords()
	{
		$data['title'] = "Administratie";
		$session_data = $this->session->userdata('logged_in');
		$data['username'] = $session_data['username'];
		$rolename = $session_data['role'];
		$data['rolename'] = $rolename;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('adm_usergeneration', $data);
		$this->load->view('templates/footer', $data);
	}
}

?>