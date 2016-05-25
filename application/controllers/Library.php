<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 5/25/2016
 * Time: 8:08 PM
 */

class Library extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->session->userdata('cellphone')){
            header('Location: '.base_url());
        }
    }

    public function index(){
        $this->load->view('library_view');
    }
}