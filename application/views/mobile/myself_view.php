<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>我的订单</title>
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
		<div class="wait-pay click">待付款</div>
		<div class="processing">处理中</div>
		<div class="done">已完成</div>
	</div>

	<div class="wait-info order-box">   <!-- 待付款 -->
		<?php
		$this->db->where('cellphone',$this->session->userdata('cellphone'));
		$this->db->where('state','UNPAID');
		$res = $this->db->get('order')->result_array();
		foreach ($res as $order){
			//var_dump($order);
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
				<div>未付款</div>
				<div><?php echo $order['shop'];?></div>
				<div><a href="javascript:;">去支付</a></div>
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
		<div class="library">文库</div>
		<div class="print-car"></div>
		<div class="person">我的</div>
	</div>
</body>
</html>