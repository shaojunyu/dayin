<?php
/**
 * Created by PhpStorm.
 * User: 96853
 * Date: 5/7/2016
 * Time: 7:13 PM
 */

class Upload extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->session->userdata('cellphone')){
            header('Location: '.base_url());
        }
    }

    public function index(){
        //$this->load->view('upload_view');
        var_dump($this->session->userdata('cellphone'));
    }
}