<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
session_start(); //we need to call PHP's session object to access it through CI
class Inleveren extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->model('user','',TRUE);
		$this->load->model('globalfunc','',TRUE);
	}
	function index()
	{
		$data['title'] = "Inleveren";
		if($this->session->userdata('logged_in'))
		{
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$this->user->getfullnamefromldap($session_data['username']);
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$result = $this->user->subjects($data['username']);
			$this->load->view('enroledvakken_view', $data);
			$this->load->view('templates/footer', $data);
		}
		else
		{
			redirect('login', 'refresh');
		}
	}
	function vak($subjectid)
	{
		if(is_numeric($subjectid))
		{
			$data['title'] = "Inleveren";
			if($this->session->userdata('logged_in'))
			{
				if(!$this->globalfunc->expiredsubject($subjectid) && $this->globalfunc->subjectexists($subjectid))
				{
					$session_data = $this->session->userdata('logged_in');
					$data['username'] = $session_data['username'];
					$username = $data['username'];
					$rolename = $session_data['role'];
					$data['rolename'] = $rolename;
					$data['version'] = 1;
					$isdelivered = $this->user->isalreadysend($username,$subjectid);
					if(!$isdelivered)
					{
						$this->load->view('templates/header', $data);
						$data['subjectid'] = $subjectid;
						$data['error'] = ' ';
						$this->load->view('inleveren_view', $data);
						$this->load->view('templates/footer', $data);
					}
					else
					{
						echo("<script>alert('Al ingeleverd!');</script>");
						redirect('inleveren', 'refresh');
					}
				}
				else
				{
					redirect('inleveren', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect($subjectid, 'refresh');
		}
	}
	function edit($subjectid)
	{
		if(is_numeric($subjectid))
		{
			if($this->session->userdata('logged_in'))
			{
				$session_data = $this->session->userdata('logged_in');
				$data['username'] = $session_data['username'];
				$username = $data['username'];
				$data['title'] = "Aanpassen";
				$rolename = $session_data['role'];
				$data['rolename'] = $rolename;
				$data['version'] = 1;
				$isdelivered = $this->user->isalreadysend($username,$subjectid);
				if($isdelivered)
				{
					$this->load->view('templates/header', $data);
					$data['subjectid'] = $subjectid;
					$data['error'] = ' ';
					$this->load->view('inleveren_view', $data);
					$this->load->view('templates/footer', $data);
				}
				else
				{
					echo("<script>alert('Hier ging even wat mis, we gaan even terug naar de vorige pagina.');</script>");
					redirect('inleveren', 'refresh');
				}
			}
			else
			{
				redirect('login', 'refresh');
			}
		}
		else
		{
			redirect($subjectid, 'refresh');
		}
	}
	function do_upload($subject,$username,$version)
	{
		$config['upload_path'] = 'files/';
		$config['allowed_types'] = '*';
		$config['max_size']	= '0';
		$data['title'] = 'Inleveren';
		$short_subject_name = $this->globalfunc->getshortsubjectnamefromid($subject);
		$fileformat = $short_subject_name . "_" . $username . "_" . $version;
		$result = glob ("files/$fileformat*.*");
		while(count(glob("files/$fileformat.*")) != 0)
		{
			$version++;
			$fileformat = $short_subject_name . "_" . $username . "_" . $version;
		}
		$config['file_name'] = $short_subject_name . "_" . $username . "_" . $version;
		$this->load->library('upload', $config);
		if ( ! $this->upload->do_upload())
		{
			$data['error'] = $this->upload->display_errors();
			$session_data = $this->session->userdata('logged_in');
			$data['username'] = $session_data['username'];
			$rolename = $session_data['role'];
			$data['rolename'] = $rolename;
			$data['subjectid'] = $subject;
			$data['version'] = $version;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/menu', $data);
			$this->load->view('inleveren_view', $data);
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
			$this->load->view('inleveren_fin_view', $data);
			$this->load->view('templates/footer', $data);
			$userid = $this->user->getuserid($data['username']);
			$file = $uploadarr['full_path'];
			
			$checkquery = $this->db->query("SELECT * FROM files WHERE ownerid = '$userid' AND subjectid = '$subject'");
			$querycount = 0;
			$fileid = -1;
			foreach($checkquery->result() as $row)
			{
				$fileid = $row->id;
				$querycount++;
			}
			if($querycount > 0)
			{
				$query = $this->db->query("UPDATE files SET location='$file',ownerid='$userid',subjectid='$subject',version='$version' WHERE id = '$fileid'");
			}
			else
			{
				$query = $this->db->query("INSERT INTO files (location, ownerid, subjectid, viewed, version) VALUES ('$file','$userid','$subject',0, '$version')");
			}
			if(!$query)
			{
				redirect('inleveren', 'refresh');
			}
		}	
	}
}

?>