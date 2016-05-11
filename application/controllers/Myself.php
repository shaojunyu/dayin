<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 5/10/2016
 * Time: 7:03 PM
 */
class Myself extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('cellphone')){
            $this->load->helper('url');
            header('Location: '.base_url());
        }
    }

    public function index(){
        $this->load->view('myself_view');
    }
}