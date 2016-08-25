<!DOCTYPE html>
<html>
<head lang="en">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>注册</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" href="https://cdn.bootcss.com/weui/0.4.2/style/weui.css">
	<link rel="stylesheet" type="text/css" href="../css/mobile/signup.css">
</head>
<body>
	<div class="header">
		<div>
			<i><img src="../images/mobile/logo.png"></i>
		</div>
		<p>四年打印</p>
	</div>

	<form class="log-box">
		<div class="select-school">
			<a href="javascript:;" class="choose-default">选择您的学校</a>
			<div class="jt"><img src="../images/mobile/jiantou.png"></div>
			<div class="select-box">
				<a href="javascript:;">华中科技大学</a>
				<a href="javascript:;">武汉大学</a>
			</div>
		</div>
		<div class="weui_cells weui_cells_form">
		    <div class="weui_cell phone-box">
		        <div class="weui_cell_bd weui_cell_primary">
		            <input class="weui_input phone" type="tel" placeholder="输入您的手机号">
		        </div>
		    </div>
		</div>
		<div class="code-box clearfix">
			<div class="weui_cells weui_cells_form">
			    <div class="weui_cell smscode-box">
			        <div class="weui_cell_bd weui_cell_primary">
			            <input class="weui_input smscode" type="text" placeholder="手机验证码">
			        </div>
			    </div>
			</div>
			<a href="javascript:;" class="get">获取短信验证码</a>
		</div>
		<div class="weui_cells weui_cells_form">
		    <div class="weui_cell password-box">
		        <div class="weui_cell_bd weui_cell_primary">
		            <input class="weui_input password" type="password" placeholder="请设置您的密码(至少8位)">
		        </div>
		    </div>
		</div>
		<a href="javascript:;" class="weui_btn weui_btn_primary sbm">确认注册</a>
	</form>

	<script type="text/javascript" src="../script/mobile/zepto.min.js"></script>
	<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
	<script type="text/javascript" src="../script/mobile/signup.js"></script>

</body>
</html>