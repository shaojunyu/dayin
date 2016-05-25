<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="head scroll">
    <header class="clearfix">
        <a href="http://www.99dayin.com" class="logo">
            <img src="images/logo.png" alt="九九打印">
            <p>九九打印</p>
        </a>
        <nav>
            <ul>
                <li><a href="http://www.99dayin.com">首页</a></li>
                <li><a href="doc/base.html" target="_blank">简介</a></li>
                <?php
                if (!$this->session->userdata('cellphone')){
                    ?>
                <li>
                    <a href="javascript:void(0)" class="sign_in">登录</a>
                    <div class="center"></div>
                    <a href="javascript:void(0)" class="sign_up">注册</a>
                </li>
                <?php } else{?>
                <li><a href="library">我的文库</a></li>
                <li class="person-box">
                    <ul id="sign-out" class="clearfix">
                        <li><a href="myself" class="person">个人中心</a></li>
                        <li><a href="javascript:void(0)" class="so">退出登录</a></li>
                    </ul>
                </li>
                <?php }?>
            </ul>
        </nav>
    </header>

    <div class="prompt-box"></div> <!-- 表单错误提示框 -->

    <div class="cover"></div> <!-- 背景模糊遮罩 -->

    <div class="sign-in">  <!-- 登录框 -->
        <p>登录<i></i></p>
        <div class="user-pic"><img src="images/yh.png"></div>
        <form class="in" id="login">
            <input type="text" autocomplete="off" class="user" name="phonein" placeholder="手机号">
            <input type="password" class="pw" name="passwordin" placeholder="密码">
            <div class="useidc clearfix"><span>使用手机验证码登录</span></div>
            <input type="submit" class="submit" value="确 定">
        </form>
    </div>

    <div class="identify-code">  <!-- 验证码登录框 -->
        <p>验证码登录<i></i></p>
        <div class="user-pic"><img src="images/yh.png"></div>
        <form class="idcode-in" id="identify-form">
            手机号：<input type="text" class="idc-user" name="phoneid"><br />
            验证码：<input type="text" class="id-code" name="idc">
            <input type="button" value="获取验证码" class="get"><br />
            <input type="submit" class="idc-submit" value="确 定">
        </form>
    </div>

    <div class="sign-up">  <!-- 注册框 -->
        <p>注册<i></i></p>
        <form class="up" id="signup">
            <label for="phone">手机号码：</label>
            <input type="text" autocomplete="off" id="phone" name="phoneup"><br />
            <label for="pw-f">密码：</label>
            <input type="password" id="pw-f" name="passwordup"><br />
            <label for="pw-r">确认密码：</label>
            <input type="password" id="pw-r" name="passwordcheck"><br />
            <label for="identify">验证码：</label>
            <input type="text" id="identify" name="identifyup">
            <input type="button" class="send" value="发送验证码"><br />
            <div class="school-box">
                <label>学校：</label>
                <select class="school">
                    <option value="华中科技大学">华中科技大学</option>
                    <option value="武汉大学">武汉大学</option>
                    <option value="华中师范大学">华中师范大学</option>
                    <option value="中南财经政法大学">中南财经政法大学</option>
                    <option value="武汉理工大学">武汉理工大学</option>
                    <option value="中国地质大学">中国地质大学</option>
                    <option value="华中农业大学">华中农业大学</option>
                </select>
            </div>
            <input type="submit" class="submit-up" value="确 认">
        </form>
    </div>

    <div class="print">
        <img src="images/desc.png">
        <a href="javascript:void(0)" id="print-btn">Print</a>
    </div>

    <img src="images/newhand.gif" class="down">
</div>

<div class="bottom scroll">
    <div class="newhand">
        <img src="images/step.png" alt="">
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
</div>
<script type="text/javascript" src="script/jquery-1.12.0.min.js"></script>
<script type="text/javascript" src="script/jquery.validate.min.js"></script>
<script type="text/javascript" src="http://7xnadt.com1.z0.glb.clouddn.com/md5.js"></script>
<script type="text/javascript" src="script/home.js"></script>
</body>
</html>