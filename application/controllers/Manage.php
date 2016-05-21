<?php

class Manage extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		if (!$this->session->userdata('cellphone')){
			header('Location: '.base_url());
		}
	}
	
	public function index() {
		$this->load->view('manage_view');
	}
	
	
	
}