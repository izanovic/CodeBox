<?php

//class for xml parser just a test for now 
class Xml_parser extends AD_Controller{
	function __construct()
	{
		parent::__construct();
		$this->load->helper('file');
		$this->load->library('dmenu');
	}

//this function loads the xml file and parses it into the $data which will be sent to the views
	function index()
	{
		$data = get_filenames("uploads");
		$file['file'] = end($data);

		$dmenu = new Dmenu;
		
		$data['menu'] = $dmenu->show_menu();
		$data['title'] = 'XML';

		$this->load->view('templates/backend/header', $data);
		$this->load->view('admin/upload/upload_success', $file);
		$this->load->view('templates/backend/footer');		
		
		$this->load->model('xmlparser_model');
		$this->xmlparser_model->insert();
	}
//shows all the data from the xml table inside our database. 
	/*function getxml()
	{
		$this->load->library('menu');
		$menu = new Menu;

		$data['menu'] = $menu->show_menu();
		$this->load->model('xmlparser_model');
		$data['xml'] = $this->xmlparser_model->getxml();

		$this->load->view('templates/frontend/header',$data);
		$this->load->view('admin/xml/xml',$data);
		$this->load->view('templates/frontend/footer');
	}
	*/
}	