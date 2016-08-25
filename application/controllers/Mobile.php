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

    public function library()
    {
        if (!$this->session->userdata('cellphone')) {
            header('Location: ' . base_url());
        } else {
            //开放文库
            $this->db->where('isOpen', 'true');
            $res = $this->db->get('library')->result_array();
//            var_dump($res);
            //加入的文库
            $this->db->where('cellphone', $this->session->userdata('cellphone'));
            $this->db->where('state', 'accepted');
            $r = $this->db->get('library_users')->result_array();
            foreach ($r as $lib) {
                $id = $lib['libraryId'];
                $this->db->where('isOpen', 'false');
                $this->db->where('Id', $id);
                $mylib = $this->db->get('library')->result_array();
                if (count($mylib) == 1) {
                    $res[] = $mylib[0];
                }
            }

            //申请中的文库
            $this->db->where('cellphone', $this->session->userdata('cellphone'));
            $this->db->where('state', 'applying');
            $applyingLib = $this->db->get('library_users')->result_array();

            //var_dump($applyingLib);
            $this->load->view('mobile/library_view', array('myLib' => $res, 'applyingLib' => $applyingLib));
        }

    }


}