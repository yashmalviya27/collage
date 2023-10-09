<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Renumeration extends CI_Controller
{
 
    function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$row = $this->session->userdata('user');

		if (empty($row['computer_code'])) {
			redirect(base_url('Login'));
		}
		$this->load->model('RenumerationM');
	}
    public function index()
    {
        $this->load->view('new_template/faculty_common');
        $this->load->view('Renumeration/manage_roles');
		$this->load->view('new_template/new_footer');
    }
	public function insert_role_1()
	{
		$this->load->view;
	}

  

}