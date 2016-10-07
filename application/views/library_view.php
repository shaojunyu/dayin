<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>学习云</title>
    <link rel="stylesheet" href="css/upload.css">
</head>
<body>
<header class="clearfix">
    <a href="http://dayin.4nian.cc" class="logo">
        <img src="images/logo.png" alt="学习云">
        <p>学习云</p>
    </a>
    <nav>
        <ul>
            <li><a href="http://dayin.4nian.cc">首页</a></li>
            <li><a href="doc/base.html" target="_blank">简介</a></li>
            <li><a href="javascript:void(0)">我的文库</a></li>
            <li class="person-box">
                <ul id="sign-out" class="clearfix">
                    <li><a href="myself" class="person">个人中心</a></li>
                    <li><a href="javascript:void(0)" class="so">退出登录</a></li>
                </ul>
            </li>
            <?php
            $this->db->like('admin',$this->session->userdata('cellphone'));
            $res = $this->db->get('library')->result_array();
            if (count($res)>0){
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
<div id="apply"> <!-- 申请加入 -->
    <div class="apply-top">
        申请加入<span>X</span>
    </div>
    <div class="apply-info">
        <p>文库名：<span class="lib-name">xxx</span></p>
        备注（有助于管理员识别）：<input type="text" class="remark" placeholder="不超过15个字（选填）">
    </div>
    <div class="apply-sub">
        <a class="apply-btn" href="javascript:;">发送请求</a>
    </div>
</div>
<div id="apply-create"> <!-- 申请自建文库 -->
    <div class="create-top">
        申请建立<span>X</span>
    </div>
    <div class="create-info">
        <p>申请文库名：<input type="text" class="library-name" placeholder="输入文库名"></p>
        <p>文库简介：<input type="text" class="introduction" placeholder="对文库的简介（选填）"></p>
        <p class="time">申请时间：</p>
    </div>
    <div class="create-sub">
        <a class="create-btn" href="javascript:;">确认建立</a>
    </div>
</div>
<div class="printcar-list"> <!-- 打印车列表 -->
    <div class="list-title">
        打印车列表<span>X</span>
    </div>
    <div class="file-list-scroll">
        <div class="file-wrap">

        </div>
    </div>
    <div class="printcar-sub">
        <a class="print-btn" href="javascript:;">查看打印车</a>
    </div>
</div>

<div class="nav">
    <div class="clearfix">
        <a href="upload" class="upload">上传文件</a>
        <a href="javascript:void(0)" class="my-store" id="clicked">我的文库</a>
    </div>
</div>

<a href="javascript:;" class="download-click" style="position: absolute; z-index: -1;"></a>
<!-- <iframe id="my_iframe" style="display:none;"></iframe> -->

<div class="myself-wrap clearfix">
    <div class="mystore clearfix">
        <p class="apply-box">
            <!-- <input type="text" class="library-name" placeholder="输入文库名(自建文库)"> -->
            <input type="button" class="create" value="申请建立文库">
        </p>
        <p class="search-box">
            <span>赶紧问学委班级文库编号,马上加入,课件再也不用愁啦!</span>
            <input type="text" class="search" placeholder="输入文库号查找文库">
            <input type="button" class="join" value="申请加入">
        </p>
        <div class="all-store">
            <div class="library-wrapper"></div>


            <?php
            //开放文库
            $this->db->where('isOpen','true');
            $this->db->not_like('admin',$this->session->userdata('cellphone'));
            $res = $this->db->get('library')->result_array();

            //var_dump($res);
            //加入的文库
            $this->db->where('cellphone',$this->session->userdata('cellphone'));
            $this->db->where('state','accepted');
            $r = $this->db->get('library_users')->result_array();
            foreach ($r as $lib){
                $id = $lib['libraryId'];
                $this->db->where('isOpen','false');
                $this->db->where('Id',$id);
                $mylib = $this->db->get('library')->result_array();
                if (count($mylib) == 1) {
                    //$res[] = $mylib[0];
                }
            }

            //自建的文库
            $this->db->like('admin',$this->session->userdata('cellphone'));
            $r = $this->db->get('library')->result_array();
            foreach ($r as $lib){
                $id = $lib['Id'];
                $this->db->where('Id',$id);
                $mylib = $this->db->get('library')->result_array();
                if (count($mylib) == 1) {
                    $res[] = $mylib[0];
                }
            }
            //var_dump($res);
            //$res = array_unique($res);
            foreach ($res as $lib){
            ?>
            <div class="library"> <!-- 每个文库用class为library的div包裹 -->
                <p class="every-store" data-libraryid="<?php echo $lib['Id']?>"><?php echo $lib['name'];?></p>
                <section data-libraryid="<?php echo $lib['Id']?>"> <!-- 如果文库为空也也要加上section，里面不放东西就行 -->
                    <div class="folder">
                        <?php
                        $this->db->where('libraryId', $lib['Id']);
                        $this->db->select('folder');
                        $this->db->distinct(true);
                        $folder_res = $this->db->get('library_files')->result_array();
                        foreach ($folder_res as $folder){
                            echo '<p class="every-folder">'.$folder['folder'].'</p>';
                        }
                        ?>
                    </div>
                    <div class="file-list">
                        <div class="file-scroll">
                            <?php
                            foreach ($folder_res as $folder){
                            ?>
                            <span data-filename="<?php echo $folder['folder'];?>"> <!-- 每个文件夹的文件用一个span包裹 -->
                                <?php
                                $this->db->where('libraryId', $lib['Id']);
                                $this->db->where('folder', $folder['folder']);
                                $this->db->where('fileName <>', null);
                                $file_res = $this->db->get('library_files')->result_array();
                                foreach ($file_res as $file){
                                if (strripos($file['fileName'],'.doc')){
                                    $class = 'word';
                                }elseif (strripos($file['fileName'],'.ppt')){
                                    $class = 'ppt';
                                }else{
                                    $class = 'pdf';
                                }
                                ?>
                                <div class="<?php echo $class;?>">
                                    <p data-fileMD5="<?php echo $file['fileMD5'];?>"><?php echo $file['fileName'];?></p>
                                    <a href="javascript:;" class="view">预览</a>
                                    <a href="javascript:;" class="download">下载</a>
                                    <i><input class="add-to-printcar" type="checkbox" value="1" /></i>
                                </div>
                                <?php }?>
                            </span>
                            <?php }//end foreach ($folder_res as $folder){?>
                        
                            <!-- <span data-filename="hhh.word" style="display: block;">
                                <div class="word">
                                    <p data-fileMD5="safa">hhh.word</p>
                                    <a href="javascript:;" class="view">预览</a>
                                    <i><input class="add-to-printcar" type="checkbox" value="1" /></i>
                                </div>
                            </span> -->
                        </div>
                    </div>
                </section>
            </div>
            <?php }?>

        </div>
    </div>
    <input type="button" class="add-car" value="添加到打印车">
    
</div>


<div class="file-show-box">
    <div class="control">
        <button class="prev">上一页</button>
        <button class="next">下一页</button>
        <input type="text" class="curPage"> / <span class="tolPage"></span>
        <button class="jumpTo">跳页</button>
        <span class="cancel">x</span>
    </div>
    <div class="scroll"><div class="pdfView"></div></div>
</div>

<footer class="clearfix">
        <div class="footbox">
            <p><a href="doc/base.html" target="_blank">关于文库</a></p>
            <p><a href="doc/base.html" target="_blank">文库简介</a></p>
            <p><a href="doc/base.html" target="_blank">使用说明</a></p>
            <p><a href="doc/base.html" target="_blank">鼓励分享</a></p>
        </div>
        <div class="footbox">
            <p><a href="javascript:;" target="_blank">订单服务</a></p>
            <p><a href="javascript:;" target="_blank">购买指南</a></p>
            <p><a href="javascript:;" target="_blank">支付方式</a></p>
            <p><a href="javascript:;" target="_blank">送货政策</a></p>
        </div>
        <div class="footbox">
            <p><a href="javascript:;" target="_blank">关于公司</a></p>
            <p><a href="javascript:;" target="_blank">公司简介</a></p>
            <p><a href="javascript:;" target="_blank">加入我们</a></p>
            <p><a href="javascript:;" target="_blank">联系我们</a></p>
        </div>
        <div class="footbox">
            <p><a href="javascript:;" target="_blank">关于我们</a></p>
            <p><a href="javascript:;" target="_blank">新浪微博</a></p>
            <p><a href="javascript:;" target="_blank">官方微博</a></p>
            <p><a href="javascript:;" target="_blank">官方贴吧</a></p>
        </div>
        <div class="ewm">
            <img src="images/ewm.jpg">
        </div>
        <p class="copyright">&copy;2016 四年生活版权所有 鄂ICP备15018392号</p>
    </footer>
<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="script/jquery.slimscroll.min.js"></script>
<script type="text/javascript" src="script/plupload.full.min.js"></script>
<script type="text/javascript" src="script/spark-md5.min.js"></script>
<script type="text/javascript" src="http://ob0826to9.bkt.clouddn.com/md5.js"></script>
<script type="text/javascript" src="script/pdf.js"></script>
<script type="text/javascript" src="script/mystore.js"></script>
</body>
</html>