<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>九九打印</title>
	<link rel="stylesheet" href="css/manage.css">
</head>
<body>
	<header class="clearfix">
			<a href="http://www.99dayin.com" class="logo">
				<img src="images/logo.png" alt="九九打印">
				<p>九九打印</p>
			</a>
			<nav>
				<ul>
					<li><a href="http://www.99dayin.com">首页</a></li>
					<li><a href="doc/base.html" target="_blank">简介</a></li>
					<li><a href="library">我的文库</a></li>
					<li class="person-box">
						<ul id="sign-out" class="clearfix">
							<li><a href="myself" class="person">个人中心</a></li>
							<li><a href="javascript:void(0)" class="so">退出登录</a></li>
						</ul>
					</li>
					<?php
		            if ($this->session->userdata('role') == 'LIBADMIN'){
		                $this->db->like('admin',$this->session->userdata('cellphone'));
		                $res = $this->db->get('library')->result_array();
		                ?>
		                <li class="manage-wrapper">
		                    <a href="javascript:void(0)" class="manage-store">管理文库</a>
		                    <ul class="library-list">
		                        <?php
		                        foreach ($res as $lib){
		                            echo '<li><a href="./manage?libraryId='.$lib['Id'].'">'.$lib['name'].'</a></li>';
		                            //<li><a href="#">文库二</a></li>
		                        }
		                        ?>
		                    </ul>
		                </li>
		            <?php }?>
				</ul>
			</nav>
		</header>

		<div class="prompt-box"></div> <!-- 表单错误提示框 -->
		<div class="file-info"></div>
		<div class="cover"></div> <!-- 背景模糊遮罩 -->
		<div id="cancel-submit"> <!-- 文件删除 -->
		    <div class="cancel-top">
		        确认删除<span>X</span>
		    </div>
		    <div class="cancel-info">
		        确认删除此文件？
		    </div>
		    <div class="cancel-sub">
		        <a class="cancel-btn" href="javascript:void(0)">确认删除</a>
		    </div>
		</div>
		<div id="new-submit"> <!-- 新建文件夹 -->
		    <div class="new-top">
		        新建文件夹<span>X</span>
		    </div>
		    <div class="new-info">
		        新文件夹：<input type="text" class="new-folder-name" placeholder="输入文件夹名称">
		    </div>
		    <div class="new-sub">
		        <a class="new-btn" href="javascript:void(0)">确认</a>
		    </div>
		</div>
		<div id="del-submit"> <!-- 删除文件夹 -->
		    <div class="del-top">
		        确认删除<span>X</span>
		    </div>
		    <div class="del-info">
		        确认删除此文件夹？
		    </div>
		    <div class="del-sub">
		        <a class="del-btn" href="javascript:void(0)">确认删除</a>
		    </div>
		</div>

		<div class="manage-wrap clearfix">
			<div class="mymanage clearfix">
				<div class="lists">
					<p class="list">文库简介</p>
					<p class="list">文库成员</p>
					<?php 
					//获取文件夹
					$this->db->where('libraryId',$libInfo['Id']);
					$this->db->select('folder');
					$this->db->distinct(true);
					$folder_res = $this->db->get('library_files')->result_array();
					//var_dump($res);
					foreach ($folder_res as $folder){
					?>
					<p class="file-list"><span><?php echo $folder['folder'];?></span><i title="删除文件夹"></i></p>
					<?php }?>
					<a href="javascript:void(0)" class="new-folder">新建文件夹</a>
				</div>
				<div class="manage-list">
					<div class="manage-scroll">
						<p class="brief">文库编号：<span><?php echo $libInfo['Id'];?></span><br />
							文库名：<?php echo $libInfo['name'];?><br />
							管理员：<?php echo $libInfo['admin'];?><br />
							创建时间：<?php echo $libInfo['createAt'];?><br />
							文库类型：<?php echo $libInfo['isOpen']=='true' ? '公开文库' : '私密文库';?>
						</p>
						<div class="members">
						<?php 
						$this->db->where('libraryId',$libInfo['Id']);
						$this->db->order_by('Id','DESC');
						$res = $this->db->get('library_users')->result_array();
						foreach ($res as $user){
						?>
							<?php if ($user['state'] == 'accepted') {?>
							<div>
								<p><?php echo $user['cellphone'];?></p>
								<p>备注：<?php echo $user['remark'];?></p>
							</div>
							<?php }?>
							<?php if ($user['state'] == 'applying'){?>
							<div> <!-- 申请的用这个格式 -->
								<p><?php echo $user['cellphone'];?></p>
								<p>备注：<?php echo $user['remark'];?></p>
								<a href="javascript:void(0)" class="agree">同意</a>
								<a href="javascript:void(0)" class="refuse">拒绝</a>
							</div>
							<?php }?>
							<?php }//end foreach ($res as $user)?>
						</div>
						<div class="file-lists">
							<?php
							//var_dump($folder_res);
							foreach ($folder_res as $folder) {
							//}
							?>
							<section> <!-- 按文件夹顺序均用section包裹起来 -->
								<?php
								$folder_name = $folder['folder'];
								$this->db->where('libraryId', $libInfo['Id']);
								$this->db->where('folder', $folder['folder']);
								$this->db->where('fileName <>', null);
								$res = $this->db->get('library_files')->result_array();
								foreach ($res as $file){
									if (strripos($file['fileName'],'.doc')){
										$class = 'word';
									}elseif (strripos($file['fileName'],'.ppt')){
										$class = 'ppt';
									}else{
										$class = 'pdf';
									}
								?>
								<div class="<?php echo $class;?>" data-status="" data-md5="<?php echo $file['fileMD5'];?>">
									<p title="<?php echo $file['fileName'];?>"><?php echo $file['fileName'];?></p>
									<i></i>
								</div>
								<?php }//end foreach ($res as $file)?>
							</section>
							<?php } //end foreach ($folder_res as $folder)?>
							<p class="continue-add" id="file"><button type="button" id="ul">上传文件</button></p>
						</div>
					</div>
				</div>
			</div>

		</div>

		<footer class="clearfix">
			<div class="footbox">
				<p><a href="doc/base.html" target="_blank">关于文库</a></p>
				<p><a href="doc/base.html" target="_blank">文库简介</a></p>
				<p><a href="doc/base.html" target="_blank">使用说明</a></p>
				<p><a href="doc/base.html" target="_blank">鼓励分享</a></p>
			</div>
			<div class="footbox">
				<p><a href="doc/orderServe.html" target="_blank">订单服务</a></p>
				<p><a href="doc/orderServe.html" target="_blank">购买指南</a></p>
				<p><a href="doc/orderServe.html" target="_blank">支付方式</a></p>
				<p><a href="doc/orderServe.html" target="_blank">送货政策</a></p>
			</div>
			<div class="footbox">
				<p><a href="doc/company.html" target="_blank">关于公司</a></p>
				<p><a href="doc/company.html" target="_blank">公司简介</a></p>
				<p><a href="doc/company.html" target="_blank">加入我们</a></p>
				<p><a href="doc/company.html" target="_blank">联系我们</a></p>
			</div>
			<div class="footbox">
				<p><a href="doc/aboutUs.html" target="_blank">关于我们</a></p>
				<p><a href="doc/aboutUs.html" target="_blank">新浪微博</a></p>
				<p><a href="doc/aboutUs.html" target="_blank">官方微博</a></p>
				<p><a href="doc/aboutUs.html" target="_blank">官方贴吧</a></p>
			</div>
			<div class="ewm">
				<img src="images/ewm.png">
			</div>
			<p class="copyright">&copy;2016 九九打印版权所有 鄂ICP备15018392号</p>
		</footer>
		<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
		<script type="text/javascript" src="script/jquery.slimscroll.min.js"></script>
		<script type="text/javascript" src="script/plupload.full.min.js"></script>
		<script type="text/javascript" src="script/spark-md5.min.js"></script>
		<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
		<script type="text/javascript" src="script/manage.js"></script>
</body>
</html>