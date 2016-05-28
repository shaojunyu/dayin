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
            <li><a href="javascript:void(0)">我的文库</a></li>
            <li class="person-box">
                <ul id="sign-out" class="clearfix">
                    <li><a href="myself" class="person">个人中心</a></li>
                    <li><a href="javascript:void(0)" class="so">退出登录</a></li>
                </ul>
            </li>
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
        <a class="apply-btn" href="javascript:void(0)">发送请求</a>
    </div>
</div>

<div class="nav">
    <div class="clearfix">
        <a href="upload" class="upload">上传文件</a>
        <a href="javascript:void(0)" class="my-store" id="clicked">我的文库</a>
    </div>
    <a href="manage" class="manage-store">管理文库</a>
</div>

<div class="myself-wrap clearfix">
    <div class="mystore clearfix">
        <p class="search-box">
            <input type="text" class="search" placeholder="输入文库号查找文库">
            <input type="button" class="join" value="申请加入">
        </p>
        <div class="all-store">
            <div class="library"> <!-- 每个文库用class为library的div包裹 -->
                <p class="every-store" data-libraryid="1000">文库1</p>
                <section data-libraryid="1000"> <!-- 如果文库为空也也要加上section，里面不放东西就行 -->
                    <div class="folder">
                        <p class="every-folder">文件夹1</p>
                        <p class="every-folder">文件夹2</p>
                        <p class="every-folder">文件夹3</p>
                        <p class="every-folder">文件夹4</p>
                    </div>
                    <div class="file-list">
                        <div class="file-scroll">
                            <span data-filename="文件夹1"> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="word">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>

                            <span data-filename="文件夹2"> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="ppt">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>

                            <span data-filename="文件夹3"> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="pdf">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>

                            <span data-filename="文件夹4"> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="word">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                                <div class="word">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="library"> <!-- 每个文库用class为library的div包裹 -->
                <p class="every-store" data-libraryid="1001">文库2</p>
                <section data-libraryid="1001">
                    <div class="folder">
                        <p class="every-folder">文件夹2</p>
                    </div>
                    <div class="file-list">
                        <div class="file-scroll">
                            <span data-filename=""> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="word">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="library"> <!-- 每个文库用class为library的div包裹 -->
                <p class="every-store" data-libraryid="1002">文库3</p>
                <section data-libraryid="1002">
                    <div class="folder">
                        <p class="every-folder">文件夹3</p>
                    </div>
                    <div class="file-list">
                        <div class="file-scroll">
                            <span data-filename=""> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="word">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>
                        </div>
                    </div>
                </section>
            </div>

            <div class="library"> <!-- 每个文库用class为library的div包裹 -->
                <p class="every-store" data-libraryid="1003">文库4</p>
                <section data-libraryid="1003">
                    <div class="folder">
                        <p class="every-folder">文件夹4</p>
                    </div>
                    <div class="file-list">
                        <div class="file-scroll">
                            <span data-filename=""> <!-- 每个文件夹的文件用一个span包裹 -->
                                <div class="word">
                                    <p></p>
                                    <p>最后修改时间：<?php ?></p>
                                    <i><input type="checkbox" value="1" /></i>
                                </div>
                            </span>
                        </div>
                    </div>
                </section>
            </div>

            
        </div>
        


    </div>
    <input type="button" class="add-car" value="加入打印车">
    
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
<script type="text/javascript" src="http://7xnadt.com1.z0.glb.clouddn.com/spark-md5.min.js"></script>
<script type="text/javascript" src="http://7xnadt.com1.z0.glb.clouddn.com/md5.js"></script>
<script type="text/javascript" src="script/mystore.js"></script>
</body>
</html>