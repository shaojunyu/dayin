<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 8/25/2016
 * Time: 1:17 PM
 */
class Mobile extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

    }

    public function index(){
        $this->load->view('mobile/choose_view');
    }

    public function login(){
        $this->load->view('mobile/login_view');
    }

    public function wechat_login(){
        $code = $this->input->get('code');
        if (!empty($code)){
            //echo $code;
            //获取token
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxd781831d64bb0674&secret=3bcc9249cce5cba968e79f232abf228e&code='.$code.'&grant_type=authorization_code ';
            $ch  = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($ch);
            $data = json_decode($data);
            curl_close($ch);
            if (isset($data->errcode)){
                echo '<h1>invalid code!请重试</h1>';
            }else{
                $openid = $data->openid;
                //检查openid是否存在
                $this->db->where('openid',$openid);
                $res = $this->db->get('user')->result_array();
                //var_dump($res);
                if (count($res) == 1){//直接登陆
                    $res = $res[0];
                    $cellphone = $res['cellphone'];
                    $this->User->wetchat_login($cellphone);
                    header('Location: ' . base_url('/mobile/library'));
                }else{
                    //绑定页面
                    $this->load->view('mobile/authorize_view',array('openid'=>$openid));
                }
            }
        }else{
            echo '<h1>参数错误</h1>';
            exit();
        }
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
            $this->load->model('Cart_model','Cart');
            $this->load->view('mobile/confirm_view');
        }
    }

    public function library()
    {
        if (!$this->session->userdata('cellphone')) {
            header('Location: ' . base_url());
        } else {
            //开放文库
            $this->db->where('isOpen','true');
            $this->db->not_like('admin',$this->session->userdata('cellphone'));
            $res = $this->db->get('library')->result_array();
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
            //自建的文库
            $this->db->like('admin',$this->session->userdata('cellphone'));
            $r = $this->db->get('library')->result_array();
            foreach ($r as $lib){
                $id = $lib['Id'];
                $this->db->where('isOpen','false');
                $this->db->where('Id',$id);
                $mylib = $this->db->get('library')->result_array();
                if (count($mylib) == 1) {
                    $res[] = $mylib[0];
                }
            }


            //申请中的文库
            $this->db->where('cellphone', $this->session->userdata('cellphone'));
            $this->db->where('state', 'applying');
            $r = $this->db->get('library_users')->result_array();
            $applyingLib = array();
            foreach ($r as $lib){
                $id = $lib['libraryId'];
                $this->db->where('isOpen', 'false');
                $this->db->where('Id', $id);
                $mylib = $this->db->get('library')->result_array();
                if (count($mylib) == 1) {
                    $applyingLib[] = $mylib[0];
                }
            }

            //var_dump($applyingLib);
            $this->load->view('mobile/library_view', array('myLib' => $res, 'applyingLib' => $applyingLib));
        }

    }


}