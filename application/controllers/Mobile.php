<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 8/25/2016
 * Time: 1:17 PM
 */
class Mobile extends CI_Controller{
    public function login(){
        $this->load->view('mobile/login_view');
    }

    public function signup(){
        $this->load->view('mobile/signup_view');
    }

    public function myself(){
        $this->load->view('mobile/myself_view');
    }

    public function library(){
        $this->load->view('mobile/library_view');
    }
}