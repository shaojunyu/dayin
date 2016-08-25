<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>我的文库</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<meta name="format-detection" content="telephone=no">
	<meta name="renderer" content="webkit">
	<meta http-equiv="Cache-Control" content="no-siteapp">
	<link rel="stylesheet" type="text/css" href="../css/mobile/library.css">
</head>
<body>
	<header>
		<input type="tel" class="search-input" placeholder="输入文库号添加到文库">
		<a href="javascript:;" class="search-btn">搜索</a>
	</header>

	<div class="library-box clearfix">  <!-- 文库1级页面 -->
		<?php
		foreach ($myLib as $lib){
			echo '<a href="javascript:;"><div class="library-wrap">';
			echo '<img src="../images/mobile/book.png">';
			echo '<p>'.$lib['name'].'<br><span>'.$lib['Id'].'</span></p>';
			echo '</div></a>';
		}

		foreach ($applyingLib as $lib){
			echo '<div class="library-wrap apply-status">  <!-- 申请中 -->';
			echo '<img src="../images/mobile/apply.png">';
			echo '<p>'.$lib['name'].'<br><span>'.$lib['Id'].'</span></p>';
			echo '<span class="apply">申请中</span>';
			echo '</div>';
		}
		?>
	</div>

	<div class="cover"></div>
	<div class="application">  <!-- 申请加入弹出框 -->
		<a href="javascript:;" class="cancel"></a>
		<div class="title">
			<img src="../images/mobile/book.png">
			<p></p>
		</div>
		<div class="all-info">
			<div class="clearfix">
				<p>文库号：<span class="library-id"></span></p>
			</div>
		</div>
		<a href="javascript:;" class="apply-add">申请加入该文库</a>
	</div>

	<div class="library-info-box clearfix">  <!-- 文库详情 -->
		
	</div>

	<div class="hack"></div>

	<!-- 文件加载中 -->
	<div class="loading">
		<p>文库加载中...</p>
	</div>

	<a href="javascript:;" class="return"></a>  <!-- 返回上一级 -->
	<a href="javascript:;" class="addto-car">加入打印车 (0)</a>
	
	<div class="menu clearfix"> <!-- 底部菜单 -->
		<a href="javascript:;"><div class="library">文库</div></a>
		<a href="confirm"><div class="print-car"></div></a>
		<a href="myself"><div class="person">我的</div></a>
	</div>

	<!-- 加入购物车后的弹出框 -->
	<div class="added clearfix">
		<p>已加入购物车</p>
		<div><a href="javascript:;" class="continue">继续添加文件</a></div>
		<div><a href="confirm">去购物车</a></div>
	</div>

	<script type="text/javascript" src="../script/mobile/zepto.min.js"></script>
	<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
	<script type="text/javascript" src="../script/mobile/library.js"></script>
</body>
</html>