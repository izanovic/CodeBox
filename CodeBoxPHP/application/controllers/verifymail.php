<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyMail extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('user','',TRUE);
		//$this->load->model('role','',TRUE);
	}

	function index()
	{
		$this->load->library('form_validation');
	    $this->form_validation->set_rules('onderwerp', 'onderwerp', 'trim|required|xss_clean');
	    $this->form_validation->set_rules('mail', 'mailcontent', 'trim|required|xss_clean');
		if($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Email";
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('email_view', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			//Email
			$onderwerp = $this->input->post('onderwerp');
			$bericht= $this->input->post('mail');
			$this->load->library('email');
			$this->email->set_newline("\r\n");

			if($session_data['role'] == "administrator")
			{
				$this->email->from('Codeboxmasters@gmail.com', 'CodeBox Administratie');
			}
			else
			{
				$this->email->from('Codeboxmasters@gmail.com', $this->user->getemailfromldap($data['username']));
			}
			
			$this->email->to(_undefined_);		
			//moet nog met een leuk forloopje worden behandeld! Alleen moet voorkomen dat niet heel NHL een leuk mailtje krijgt.
			$this->email->subject($onderwerp);		
			$this->email->message($bericht);

			if($this->email->send())
			{
				echo("<script>alert('De email is verstuurd!')</script>");
				redirect('overzicht', 'refresh');
			}
			
			else
			{
				show_error($this->email->print_debugger());
			}
		}
	}
}
?>