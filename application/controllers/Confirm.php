<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 5/9/2016
 * Time: 6:51 PM
 */

class Confirm extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        if (!$this->session->userdata('cellphone')){
            header('Location: '.base_url());
        }

    }

    public function index(){
        $this->load->view('confirm_view');
    }
}