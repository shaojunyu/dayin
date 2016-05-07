<?php
/**
 * Created by PhpStorm.
 * User: 96853
 * Date: 5/7/2016
 * Time: 9:19 PM
 */
class Test extends CI_Controller{
    public function index(){
        $this->load->model('user_model');
        $user = new User_model();

        $this->db->where('username','123');
        $r = $this->db->get('user');
        var_dump($r->result_array());

        var_dump($user->login('233','123456'));

        //$user->signup('123456','123456','华中科技大学');
        echo $user->login('123456','123456');
        $user->userInfo2session('123456');
        var_dump($this->session->userdata());
    }
}