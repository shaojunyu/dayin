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

    //文件上传相关
    private function gmt_iso8601($time) {
        $dtStr = date("c", $time);
        $mydatetime = new DateTime($dtStr);
        $expiration = $mydatetime->format(DateTime::ISO8601);
        $pos = strpos($expiration, '+');
        $expiration = substr($expiration, 0, $pos);
        return $expiration."Z";
    }

    public function getUploadToken(){
        //$username = $this->session->userdata('username');

        require_once APPPATH.'third_party/oss_php_sdk_20140625/sdk.class.php';
        $id= 'GtzMAvDTnxg72R04';
        $key= 'VhD2czcwLVAaE7DReDG4uEVSgtaSYK';
        $host = 'http://99dayin.oss-cn-hangzhou.aliyuncs.com';
        $callback_body = '{"callbackUrl":"http://www.99dayin.com:12345","callbackHost":"www.99dayin.com","callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}","callbackBodyType":"application/x-www-form-urlencoded"}';
        $base64_callback_body = base64_encode($callback_body);
        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        $oss_sdk_service = new alioss($id, $key, $host);
        $dir = 'user_upload/'.$this->session->userdata('cellphone').'/';

        //最大文件大小.用户可以自己设置
        $condition = array(0=>'content-length-range', 1=>0, 2=>1048576000);
        $conditions[] = $condition;

        //表示用户上传的数据,必须是以$dir开始, 不然上传会失败,这一步不是必须项,只是为了安全起见,防止用户通过policy上传到别人的目录
        $start = array(0=>'starts-with', 1=>'$key', 2=>$dir);
        $conditions[] = $start;


        //这里默认设置是２０２０年.注意了,可以根据自己的逻辑,设定expire 时间.达到让前端定时到后面取signature的逻辑
        $arr = array('expiration'=>$expiration,'conditions'=>$conditions);
        //echo json_encode($arr);
        //return;
        $policy = json_encode($arr);
        $base64_policy = base64_encode($policy);
        $string_to_sign = $base64_policy;
        $signature = base64_encode(hash_hmac('sha1', $string_to_sign, $key, true));

        $response = array();
        $response['accessid'] = $id;
        $response['host'] = $host;
        $response['policy'] = $base64_policy;
        $response['signature'] = $signature;
        $response['expire'] = $end;
        $response['callback'] = $base64_callback_body;
        //这个参数是设置用户上传指定的前缀
        $response['dir'] = $dir;
        echo json_encode($response);
    }


    /**
     * function confirmMD5 检查文件是否已存在
     * @param
     * @return
     * @author yushaojun
     */
    public function confirmMD5(){
        $this->check_post_data(array('fileMD5'));
        $this->db->where('fileMD5',$this->post_data->fileMD5);
        $this->db->get('file_info')->result_array();
        if ($this->db->affected_rows() >= 1){
            $this->echo_msg(true,'yes');
        }else{
            $this->echo_msg(true,'no');
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