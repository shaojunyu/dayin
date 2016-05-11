<?php
/**
 * Created by PhpStorm.
 * User: yushaojun
 * Date: 5/10/2016
 * Time: 7:02 PM
 */
class Pay extends CI_Controller{

    private $pingpp_charge;


    public function index(){

        if(!$this->session->userdata('cellphone')){
            http_response_code(400);
            exit('bad request');
        }

        $orderId = $this->input->get('orderId');
        $channel = $this->input->get('channel');
        if ($orderId == null or $channel == null or !in_array($channel,array('wx_pub_qr','alipay_pc_direct'))){
            http_response_code(400);
            exit('bad request');
        }

        $this->db->where('Id',$orderId);
        $this->db->where('cellphone',$this->session->userdata('cellphone'));
        $this->db->where('state','UNPAID');
        $res = $this->db->get('order')->result_array();
        if (count($res) != 1){
            http_response_code(400);
            exit('bad request');
        }
        //获取订单信息
        $res = $res[0];
        $total = $res['total'];
        //echo $total;
        require_once APPPATH.'third_party/pingpp/init.php';
        $api_key = 'sk_live_bOz9YlaOHrS7dFw9yYlUif7R';
        $app_id = 'app_SO0anHPWznHCbL0y';

        \pingpp\Pingpp::setApiKey($api_key);
        //支付渠道
        switch ($channel){
            case 'wx_pub_qr':
                $extra = array(
                    'product_id' => 'print'
                );
                break;
            case 'wx_pub':
                $extra = array(
                    'open_id' => 'wxd781831d64bb0674');
                break;
            case 'alipay_pc_direct':
                $extra = array(
                    'success_url' => 'http://www.99dayin.com/myself');
                break;
            default:
                $extra = array();
                break;
        }
        try{
            $ch = Pingpp\Charge::create(array(
                'subject' => '99打印在线支付',
                'body' => '文档打印',
                'amount'=>$total * 100,
                'order_no'=>$orderId.time(),
                'currency'  => 'cny',
                'extra'     => $extra,
                'channel'   => $channel,
                'client_ip' => '127.0.0.1',
                'app'       => array('id' => $app_id)
            ));
            $this->pingpp_charge = $ch;
            //var_dump($ch->__toArray());
            //更新数据库
            $this->db->where('Id',$orderId);
            $this->db->where('cellphone',$this->session->userdata('cellphone'));
            $this->db->update('order',array('pingppId'=>$ch->__toArray()['id']));

            //加载视图
            if ($channel == 'wx_pub_qr'){
                $this->load->view('pay_view',array('orderId'=>$orderId,'charge'=>$ch));
            }elseif ($channel == 'alipay_pc_direct'){
                $alipay_pc_direct = $ch->__toStdObject()->credential->alipay_pc_direct;
                $alipay_pc_direct_array = array();
                foreach ($alipay_pc_direct as $k => $v){
                    $alipay_pc_direct_array[$k] = $v;
                }
                echo '正在跳转至支付宝支付页面...';
                $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='https://mapi.alipay.com/gateway.do?_input_charset=utf-8' method='post'>";
                while (list ($key, $val) = each ($alipay_pc_direct_array)) {
                    $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/><br/>";
                }

                //submit按钮控件请不要含有name属性
                $sHtml = $sHtml."</form>";

                $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
                echo  $sHtml;
            }
        }catch (\Pingpp\Error\Base $e) {
            header('Status: ' . $e->getHttpStatus());
            echo($e->getHttpBody());
        }
        //
    }

}