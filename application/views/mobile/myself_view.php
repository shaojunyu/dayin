<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>个人中心</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" type="text/css" href="../css/mobile/myself.css">
</head>
<body>
	<header class="clearfix">
		<div>
			<img src="../images/mobile/tx.png">
		</div>
		<div>
			<span>账号：<?php echo $this->session->userdata('cellphone');?></span><br>
			<span></span>
		</div>
	</header>

	<p class="order">订单</p>

	<div class="order-status clearfix">
		<a href="javascript:;"><div class="wait-pay click" data-index="1">待付款</div></a>
		<a href="javascript:;"><div class="processing" data-index="2">处理中</div></a>
		<a href="javascript:;"><div class="done" data-index="3">已完成</div></a>
	</div>

	<div class="wait-info order-box">   <!-- 待付款 -->
		<?php
		$this->db->where('cellphone',$this->session->userdata('cellphone'));
		$this->db->where('state','UNPAID');
		$res = $this->db->get('order')->result_array();
		foreach ($res as $order){
			//var_dump($order);
		?>
		<div class="order-info" data-orderId="<?php echo $order['Id'];?>">
			<div class="file-info-box clearfix">
				<div class="file-info">
					<p><?php
						$content = json_decode($order['content']);
						echo $content[0]->fileName;
						echo count($content) > 1 ? ' 等'.count($content).'个文件':'';
						?></p>
					<p><?php echo $order['createAt'];?></p>
				</div>
				<div class="money">¥<?php echo $order['total'];?></div>
			</div>
			<div class="other-info waitting-to-pay clearfix">
				<div>未付款</div>
				<div><?php echo $order['shop'];?></div>
				<div><a href="javascript:;" class="cancel">取消订单</a></div>
				<div><a href="javascript:;" class="pay">去支付</a></div>
			</div>
		</div>
		<?php }?>
	</div>

	<div class="processing-info order-box">   <!-- 处理中 -->
		<?php
		$this->db->where('cellphone',$this->session->userdata('cellphone'));
		$this->db->where('state','PRINTED');
		//$this->db->or_where('state','UNPRINTED');
		$res = $this->db->get('order')->result_array();
		foreach ($res as $order){
		?>
		<div class="order-info">
			<div class="file-info-box clearfix">
				<div class="file-info">
					<p><?php
						$content = json_decode($order['content']);
						echo $content[0]->fileName;
						echo count($content) > 1 ? ' 等'.count($content).'个文件':'';
						?></p>
					<p><?php echo $order['createAt'];?></p>
				</div>
				<div class="money">¥<?php echo $order['total'];?></div>
			</div>
			<div class="other-info clearfix">
				<div>已付款</div>
				<div><?php echo $order['shop'];?></div>
				<div>等待处理</div>
			</div>
		</div>
		<?php }?>
	</div>

	<div class="done-info order-box">   <!-- 已完成 -->
		<?php
		$this->db->where('cellphone',$this->session->userdata('cellphone'));
		$this->db->where('state','DONE');
		$res = $this->db->get('order')->result_array();
		foreach ($res as $order){
			?>
			<div class="order-info">
				<div class="file-info-box clearfix">
					<div class="file-info">
						<p><?php
							$content = json_decode($order['content']);
							echo $content[0]->fileName;
							echo count($content) > 1 ? ' 等'.count($content).'个文件':'';
							?></p>
						<p><?php echo $order['createAt'];?></p>
					</div>
					<div class="money">¥<?php echo $order['total'];?></div>
				</div>
				<div class="other-info clearfix">
					<div>已付款</div>
					<div><?php echo $order['shop'];?></div>
					<div>已完成</div>
				</div>
			</div>
		<?php }?>
	</div>
	
	<div class="menu clearfix">
		<a href="library"><div class="library">文库</div></a>
		<a href="confirm"><div class="print-car"></div></a>
		<a href="javascript:;"><div class="person">我的</div></a>
	</div>

	<script type="text/javascript" src="../script/mobile/zepto.min.js"></script>
	<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
	<script type="text/javascript" src="../script/mobile/myself.js"></script>
</body>
</html>