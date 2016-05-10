<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印</title>
    <link rel="stylesheet" href="css/printList.css">
</head>
<body>
<header class="clearfix">
    <a href="index.html" class="logo">
        <img src="images/logo.png" alt="九九打印">
        <p>九九打印</p>
    </a>
    <nav>
        <ul>
            <li><a href="index.html">首页</a></li>
            <li><a href="base.html" target="_blank">简介</a></li>
            <li><a href="javascript:void(0)">我的文库</a></li>
            <li class="person-box">
                <ul id="sign-out" class="clearfix">
                    <li><a href="javascript:void(0)" class="person">个人中心</a></li>
                    <li><a href="javascript:void(0)" class="so">退出登录</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<div class="prompt-box"></div> <!-- 表单错误提示框 -->

<div class="container clearfix">
    <div class="person-info">
        <p class="user">用户1234</p>
        <p class="user-info">个人信息</p>
        <p class="phone-num">手机：12345678910</p>
        <p class="school-info">学校：华中科技大学</p>
        <p class="receipt-info">收货信息</p>
        <p class="recv-phone">电话：12345678910</p>
        <p class="recv-address clearfix"><span>收货地址：</span><span class="now-address">韵苑22栋503</span><input type="text" class="change-address" placeholder="输入新地址"></p>
        <input type="button" class="edit-info" value="编辑信息">
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
                <div class="nodo">
							<span class="order-1">
								<p class="order-num">订单编号：1234253453</p>
								<p class="address">收货地址：韵苑22栋503</p>
								<p class="wrapper clearfix"><span class="left">包含文件：</span><span class="right">1.doc, 2.doc, 3.ppt, 4.ppt,ahdybcszd.doc1.doc, 2.doc, 3.ppt, 4.ppt,ahdybcszd.doc1.doc, 2.doc, 3.ppt, 4.ppt,ahdybcszd.doc</span></p>
							</span>
							<span class="order-2">
								<p class="toPay"><span>去付款</span></p>
								<p class="cancel"><span>取消订单</span></p>
							</span>
                    <p class="status">总价：120元&nbsp;&nbsp;&nbsp;&nbsp;收货方式：送货上门&nbsp;&nbsp;&nbsp;&nbsp;支付状态：未支付</p>
                    <hr />
                </div>
                <div class="done">
							<span class="order-1">
								<p class="order-num">订单编号：1234253453</p>
								<p class="address">收货地址：韵苑22栋503</p>
								<p class="wrapper clearfix"><span class="left">包含文件：</span><span class="right">1.doc, 2.doc, 3.ppt, 4.ppt,ahdybcszd.doc</span></p>
							</span>
							<span class="order-2">
								<p class="add-print"><span>加印</span></p>
							</span>
                    <p class="status">总价：120元&nbsp;&nbsp;&nbsp;&nbsp;收货方式：送货上门&nbsp;&nbsp;&nbsp;&nbsp;支付状态：已支付</p>
                    <hr />
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="clearfix">
    <div class="footbox">
        <p><a href="base.html" target="_blank">关于文库</a></p>
        <p><a href="base.html" target="_blank">文库简介</a></p>
        <p><a href="base.html" target="_blank">使用说明</a></p>
        <p><a href="base.html" target="_blank">鼓励分享</a></p>
    </div>
    <div class="footbox">
        <p><a href="orderServe.html" target="_blank">订单服务</a></p>
        <p><a href="orderServe.html" target="_blank">购买指南</a></p>
        <p><a href="orderServe.html" target="_blank">支付方式</a></p>
        <p><a href="orderServe.html" target="_blank">送货政策</a></p>
    </div>
    <div class="footbox">
        <p><a href="company.html" target="_blank">关于公司</a></p>
        <p><a href="company.html" target="_blank">公司简介</a></p>
        <p><a href="company.html" target="_blank">加入我们</a></p>
        <p><a href="company.html" target="_blank">联系我们</a></p>
    </div>
    <div class="footbox">
        <p><a href="aboutUs.html" target="_blank">关于我们</a></p>
        <p><a href="aboutUs.html" target="_blank">新浪微博</a></p>
        <p><a href="aboutUs.html" target="_blank">官方微博</a></p>
        <p><a href="aboutUs.html" target="_blank">官方贴吧</a></p>
    </div>
    <div class="ewm">
        <img src="images/ewm.png">
    </div>
    <p class="copyright">&copy;2016 九九打印版权所有 鄂ICP备15018392号</p>
</footer>
<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="script/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="script/person.js"></script>
</body>
</html>