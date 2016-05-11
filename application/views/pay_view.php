<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印在线支付</title>
    <link rel="stylesheet" type="text/css" href="css/pay.css">
</head>
<body>
<header>
    <img src="images/logo_pay.png" alt="微信支付">
</header>
<div class="prompt-box"></div> <!-- 表单错误提示框 -->
<div class="container">
    <img src="http://qr.liantu.com/api.php?el=h&text=<?php echo $charge->__toArray()['credential']->__toArray()['wx_pub_qr']; ?>">
    <p class="info">请使用微信扫描<br />二维码以完成支付</p>
    <div class="order-info">
        <span class="l"></span>
        <span class="r"></span>
        <p class="money">￥<?php echo $charge->__toArray()['amount'] / 100;?></p>
        <hr />
        <p class="online">99打印在线支付</p>
        <p class="order-num">订单编号：<span><?php echo $orderId;?></span></p>
        <hr />
        <p class="trade clearfix">
            <span class="trade-l">交易单号</span>
            <span class="trade-r"><?php echo $charge->__toArray()['id'];?></span>
        </p>
        <p class="time clearfix">
            <span class="time-l">创建时间</span>
            <span class="time-r"><?php echo date('Y-m-d H:i:s',$charge->__toArray()['created']);?></span>
        </p>
        <div class="end"></div>
    </div>
</div>

<footer>
    &copy;武汉巧然电子科技有限公司
</footer>
<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="http://7xnadt.com1.z0.glb.clouddn.com/md5.js"></script>
<script type="text/javascript" src="script/pay.js"></script>
</body>
</html>