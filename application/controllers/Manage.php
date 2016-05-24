<?php

class Manage extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		if (!$this->session->userdata('cellphone')){
			header('Location: '.base_url());
		}
		
		if ($this->session->userdata('role') != 'LIBADMIN') {
			header('Location: '.base_url('upload'));
		}
		
		//获取文库信息
	}
	
	public function index() {
		//var_dump($this->session->userdata());
		$this->db->where('admin',$this->session->userdata('cellphone'));
		$res = $this->db->get('library')->result_array();
		if (count($res) == 1) {
			$res = $res[0];
			//var_dump($res);
			$this->load->view('manage_view',array('libInfo'=>$res));
		}else{
			header('Location: '.base_url('upload'));
		}
		
	}
	
	
	
}