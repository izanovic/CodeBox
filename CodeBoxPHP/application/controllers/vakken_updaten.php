<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//session_start(); //we need to call PHP's session object to access it through CI
class Vakken_updaten extends MY_Controller 
{
	//constructs the function
	function __construct()
	{
		parent::__construct();
		$this->admin_auth();
		$this->load->helper(array('form', 'url'));
		$this->load->model('user','',TRUE);
		$this->load->model('globalfunc','',TRUE);
		$this->load->model('xmlparser_model','',TRUE);
	}
	//called when the controller inleveren is being called from index [without any parameters]
	//This gives a list of subjects for the study the person is in.
	function index()
	{
		$data['title'] = "Inleveren";
		$session_data = $this->session->userdata('logged_in');
		$data['username'] = $session_data['username'];
		$rolename = $session_data['role'];
		$data['error'] = ' ';
		$data['rolename'] = $rolename;
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		$this->load->view('xml_upload_view',$data);	
		$this->load->view('templates/footer', $data);
	}

	function do_upload()
	{
		$data['title'] = "XML inladen";	
		$config['upload_path'] = 'uploads/';
		$config['allowed_types'] = 'xml';
		$config['max_size']	= '0';
		$config['file_name'] = 'vakken';
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			$data['error'] = $this->upload->display_errors();
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			//$data['version'] = $version;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('xml_upload_view', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			$uploadarr = $this->upload->data();
			$data['upload_data'] = $this->upload->data();
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('upload_xml_success', $data);
			$this->load->view('templates/footer', $data);
			$this->xmlparser_model->insert();
			$user = $data['username'];
			$file = $uploadarr['full_path'];
		}
	}
}

?>