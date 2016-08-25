<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 8/25/2016
 * Time: 1:17 PM
 */
class Mobile extends CI_Controller{

    public function index(){
        $this->load->view('mobile/choose_view');
    }

    public function login(){
        $this->load->view('mobile/login_view');
    }

    public function signup(){
        $this->load->view('mobile/signup_view');
    }

    public function myself(){
        if (!$this->session->userdata('cellphone')){
            header('Location: '.base_url());
        }else{
            $this->load->view('mobile/myself_view');
        }

    }

    public function confirm(){
        if (!$this->session->userdata('cellphone')){
            header('Location: '.base_url());
        }else{
            $this->load->view('mobile/confirm_view');
        }
    }

    public function library(){
        if (!$this->session->userdata('cellphone')){
            header('Location: '.base_url());
        }else{
            $this->load->view('mobile/library_view');
        }

    }
}