<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>我的订单</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" type="text/css" href="../css/mobile/person.css">
</head>
<body>
	<header class="clearfix">
		<div>
			<img src="../images/mobile/tx.png">
		</div>
		<div>
			<span>账号：18062421246</span><br>
			<span>自建文库：0个</span>
		</div>
	</header>

	<p class="order">订单</p>

	<div class="order-status clearfix">
		<div class="wait-pay click">待付款 (0)</div>
		<div class="processing">处理中 (10)</div>
		<div class="done">已完成 (100)</div>
	</div>

	<div class="wait-info order-box">   <!-- 待付款 -->
		<div class="order-info">
			<div class="file-info-box clearfix">
				<div class="file-info">
					<p>听力专项训练等7个文档</p>
					<p>2016年8月17日 14:56</p>
				</div>
				<div class="money">¥19.30</div>
			</div>
			<div class="other-info clearfix">
				<div>未付款</div>
				<div>XXXX打印店</div>
				<div><a href="javascript:;">去支付</a></div>
			</div>
		</div>
	</div>

	<div class="processing-info order-box">   <!-- 处理中 -->
		<div class="order-info">
			<div class="file-info-box clearfix">
				<div class="file-info">
					<p>听力专项训练等7个文档</p>
					<p>2016年8月17日 14:56</p>
				</div>
				<div class="money">¥19.30</div>
			</div>
			<div class="other-info clearfix">
				<div>已付款</div>
				<div>XXXX打印店</div>
				<div>等待处理</div>
			</div>
		</div>
	</div>

	<div class="done-info order-box">   <!-- 已完成 -->
		<div class="order-info">
			<div class="file-info-box clearfix">
				<div class="file-info">
					<p>听力专项训练等7个文档</p>
					<p>2016年8月17日 14:56</p>
				</div>
				<div class="money">¥19.30</div>
			</div>
			<div class="other-info clearfix">
				<div>已付款</div>
				<div>XXXX打印店</div>
				<div>已取货</div>
			</div>
		</div>
	</div>
	
	<div class="menu clearfix">
		<div class="library">文库</div>
		<div class="print-car"></div>
		<div class="person">我的</div>
	</div>
</body>
</html>