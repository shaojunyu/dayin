<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印</title>
    <link rel="stylesheet" href="css/upload.css">
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


<div class="nav">
    <div class="clearfix">
        <a href="javascript:void(0)" class="upload" id="clicked">上传文件</a>
        <a href="library" class="my-store">我的文库</a>
    </div>
</div>

<div class="myself-wrap clearfix">
    <div class="upload-box">
        <div class="scroll-bar clearfix">
    <?php
    $this->db->where('cellphone',$this->session->userdata('cellphone'));
    $res = $this->db->get('cart')->result_array();
    foreach ($res as $item){
        if (strripos($item['fileName'],'.doc')){
            $class = 'word';
        }elseif (strripos($item['fileName'],'.ppt')){
            $class = 'ppt';
        }else{
            $class = 'pdf';
        }
        //var_dump($item);
    ?>

            <div class="<?php echo $class;?>" data-status="<?php if ($item['pages'] > 0){}else{echo 'processing';}?>" data-md5="<?php echo $item['fileMD5'];?>">
                <p><?php echo $item['fileName'];?></p>
                <p>上传时间：<?php echo $item['createAt'];?></p>
                <i></i>
            </div>
<?php    }?>
            <p class="continue-add" id="file"><button type="button" id="ul">上传文件</button></p>
        </div>
    </div>
    
    <input type="button" class="to-order" value="去下单">
    
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
<script type="text/javascript" src="script/upload.js"></script>
<script type="text/javascript" src="script/myself.js"></script>
</body>
</html>