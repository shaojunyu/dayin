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
                exit();
            }
        } else {
            $this->echo_msg(false,'post data required!');
            exit();
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
        $callback_body = '{"callbackUrl":"http://hook.99dayin.com/uploadcallback","callbackHost":"hook.99dayin.com","callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}","callbackBodyType":"application/x-www-form-urlencoded"}';
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
        $this->needSession();
        $this->check_post_data(array('fileMD5'));
        $this->db->where('fileMD5',$this->post_data->fileMD5);
        $this->db->get('file_info')->result_array();
        if ($this->db->affected_rows() >= 1){
            $this->echo_msg(true,'no');
        }else{
            $this->echo_msg(true,'no');
        }
    }

    /**
     * function uploadACK 文件上传成功，前端回掉
     * @param
     * @return
     * @author yushaojun
     */
    public function uploadACK(){
        $this->needSession();
        $this->check_post_data(array('fileName','fileMD5'));
        //文件加入购物车
        $this->load->model('Cart_model','Cart');

        if ($this->Cart->add_item($this->post_data->fileName,$this->post_data->fileMD5)){
            $this->echo_msg(true,'添加成功');
            $this->db->insert('user_upload',array(
                'cellphone'=>$this->session->userdata('cellphone'),
                'fileName'=>$this->post_data->fileName,
                'fileMD5'=>$this->post_data->fileMD5
            ));
        }else{
            $this->echo_msg(false,'添加失败，稍后重试');
        }
    }

    /**
     * function deleteItem 删除购物车中一项
     * @param
     * @return
     * @author yushaojun
     */
    public function deleteItem(){
        $this->needSession();
        $this->check_post_data(array('fileMD5'));
        $this->load->model('Cart_model','Cart');
        if ($this->Cart->delete_item($this->post_data->fileMD5)) {
            $this->echo_msg(true, '删除成功');
        }else{
            $this->echo_msg(false,'删除失败，稍后重试');
        }
    }

    /**
     * function printSettings 打印设定
     * @param
     * @return
     * @author yushaojun
     */
    public function printSettings(){
        $this->needSession();
        $this->check_post_data(array('fileMD5'));
        $this->load->model('Cart_model','Cart');
        $res = false;
        if (isset($this->post_data->paperSize)){
            $res = $this->Cart->printSettings($this->post_data->fileMD5,'paperSize',$this->post_data->paperSize);
        }elseif (isset($this->post_data->isTwoSides)){
            $res = $this->Cart->printSettings($this->post_data->fileMD5,'isTwoSides',$this->post_data->isTwoSides);
        }elseif (isset($this->post_data->amount)){
            $res = $this->Cart->printSettings($this->post_data->fileMD5,'amount',$this->post_data->amount);
        }elseif (isset($this->post_data->pptPerPage)){
            $res = $this->Cart->printSettings($this->post_data->fileMD5,'pptPerPage',$this->post_data->pptPerPage);
        }elseif (isset($this->post_data->direction)) {
            $res = $this->Cart->printSettings($this->post_data->fileMD5, 'direction', $this->post_data->direction);
        }elseif (isset($this->post_data->remark)){
            $res = $this->Cart->printSettings($this->post_data->fileMD5,'remark',$this->post_data->remark);
        }

        if ($res){
            $price_info = $this->Cart->calculate_price($this->post_data->fileMD5);
            echo json_encode(array("success"=>true,'price'=>$price_info['price'],'subTotal'=>$price_info['subTotal']));
            //$this->echo_msg(true,'修改成功');
        }else{
            $this->echo_msg(false,'修改失败');
        }
    }

    /**
     * function getProgess
     * @param
     * @return
     * @author yushaojun
     */
    public function getProgess(){
        $this->needSession();
        $this->check_post_data(array('fileMD5'));
        $this->db->where('fileMD5',$this->post_data->fileMD5);
        $res = $this->db->get('file_info')->result_array();
        if (count($res) == 0){
            $this->echo_msg(true,'processing');
        }else{
            $res = $res[0];
            $pages = $res['pages'];
            if ($pages == 0){
                $this->echo_msg(true,'fail');
            }else{
                $this->echo_msg(true,'done');
            }
        }
    }

    /**
     * function createOrder 创建订单
     *
     * @param
     * @return
     * @author yushaojun
     */
    public function createOrder(){
        $this->needSession();
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $res = $this->db->get('cart')->result_array();
        if (count($res) == 0){
            $this->echo_msg(false,'购物车为空');
            exit();
        }

        if($this->post_data->deliveryMode == 'self'){
            $this->check_post_data(array('shop','total'));
            $total = 0;

            foreach ($res as $e){
                $total += $e['subTotal'];
            }
            $this->db->insert('order',array(
                'cellphone'=>$this->session->userdata('cellphone'),
                'shop'=>$this->post_data->shop,
                'deliveryMode'=>$this->post_data->deliveryMode,
                'total'=>$total,
                'state'=>'UNPAID',
                'content'=>json_encode($res)
            ));
        }else{
            $this->check_post_data(array('area','buildingNum','roomNum','receiver','receiverPhone','deliveryMode','total'));
            $total = 0;

            foreach ($res as $e){
                $total += $e['subTotal'];
            }

            $this->db->insert('order',array(
                'cellphone'=>$this->session->userdata('cellphone'),
                'area'=>$this->post_data->area,
                'buildingNum'=>$this->post_data->buildingNum,
                'roomNum'=>$this->post_data->roomNum,
                'receiver'=>$this->post_data->receiver,
                'receiverPhone'=>$this->post_data->receiverPhone,
                'deliveryMode'=>$this->post_data->deliveryMode,
                //'deliveryTime'=>$this->post_data->deliveryTime,
                'total'=>$total,
                'shop'=>'东篱阳光图文',
                'state'=>'UNPAID',
                'content'=>json_encode($res)
            ));
        }





        //删除购物车
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->delete('cart');

        $this->echo_msg(true,'');
        //var_dump($this->post_data);
    }

    /**
     * function cancelOrder 取消订单
     * @param
     * @return
     * @author yushaojun
     */
    public function cancelOrder(){
    	$this->needSession();
    	$this->check_post_data(array('orderId'));
        $this->db->where('Id',$this->post_data->orderId);
        $this->db->where('state !=','PAID');
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->update('order',array('state'=>'CANCELED'));
        $this->echo_msg(true,'');
    }

    /**
     * function copyOrder 加印订单
     * @param
     * @return
     * @author yushaojun
     */
    public function copyOrder(){
        $this->needSession();
        $this->check_post_data(array('orderId'));
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->where('Id',$this->post_data->orderId);
        $res = $this->db->get('order')->result_array();
        if (count($res) == 1){
            $res = $res[0];
            $this->db->insert('order',array(
                'cellphone'=>$res['cellphone'],
                'shop'=>$res['shop'],
                'area'=>$res['area'],
                'buildingNum'=>$res['buildingNum'],
                'roomNum'=>$res['roomNum'],
                'receiver'=>$res['receiver'],
                'receiverPhone'=>$res['receiverPhone'],
                'deliveryMode'=>$res['deliveryMode'],
                'total'=>$res['total'],
                'state'=>'UNPAID',
                'content'=>$res['content']
            ));
            $this->echo_msg(true,false);
        }else{
            $this->echo_msg(false,false);
        }
    }


    /**
     * function isPaid 是否已支付
     * @param
     * @return
     * @author yushaojun
     */
    public function isPaid(){
        $this->needSession();
        $this->check_post_data(array('orderId'));
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->where('Id',$this->post_data->orderId);
        $res = $this->db->get('order')->result_array();
        if (count($res) == 1){
            $res = $res[0];
            if ($res['state'] == 'PAID'){
                $this->echo_msg(true,'已支付');
            }else{
                $this->echo_msg(false,'未支付');
            }
        }else{
            $this->echo_msg(false,'未支付');
            
        }
    }

    /**
     * function acceptUser 同意用户加入
     * @param
     * @return
     * @author yushaojun
     */
    public function acceptUser(){
    	$this->needSession();
    	$this->check_post_data(array('libraryId','cellphone'));
    	$this->db->where('cellphone',$this->post_data->cellphone);
    	$this->db->where('libraryId',$this->post_data->libraryId);
    	$this->db->update('library_users',array('state'=>'accepted'));
        $this->echo_msg(true,'');
    }


    /**
     * function rejectUser 拒绝用户加入
     * @param
     * @return
     * @author yushaojun
     */
    public function rejectUser(){
        $this->needSession();
        $this->check_post_data(array('libraryId','cellphone'));
        $this->db->where('cellphone',$this->post_data->cellphone);
        $this->db->where('libraryId',$this->post_data->libraryId);
        $this->db->update('library_users',array('state'=>'rejected'));
        $this->echo_msg(true,'');
    }

    /**
     * function createFolder 创建文件夹
     * @param
     * @return
     * @author yushaojun
     */
    public function createFolder(){
        $this->needSession();
        $this->check_post_data(array('libraryId','folder'));
        $this->db->insert('library_files',array(
            'libraryId'=>$this->post_data->libraryId,
            'folder'=>$this->post_data->folder
            ));
        $this->echo_msg(true);
    }

    /**
     * function deteleFolder 删除文件夹
     * @param
     * @return
     * @author yushaojun
     */
    public function deteleFolder(){
        $this->needSession();
        $this->check_post_data(array('libraryId','folder'));
        $this->db->where('libraryId',$this->post_data->libraryId);
        $this->db->where('folder',$this->post_data->folder);
        $this->db->delete('library_files');
        $this->echo_msg(true);
    }

    /**
     * 文库文件上传token获取
     */
    public function getLibUploadToken(){
        $this->needSession();
        $this->check_post_data(array('libraryId','folder'));
        //查询folder是否存在
        $this->db->where('libraryId',$this->post_data->libraryId);
        $this->db->where('folder',$this->post_data->folder);
        $this->db->get('library_files');
        if ($this->db->affected_rows() == 0){
            $this->echo_msg(false,'文件夹不存在');
            exit();
        }

        require_once APPPATH.'third_party/oss_php_sdk_20140625/sdk.class.php';
        $id= 'GtzMAvDTnxg72R04';
        $key= 'VhD2czcwLVAaE7DReDG4uEVSgtaSYK';
        $host = 'http://99dayin.oss-cn-hangzhou.aliyuncs.com';
        $callback_body = '{"callbackUrl":"http://hook.99dayin.com/uploadcallback","callbackHost":"hook.99dayin.com","callbackBody":"filename=${object}&size=${size}&mimeType=${mimeType}&height=${imageInfo.height}&width=${imageInfo.width}","callbackBodyType":"application/x-www-form-urlencoded"}';
        $base64_callback_body = base64_encode($callback_body);
        $now = time();
        $expire = 30; //设置该policy超时时间是10s. 即这个policy过了这个有效时间，将不能访问
        $end = $now + $expire;
        $expiration = $this->gmt_iso8601($end);

        $oss_sdk_service = new alioss($id, $key, $host);
        $dir = 'library/'.$this->post_data->libraryId.'/'.$this->post_data->folder.'/';

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
     * function libUploadACK 文库文件上传完成回调
     * @param
     * @return
     * @author yushaojun
     */
    public function libUploadACK(){
        $this->needSession();
        $this->check_post_data(array('libraryId','folder','fileName','fileMD5'));
        $this->db->insert('library_files',array(
            'fileName'=>$this->post_data->fileName,
            'fileMD5'=>$this->post_data->fileMD5,
            'libraryId'=>$this->post_data->libraryId,
            'folder'=>$this->post_data->folder
        ));
        $this->db->insert('user_upload',array(
            'cellphone'=>$this->session->userdata('cellphone'),
            'fileName'=>$this->post_data->fileName,
            'fileMD5'=>$this->post_data->fileMD5
        ));
        $this->echo_msg(true);
    }

    public function deleteLibFile(){
        $this->needSession();
        $this->check_post_data(array('libraryId','folder','fileName'));
        $this->db->where('libraryId',$this->post_data->libraryId);
        $this->db->where('folder',$this->post_data->folder);
        $this->db->where('fileName',$this->post_data->fileName);
        $this->db->delete('library_files');
        $this->echo_msg(true);
    }

    public function getLibFiles(){
        $this->needSession();
        $this->check_post_data(array('libraryId','folder'));
        $this->db->where('libraryId',$this->post_data->libraryId);
        $this->db->where('folder',$this->post_data->folder);
        $this->db->where('fileName <>',null);
        $res = $this->db->get('library_files')->result_array();
        echo json_encode($res);
    }

    /**
     * function searchLib 查找文库
     * @param
     * @return
     * @author yushaojun
     */
    public function searchLib(){
        $this->needSession();
        $this->check_post_data(array('libraryId'));
        //$remark = '';
        $this->db->select('name');
        $this->db->where('Id',$this->post_data->libraryId);
        $res = $this->db->get('library')->result_array();
        if (count($res) == 1){
            $res = $res[0];
            echo json_encode(array('libName'=>$res['name']));
        }else{
            $this->echo_msg(false,'文库不存在');
        }
        //var_dump($res);
    }

    /**
     * function joinLib 加入文库申请
     * @param
     * @return
     * @author yushaojun
     */
    public function joinLib(){
        $this->needSession();
        $this->check_post_data(array('libraryId'));
        $remark = '';
        $this->db->insert('library_users',array(
            'libraryId'=>$this->post_data->libraryId,
            'cellphone'=>$this->session->userdata('cellphone'),
            'state'=>'applying',
            'remark'=>$this->post_data->remark
        ));
        $this->echo_msg(true.false);
    }

    /**
     * function addToCart 文库文件加入到购物车
     * @param
     * @return
     * @author yushaojun
     */
    public function addToCart(){
        $this->needSession();
        $this->check_post_data(array('files'));
        $files = $this->post_data->files;
        foreach ($files as $file){
            $this->load->model('Cart_model','Cart');
            $this->Cart->add_item($file->fileName,$file->fileMD5);
        }
    }
    
/*
 * ----------------------------------------------------------------------------------------
 * 以下是private函数，供本类调用
 * ----------------------------------------------------------------------------------------
 */

    /**
     * function needSession 验证session，不存在就退出
     * @param
     * @return
     * @author yushaojun
     */
    private function needSession(){
        if ( $this->session->userdata('cellphone') == null ){
            exit('api false,need session');
        }else{
            return;
        }
    }

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
            if (isset($this->post_data->$key)){
                if ($this->post_data->$key == null){
                    $this->echo_msg('false','参数不完整');
                    exit();
                }
            }
        }
        return true;
    }

    //封装数据，json格式，返回客户端
    private function echo_msg($isSuccess = false,$msg = ''){
        echo json_encode(array("success"=>$isSuccess,'msg'=>$msg));
    }
}