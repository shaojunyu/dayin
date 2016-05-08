<?php
/**
 * Created by PhpStorm.
 * User: 96853
 * Date: 5/7/2016
 * Time: 11:14 PM
 */

class Api extends CI_Controller{
    private $post_data;

    public function __construct()
    {
        parent::__construct();
        if ($this->input->raw_input_stream){
            //解析post数据,以json格式接收的数据
            if($this->input->server('CONTENT_TYPE') === 'application/json'){
                $this->post_data = json_decode($this->input->raw_input_stream);
            }else{
                $this->echo_msg(false,'json formate data required!');
            }
        } else {
            $this->echo_msg(false,'post data required!');
        }
    }

    public function index(){
    }


    //用户相关

    /**
     * function login 用户登录
     * @param cellphone,password
     * @return json
     * @author yushaojun
     */
    public function login(){
        if (isset($this->post_data->cellphone) and isset($this->post_data->password)){
            $cellphone = $this->post_data->cellphone;
            $password = $this->post_data->password;

            //$this->model->load
            if ($this->User->login($cellphone,$password)){
                $this->echo_msg(true,'登录成功');
            }else{
                $this->echo_msg(false,'手机号或密码错误');
            }
        }else{
            $this->echo_msg(false,'data formate false');
        }
    }

    /**
     * function logout
     * @param
     * @return
     * @author yushaojun
     */
    public function logout(){
        $this->session->sess_destroy();
        $this->echo_msg(true);
    }

    public function loginBySmscode(){
        $this->check_post_data(array('cellphone','smscode'));

        $this->db->where('cellphone',$this->post_data->cellphone);
        $res = $this->db->get('user')->result_array();
        if (count($res) == 1){
            if ($this->verifySmsCode()){
                $this->User->userInfo2session($this->post_data->cellphone);
                $this->echo_msg(true,'登录成功');
            }
        }else{
            $this->echo_msg(false,'用户不存在，请注册！');
        }

    }

    /**
     * function signup 注册
     * @param
     * @return
     * @author yushaojun
     */
    public function signup(){
        if ($this->check_post_data(array('cellphone','password','school','smscode')) ){
            if (!$this->verifySmsCode()){
                $this->echo_msg(false,'验证码错误');
                exit();
            }
            try{
                $this->User->signup($this->post_data->cellphone,$this->post_data->password,$this->post_data->school);
                $this->echo_msg(true,'注册成功');
            }catch (Exception $e){
                $this->echo_msg(false,$e->getMessage());
            }
        }else{
            $this->echo_msg(false,'参数不完整');
        }
    }


    /**
     * function getSmscode 获取验证码
     * @param
     * @return
     * @author yushaojun
     */
    public function sendSmscode(){
        if ($this->check_post_data(array('cellphone'))){
            require_once APPPATH.'third_party/bmob/lib/BmobSms.class.php';
            try {
                $bmobSms = new BmobSms();
                $res = $bmobSms->sendSmsVerifyCode($this->post_data->cellphone,'register');
                $this->echo_msg(true,'验证码发送成功');
                exit();
            } catch (Exception $e) {
                $this->echo_msg(false,$e->__toString());
                exit();
            }
        }else{
            $this->echo_msg(false,'参数不完整');
        }
    }
/*
 * ----------------------------------------------------------------------------------------
 * 以下是private函数，供本类调用
 * ----------------------------------------------------------------------------------------
 */

    /**
     * function verifySmsCode 验证smscode
     * @param
     * @return
     * @author yushaojun
     */
    private function verifySmsCode()
    {
        require_once APPPATH.'third_party/bmob/lib/BmobSms.class.php';
        $this->check_post_data(array('cellphone','smscode'));
        $cellphone = $this->post_data->cellphone;
        $smsCode = $this->post_data->smscode;
        try {
            $bmobSms = new BmobSms();
            $res = $bmobSms->verifySmsCode($cellphone, $smsCode);
            return true;
            //$this->echo_msg(true, '验证码有效');
        } catch (Exception $e) {
            //$this->echo_msg(false, $e->error_msg);
            return false;
        }
    }

    /**
     * function check_post_data 检查post_data中是否含有需要的字段
     * @param array
     * @return true, false
     * @author yushaojun
     */
    private function check_post_data($check_list){
        foreach ($check_list as $key){
            if (!isset($this->post_data->$key)){
                $this->echo_msg('false','参数不完整');
                exit();
            }
        }
        return true;
    }

    //封装数据，json格式，返回客户端
    private function echo_msg($isSuccess = false,$msg = ''){
        echo json_encode(array("success"=>$isSuccess,'msg'=>$msg));
    }
}