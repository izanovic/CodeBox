<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class VerifyMail extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('user','',TRUE);
	}
	//Sends the mail to the specified users, this sends a mail to the specified NHL users.
	function index($subjectid)
	{
		//
	}
	function handle($subjectid)
	{
		$this->load->library('form_validation');
	    $this->form_validation->set_rules('onderwerp', 'onderwerp', 'trim|required|xss_clean');
	    $this->form_validation->set_rules('mail', 'mailcontent', 'trim|required|xss_clean');
	    $session_data = $this->session->userdata('logged_in');
		if($this->form_validation->run() == FALSE)
		{
			$data['title'] = "Email";
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
			$onderwerp = $this->input->post('onderwerp');
			$bericht= $this->input->post('mail');
			$this->load->library('email');
			$this->email->set_newline("\r\n");
			$username = $session_data['username'];
			if($session_data['role'] == "administrator")
			{
				$this->email->from('Codeboxmasters@gmail.com', 'CodeBox Administratie');
			}
			else
			{
				$this->email->from('Codeboxmasters@gmail.com', $this->user->getemailfromldap($username));
			}
			$mails = "";
			$result = $this->user->returnusersfromsubject($subjectid);
			foreach($result as $row)
			{
				if(!$this->user->isalreadysend($row->username,$subjectid))
				{
					$mails = $mails . "," . $this->user->getemail($username);
				}
			}
			$mails = substr($mails, 1);
			$this->email->to($mails);
			//echo("<script>alert('" . $mails . "');</script>");

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