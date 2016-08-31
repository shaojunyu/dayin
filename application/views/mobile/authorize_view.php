<!DOCTYPE html>
<html>
<head lang="en">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>绑定账号</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" href="https://cdn.bootcss.com/weui/0.4.2/style/weui.css">
	<link rel="stylesheet" type="text/css" href="../css/mobile/reset.css">
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
		
		/* form */
		form {
			margin: 0 auto;
			width: 85%;
			padding-top: 57px;
		}
		.log-box {
			display: none;
		}
		.weui_cell {
			padding: 7px;
			-webkit-box-sizing: border-box;
			   -moz-box-sizing: border-box;
			        box-sizing: border-box;
		}
		.weui_cells {
			margin-top: 7px;
		}
		.weui_cell input {
			font-size: 1.1rem;
			color: #56a0ad;
		}
		.code-box {
			height: auto;
		}
		.code-box .weui_cells, .code-box .get {
			float: left;
		}
		.code-box .weui_cells {
			width: 60%;
			font-size: 1.1rem;
		}
		.code-box .weui_cell input {
			height: 1.5rem;
			line-height: 1.5rem;
		}
		.code-box .get {
			width: 40%;
			margin-top: 7px;
			padding: 7px 0 8px 0;
			font-size: .9rem;
			background-color: #3991a0;
			border-radius: 0;
			height: 1.5rem;
			line-height: 1.5rem;
			text-align: center;
			color: #fff;
		}
		.sbm {
			background-color: #3991a0;
			letter-spacing: 12px;
			font-size: 1.3rem;
			margin-top: 25px;
		}
	</style>
</head>
<body>
	<div class="header">
		<div>
			<i><img src="../images/mobile/logo.png"></i>
		</div>
		<p>四年打印</p>
	</div>

	<form class="log-box">
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
		<a href="javascript:;" class="weui_btn weui_btn_primary sbm">绑定</a>
	</form>

	<script type="text/javascript" src="../script/mobile/zepto.min.js"></script>
	<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
	<script type="text/javascript" src="../script/mobile/authorize.js"></script>
</body>
</html>