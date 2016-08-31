<!DOCTYPE html>
<html>
<head lang="en">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>选择登入</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" href="css/mobile/reset.css">
	<style type="text/css">
		body {
			background-color: #6bc8f1;
		}
		.header {
			text-align: center;
			padding-top: 70px;
		}
		.header div {
			width: 10rem;
			height: 10rem;
			margin: 0 auto;
			background-color: #fff;
			border: 1px solid #fff;
			border-radius: 50%;
			display: table;
			min-width: 10rem;
			min-height: 10rem;
		}
		.header i {
			display: table-cell;
			vertical-align: middle;
		}
		.header img {
			width: 90%;
		}
		.header p {
			font-size: 130%;
			font-weight: 600;
			color: #fff;
			margin-top: 10px;
		}

		.choose {
			width: 70%;
			margin: 0 auto;
			padding-top: 60px;
		}
		.choose a {
			display: block;
			width: 100%;
			margin: 25px 0;
			background-color: #3991a0;
			color: #fff;
			font-size: 1.3rem;
			text-align: center;
			line-height: 3rem;
			border-radius: 5px;
		}
	</style>
</head>
<body>
	<div class="header">
		<div>
			<i><img src="images/mobile/logo.png"></i>
		</div>
		<p>四年打印</p>
	</div>

	<div class="choose">
		<a href="https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxd781831d64bb0674&redirect_uri=http://dayin.4nian.cc/mobile/wechat_login/&response_type=code&scope=snsapi_userinfo#wechat_redirect" class="binding">微信登录</a>
		<a href="mobile/signup" class="no">未注册，开始注册</a>
		<a href="mobile/login" class="yes">已注册，登录</a>
	</div>

	<script type="text/javascript" src="script/mobile/zepto.min.js"></script>
	
</body>
</html>