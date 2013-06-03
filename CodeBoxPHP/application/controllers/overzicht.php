<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Overzicht extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('globalfunc','',TRUE);
		$this->load->model('user','',TRUE);
		$this->load->helper(array('form'));
	}
	function index()
	{
		$data['title'] = "Overzicht";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			if($rolename != 'student' && $rolename != 'gast')
			{
				$this->load->view('overzicht_docent_view', $data);
			}
			else
			{
				$date = new DateTime();
				$date->setTimestamp($this->globalfunc->todaydateindbformat());
				$data['datenow'] = $date->format('d/m/Y H:i:s');
				$this->load->view('overzicht_student_view', $data);
			}
			$this->load->view('templates/footer', $data);
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
	function mailusers($subjectid)
	{
		$data['title'] = "Email";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			if($session_data['role'] != "student" && $session_data['role'] != "gast")
			{
				$data['username'] = $session_data['username'];
				$rolename = $session_data['role'];
				$data['subjectid'] = $subjectid;
				$data['rolename'] = $rolename;
				$this->load->view('templates/header', $data);
				$this->load->view('templates/menu', $data);
				$this->load->view('email_view', $data);
				$this->load->view('templates/footer', $data);
			}
			else
			{
				redirect('home', 'refresh');
			}
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
	function choice($studyid)
	{
		if(is_numeric($studyid))
		{
			$data['title'] = "Overzicht - " . $this->globalfunc->getstudynamefromid($studyid);
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				if($session_data['role'] != "student" && $session_data['role'] != "gast")
				{
					$data['username'] = $session_data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['studyid'] = $studyid;
					$this->load->view('templates/header', $data);
					$this->load->view('templates/menu', $data);
					//$this->load->view('overzicht_students_view', $data);
					$this->load->view('overzicht_docentch_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					redirect('home', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect('overzicht', 'refresh');
		}
	}
	function studentlist($studyid)
	{
		if(is_numeric($studyid))
		{
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				if($session_data['role'] != "student" && $session_data['role'] != "gast")
				{
					$data['title'] = "Overzicht - " . $this->globalfunc->getstudynamefromid($studyid);
					$data['username'] = $session_data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['studyid'] = $studyid;
					$this->load->view('templates/header', $data);
					$this->load->view('templates/menu', $data);
					$this->load->view('overzicht_students_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					redirect('home', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect('overzicht', 'refresh');
		}
	}
	function student($studyid,$studentname)
	{
		if(is_numeric($studyid) && $this->user->userexists($studentname))
		{
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				if($session_data['role'] != "student" && $session_data['role'] != "gast")
				{
					$data['student_full_name'] = $this->user->getfullnamefromdb($studentname);
					$data['title'] = "Overzicht - " . $this->globalfunc->getstudynamefromid($studyid) . " / " . $data['student_full_name'];
					$data['username'] = $session_data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['studyid'] = $studyid;
					$data['studentname'] = $studentname;
					$this->load->view('templates/header', $data);
					$this->load->view('templates/menu', $data);
					$this->load->view('overzicht_subjects_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					redirect('home', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect('overzicht', 'refresh');
		}
	}
	function subjectlist($studyid)
	{
		if(is_numeric($studyid))
		{
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				if($session_data['role'] != "student" && $session_data['role'] != "gast")
				{
					$data['title'] = "Overzicht - " . $this->globalfunc->getstudynamefromid($studyid);
					$session_data = $this->session->userdata('logged_in');
					$data['username'] = $session_data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['studyid'] = $studyid;
					$this->load->view('templates/header', $data);
					$this->load->view('templates/menu', $data);
					$this->load->view('overzicht_subjectlist_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					redirect('home', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect('overzicht', 'refresh');
		}
	}
	function userlistbysubject($studyid,$subjectid)
	{	
		if($this->session->userdata('logged_in'))
		{
			if(is_numeric($studyid) && is_numeric($subjectid))
			{
				$session_data = $this->session->userdata('logged_in');
				if($session_data['role'] != "student" && $session_data['role'] != "gast")
				{
					$data['subject_name'] = $this->globalfunc->getsubjectnamefromid($subjectid);
					$data['title'] = "Overzicht - " . $this->globalfunc->getstudynamefromid($studyid) . " / " .  $data['subject_name'];
					$session_data = $this->session->userdata('logged_in');
					$data['username'] = $session_data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['studyid'] = $studyid;
					$data['subjectid'] = $subjectid;
					$data['short_subject_name'] = $this->globalfunc->getshortsubjectnamefromid($subjectid);
					$this->load->view('templates/header', $data);
					$this->load->view('templates/menu', $data);
					$this->load->view('overzicht_userspersubject_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					redirect('home', 'refresh');
				}
			}
			else
			{
				redirect('overzicht', 'refresh');
			}
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
	function subject($studyid,$studentname,$subjectid)
	{
		if(is_numeric($studyid) && !is_numeric($studentname) && is_numeric($subjectid))
		{
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				if($session_data['role'] != "student" && $session_data['role'] != "gast")
				{
					$data['student_full_name'] = $this->user->getfullnamefromdb($studentname);
					$data['subject_name'] = $this->globalfunc->getsubjectnamefromid($subjectid);
					$data['title'] = "Overzicht - " . $this->globalfunc->getstudynamefromid($studyid) . " / " . $data['student_full_name'] . " / " . $data['subject_name'];
					$session_data = $this->session->userdata('logged_in');
					$data['username'] = $session_data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['studyid'] = $studyid;
					$data['studentname'] = $studentname;
					$data['subjectid'] = $subjectid;
					$data['short_subject_name'] = $this->globalfunc->getshortsubjectnamefromid($subjectid);
					$this->load->view('templates/header', $data);
					$this->load->view('templates/menu', $data);
					$this->load->view('overzicht_download_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					redirect('home', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect('overzicht', 'refresh');
		}
	}
}

?>