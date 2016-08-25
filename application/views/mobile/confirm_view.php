<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>打印车</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" type="text/css" href="../css/mobile/confirm.css">
</head>
<body>
	<header class="clearfix">
		<a href="javascript:;" class="select-all">全选</a>
		<span>文档</span>
		<span>每页印刷</span>
		<span>排版设置</span>
		<span>单双打印</span>
		<span>纸张大小</span>
		<span>份数</span>
	</header>

	<div class="main">
		<div class="content">
			<?php
			$this->load->model('Cart_model','Cart');
			$this->db->where('cellphone',$this->session->userdata('cellphone'));
			$res = $this->db->get('cart')->result_array();
			if (count($res) == 0){
				$this->load->helper('url');
				header('Location: '.base_url('/upload'));
				return;
			}
			$i = 1;
			foreach ($res as $item) {
				if (strripos($item['fileName'], '.doc')) {
					$class = 'word';
				} elseif (strripos($item['fileName'], '.ppt')) {
					$class = 'ppt';
				} else {
					$class = 'pdf';
				}

				//page信息是否已存在
				if ($item['pages'] != 0) {
					$pages = $item['pages'];
				} else {
					$this->db->where('fileMD5', $item['fileMD5']);
					$r = $this->db->get('file_info')->result_array();

					if (count($r) == 0) {//没有该文件的信息,删除购物车条目
						$this->Cart->delete_item($item['fileMD5']);
						continue;
					} else {//pages信息写入cart表
						$r = $r[0];
						$pages = $r['pages'];
						//如果pages 0 文件解析出错
						if ($pages == 0) {
							$this->Cart->delete_item($item['fileMD5']);
							continue;
						} else {
							$this->db->where('fileMD5', $item['fileMD5']);
							$this->db->update('cart', array('pages' => $pages));
						}
					}
				}
				if(!$item['subTotal']){
					//$this->load->model('Cart_model','Cart');
					$item['subTotal'] = $this->Cart->calculate_price($item['fileMD5'])['subTotal'];
				}
				//var_dump($item);

			?>
			<div class="file-set-box clearfix">
				<div class="check-box">
					<a href="javascript:;" class="check"></a>
				</div>
				<div class="file-info">
					<p class="file-name"><?php echo $item['fileName'];?></p>
					<span>共 <?php echo $item['pages'];?> 页</span>
				</div>
				<div class="choose-box">
					<a href="javascript:;" class="choose-default per-page"><?php echo $item['pptPerPage'];?></a>
					<div class="jt"><img src="../images/mobile/jiantou.png"></div>
					<div class="select-box select-page">
						<a href="javascript:;">1</a>
						<a href="javascript:;">2</a>
						<a href="javascript:;">4</a>
						<a href="javascript:;">6</a>
					</div>
				</div>
				<div class="choose-box">
					<a href="javascript:;" class="choose-default direction"><?php echo ($item['direction'] == 'vertical')?'竖':'横';?></a>
					<div class="jt"><img src="../images/mobile/jiantou.png"></div>
					<div class="select-box">
						<a href="javascript:;">竖</a>
						<a href="javascript:;">横</a>
					</div>
				</div>
				<div class="choose-box">
					<a href="javascript:;" class="choose-default pos-and-neg"><?php echo ($item['isTwoSides'] == 'YES')?'双面':'单面';?></a>
					<div class="jt"><img src="../images/mobile/jiantou.png"></div>
					<div class="select-box">
						<a href="javascript:;">单面</a>
						<a href="javascript:;">双面</a>
					</div>
				</div>
				<div class="choose-box">
					<a href="javascript:;" class="choose-default page-size"><?php echo  $item['paperSize'];?></a>
					<div class="jt"><img src="../images/mobile/jiantou.png"></div>
					<div class="select-box">
						<a href="javascript:;">A4</a>
						<a href="javascript:;">B5</a>
					</div>
				</div>
				<div class="copies-box">
					<input type="tel" class="copies" value="<?php echo $item['amount'];?>">
				</div>
				<div class="price">小计：¥<?php echo $item['subTotal'];?></div>
			</div>
			<?php } //end foreach?>

		</div>
	</div>

	<form class="form-box clearfix">
		<div class="select clearfix">
			<a href="javascript:;" class="default">选择一个打印店</a>
			<div class="option-box">
				<a href="javascript:;" class="option">A打印店</a>
				<a href="javascript:;" class="option">B打印店</a>
				<a href="javascript:;" class="option">C打印店</a>
				<a href="javascript:;" class="option">D打印店</a>
				<a href="javascript:;" class="option">E打印店</a>
			</div>
			<div class="icon">
				<img src="../images/mobile/jiantou.png">
			</div>
		</div>
		<a href="javascript:;" class="to-pay">去下单 ¥10.55</a>
	</form>

	<div class="menu clearfix"> <!-- 底部菜单 -->
		<a href="library"><div class="library">文库</div></a>
		<a href="javascript:;"><div class="print-car"></div></a>
		<a href="myself"><div class="person">我的</div></a>
	</div>

	<script type="text/javascript" src="../script/mobile/zepto.min.js"></script>
	<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
	<script type="text/javascript" src="../script/mobile/touch.js"></script>
	<script type="text/javascript" src="../script/mobile/confirm.js"></script>
</body>
</html>