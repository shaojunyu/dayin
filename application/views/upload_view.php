<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印</title>
    <link rel="stylesheet" href="css/printList.css">
</head>
<body>
<header class="clearfix">
    <a href="index.html" class="logo">
        <img src="images/logo.png" alt="九九打印">
        <p>九九打印</p>
    </a>
    <nav>
        <ul>
            <li><a href="index.html">首页</a></li>
            <li><a href="base.html" target="_blank">简介</a></li>
            <li><a href="javascript:void(0)">我的文库</a></li>
            <li class="person-box">
                <ul id="sign-out" class="clearfix">
                    <li><a href="javascript:void(0)" class="person">个人中心</a></li>
                    <li><a href="javascript:void(0)" class="so">退出登录</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</header>

<div class="prompt-box"></div> <!-- 表单错误提示框 -->

<div class="nav"><div class="clearfix"><span class="upload" id="clicked">上传文件</span><span class="my-store">我的文库</span></div></div>

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
            $class = 'ppt';
        }
        //var_dump($item);
    ?>

            <div class="<?php echo $class;?>" data-md5="<?php echo $item['fileMD5'];?>">
                <p><?php echo $item['fileName'];?></p>
                <p>上传时间：<?php echo $item['createAt'];?></p>
                <i></i>
            </div>
<?php    }?>
            <p class="continue-add" id="file"><span id="ul">上传文件</span></p>
        </div>
    </div>
    <div class="mystore clearfix">
        <p class="search-box">
            <input type="text" class="search" placeholder="输入文库号查找文库">
            <input type="button" class="join" value="申请加入">
        </p>
        <div class="all-store">
            <p class="every-store">文库编号1</p>
            <p class="every-store">文库编号2</p>
            <p class="every-store">文库编号3</p>
        </div>
        <div class="folder">
            <p class="every-folder">文件夹1</p>
            <p class="every-folder">文件夹2</p>
            <p class="every-folder">文件夹3</p>
            <p class="every-folder">文件夹4</p>
        </div>
        <div class="file-list">
            <div class="file-scroll">
                <div class="word">
                    <p>abdsbsa.doc</p>
                    <p>最后修改时间：2016/4/26 23:59 大小：300kb</p>
                    <i><input type="checkbox" value="1" /></i>
                </div>
                <div class="ppt">
                    <p>abdsbsa.ppt</p>
                    <p>最后修改时间：2016/4/26 23:59 大小：300kb</p>
                    <i><input type="checkbox" value="2" /></i>
                </div>
            </div>
        </div>
    </div>

    <div class="print-car-box">
        <div class="print-car">
            <div id="triangle" class="hide"></div>
            <p class="car">打印车</p>
            <p class="see">（点击查看已选文件）</p>
        </div>
        <input type="button" class="to-order" value="去下单">
        <div class="car-scroll">
            <div class="word">
                <p>abdsbsa.doc</p>
                <p>最后修改时间：2016/4/26 23:59 大小：300kb</p>
                <i></i>
            </div>
            <div class="ppt">
                <p>abdsbsa.doc</p>
                <p>最后修改时间：2016/4/26 23:59 大小：300kb</p>
                <i></i>
            </div>
        </div>
    </div>
</div>

<footer class="clearfix">
    <div class="footbox">
        <p><a href="base.html" target="_blank">关于文库</a></p>
        <p><a href="base.html" target="_blank">文库简介</a></p>
        <p><a href="base.html" target="_blank">使用说明</a></p>
        <p><a href="base.html" target="_blank">鼓励分享</a></p>
    </div>
    <div class="footbox">
        <p><a href="orderServe.html" target="_blank">订单服务</a></p>
        <p><a href="orderServe.html" target="_blank">购买指南</a></p>
        <p><a href="orderServe.html" target="_blank">支付方式</a></p>
        <p><a href="orderServe.html" target="_blank">送货政策</a></p>
    </div>
    <div class="footbox">
        <p><a href="company.html" target="_blank">关于公司</a></p>
        <p><a href="company.html" target="_blank">公司简介</a></p>
        <p><a href="company.html" target="_blank">加入我们</a></p>
        <p><a href="company.html" target="_blank">联系我们</a></p>
    </div>
    <div class="footbox">
        <p><a href="aboutUs.html" target="_blank">关于我们</a></p>
        <p><a href="aboutUs.html" target="_blank">新浪微博</a></p>
        <p><a href="aboutUs.html" target="_blank">官方微博</a></p>
        <p><a href="aboutUs.html" target="_blank">官方贴吧</a></p>
    </div>
    <div class="ewm">
        <img src="images/ewm.png">
    </div>
    <p class="copyright">&copy;2016 九九打印版权所有 鄂ICP备15018392号</p>
</footer>
<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="script/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="script/plupload.full.min.js"></script>
<script type="text/javascript" src="http://7xnadt.com1.z0.glb.clouddn.com/spark-md5.min.js"></script>
<script type="text/javascript" src="script/upload.js"></script>
<script type="text/javascript" src="script/myself.js"></script>
</body>
</html>