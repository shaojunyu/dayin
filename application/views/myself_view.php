<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印</title>
    <link rel="stylesheet" href="css/myself.css">
</head>
<body>
<header class="clearfix">
    <a href="http://www.99dayin.com" class="logo">
        <img src="images/logo.png" alt="九九打印">
        <p>九九打印</p>
    </a>
    <nav>
        <ul>
            <li><a href="/dayin">首页</a></li>
            <li><a href="base.html" target="_blank">简介</a></li>
            <li><a href="library">我的文库</a></li>
            <li class="person-box">
                <ul id="sign-out" class="clearfix">
                    <li><a href="javascript:void(0)" class="person">个人中心</a></li>
                    <li><a href="javascript:void(0)" class="so">退出登录</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<div class="cover"></div> <!-- 背景模糊遮罩 -->
<div id="pay">
    <div class="pay-way">
        支付方式<span>X</span>
    </div>
    <div class="chioce">
        请选择支付方式~
    </div>
    <div class="wx-zfb">
        <a class="wx" target="_blank" href="javascript:void(0)">微信支付</a>
        <a class="zfb" target="_blank" href="javascript:void(0)">支付宝支付</a>
    </div>
</div>
<div id="paying">
    <div class="inpay">
        支付中<span>X</span>
    </div>
    <div class="inpay-info">
        <p>您将转至支付页面进行支付！</p>
        <p>请您在新打开的支付页面进行支付，支付完成前请不要关闭此窗口。</p>
    </div>
    <div class="pay-status">
        <a class="pay-done" href="javascript:void(0)">已完成支付</a>
        <a class="pay-problem" href="javascript:void(0)">支付遇到问题</a>
    </div>
</div>
<div id="cancel-submit">
    <div class="cancel-top">
        确认删除<span>X</span>
    </div>
    <div class="cancel-info">
        确认取消此订单？
    </div>
    <div class="cancel-sub">
        <a class="cancel-btn" href="javascript:void(0)">确认删除</a>
    </div>
</div>

<div class="prompt-box"></div> <!-- 表单错误提示框 -->

<div class="container clearfix">
    <div class="person-info">
        <p class="user">用户<?php echo $this->session->userdata('cellphone');?></p>
        <p class="user-info">个人信息</p>
        <p class="phone-num">手机：<?php echo $this->session->userdata('cellphone');?></p>
        <p class="school-info">学校：<?php echo $this->session->userdata('school');?></p>


        <input type="button" class="save-info" value="保存修改">
    </div>
    <div class="order-info">
        <span class="untreated">未处理订单<div></div></span>
        <span class="history">历史订单<div></div></span>
        <div class="order-box">
            <div class="order-top">
                <div>
                    <span class="order-1">订单信息</span>
                    <span class="order-2">操作</span>
                </div>
            </div>
            <div class="order-list">
            <?php
            $this->db->where('cellphone',$this->session->userdata('cellphone'));
            //$this->db->where('statePAID',null);
            $this->db->where('state != ','CANCELED');
            $this->db->where('state != ','DONE');
            $this->db->order_by('Id', 'DESC');
            $res = $this->db->get('order')->result_array();
            foreach ($res as $order){
                //var_dump($this->session->userdata('cellphone'));
            ?>
                <div class="nodo">
							<span class="order-1">
								<p class="order-num">订单编号：<span><?php echo $order['Id'];?></span></p>
								<p class="address">收货地址：
                                    <?php
                                    if ($order['deliveryMode'] == 'self'){
                                        echo $order['shop'];
                                    }else{
                                        echo $order['area'].$order['buildingNum'].'栋'.$order['roomNum'];
                                    }
                                    ?>
                                </p>
								<p class="wrapper clearfix"><span class="left">包含文件：</span><span class="right">
                                 <?php
                                 $content = json_decode($order['content']);
                                 foreach ($content as $file){
                                     echo $file->fileName.'<br>';
                                 }
                                 ?>
                                    </span></p>
							</span>
                                <?php if ($order['state'] == 'UNPAID'){ ?>
							<span class="order-2">
								<p class="toPay"><span>去付款</span></p>
								<p class="cancel"><span>取消订单</span></p>
							</span>
                            <?php }?>
                    <p class="status">总价：<?php echo $order['total'];?>元&nbsp;&nbsp;&nbsp;&nbsp;收货方式：
                        <?php
                        if ($order['deliveryMode'] == 'self') {
                            echo '到店自取';
                        }else{
                            echo '送货上门';
                        }
                        ?>&nbsp;&nbsp;&nbsp;&nbsp;订单状态：
                        <?php
                            if ($order['state'] == 'PAID'){
                                echo '已支付，正在等待打印';
                                //echo
                            }else if ($order['state'] == 'PRINTED'){
                                echo '已打印完成，正在配送中';
                            }else if ($order['state'] == 'UNPAID'){
                                echo '未支付';
                            }
                        ?>
                    </p>
                    <hr />
                </div>
            <?php
            }
                //end $res foreach
            ?>


                <?php
                $this->db->where('cellphone',$this->session->userdata('cellphone'));
                $this->db->where('state','DONE');
                $this->db->order_by('Id', 'DESC');
                $res = $this->db->get('order')->result_array();
                foreach ($res as $order){
                ?>
                <div class="done">
							<span class="order-1">
								<p class="order-num">订单编号：<?php echo $order['Id'];?></p>
								<p class="address">收货地址：<?php
                                    if ($order['deliveryMode'] == 'self'){
                                        echo $order['shop'];
                                    }else{
                                        echo $order['area'].$order['buildingNum'].'栋'.$order['roomNum'];
                                    }
                                    ?></p>
								<p class="wrapper clearfix"><span class="left">包含文件：</span><span class="right">
                                        <?php
                                        $content = json_decode($order['content']);
                                        foreach ($content as $file){
                                            echo $file->fileName.'<br>';
                                        }
                                        ?>
                                    </span></p>
							</span>
							<span class="order-2">
								<p class="add-print"><span></span></p>
							</span>
                    <p class="status">总价：<?php echo $order['total'];?>元&nbsp;&nbsp;&nbsp;&nbsp;收货方式：
                        <?php
                        if ($order['deliveryMode'] == 'self') {
                            echo '到店自取';
                        }else{
                            echo '送货上门';
                        }
                        ?>&nbsp;&nbsp;&nbsp;&nbsp;支付状态：已完成</p>
                    <hr />
                </div>
                <?php }
                //end $res foreach
                ?>
            </div>
        </div>
    </div>
</div>

<footer class="clearfix">
        <div class="footbox">
            <p><a href="doc/base.html" target="_blank">关于文库</a></p>
            <p><a href="doc/base.html" target="_blank">文库简介</a></p>
            <p><a href="doc/base.html" target="_blank">使用说明</a></p>
            <p><a href="doc/base.html" target="_blank">鼓励分享</a></p>
        </div>
        <div class="footbox">
            <p><a href="doc/orderServe.html" target="_blank">订单服务</a></p>
            <p><a href="doc/orderServe.html" target="_blank">购买指南</a></p>
            <p><a href="doc/orderServe.html" target="_blank">支付方式</a></p>
            <p><a href="doc/orderServe.html" target="_blank">送货政策</a></p>
        </div>
        <div class="footbox">
            <p><a href="doc/company.html" target="_blank">关于公司</a></p>
            <p><a href="doc/company.html" target="_blank">公司简介</a></p>
            <p><a href="doc/company.html" target="_blank">加入我们</a></p>
            <p><a href="doc/company.html" target="_blank">联系我们</a></p>
        </div>
        <div class="footbox">
            <p><a href="doc/aboutUs.html" target="_blank">关于我们</a></p>
            <p><a href="doc/aboutUs.html" target="_blank">新浪微博</a></p>
            <p><a href="doc/aboutUs.html" target="_blank">官方微博</a></p>
            <p><a href="doc/aboutUs.html" target="_blank">官方贴吧</a></p>
        </div>
        <div class="ewm">
            <img src="images/ewm.png">
        </div>
        <p class="copyright">&copy;2016 九九打印版权所有 鄂ICP备15018392号</p>
    </footer>
<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="script/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
<script type="text/javascript" src="script/person.js"></script>
</body>
</html>