<?php
class Upload extends MY_Controller 
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
	}

	function index()
	{
		$this->load->view('upload_view', array('error' => ' ' ));
	}
	//Uploads the file to the specified position.
	function do_upload()
	{
		$config['upload_path'] = '../../files/';
		$config['allowed_types'] = 'rar|sql|zip';
		$config['max_size']	= '100';
		$config['max_width']  = '1024';
		$config['max_height']  = '768';

		$this->load->library('upload', $config);
		$this->load->view('templates/header', $data);
		$this->load->view('templates/menu', $data);
		if ( ! $this->upload->do_upload())
		{
			$error = array('error' => $this->upload->display_errors());

			$this->load->view('upload_view', $error);
		}
		else
		{
			$data = array('upload_data' => $this->upload->data());

			$this->load->view('upload_success', $data);
		}
		$this->load->view('templates/footer', $data);
	}
}
?>