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

<div class="container">
    <div class="internal-box">
        <p class="title">打<br />印<br />列<br />表</p>
        <div class="top">
            <div>
                <span class="row-1">序号</span>
                <span class="row-2">文件名</span>
                <span class="row-3">页数</span>
                <span class="row-4">单/双面</span>
                <span class="row-5">横/竖</span>
                <span class="row-6">每面ppt数量</span>
                <span class="row-7">大小</span>
                <span class="row-8">单价</span>
                <span class="row-9">份数</span>
                <span class="row-10">总价</span>
                <span class="row-11">备注</span>
                <span class="row-12">操作</span>
            </div>
        </div>

        <div class="scroll-box">
            <div data-md5="1">
                <span class="row-1">1</span>
                <span class="row-2 word" title="dansjudnasnxdasubcasbc.doc">dansjudnasnxdasubcasbc.doc</span>
                <span class="row-3">4</span>
				<span class="row-4">
					<select class="face" class="row-3">
                        <option value="单面">单面</option>
                        <option value="双面">双面</option>
                    </select>
				</span>
				<span class="row-5">
					<select class="direction">
                        <option value="横">横</option>
                        <option value="竖">竖</option>
                    </select>
				</span>
				<span class="row-6">
					<select class="page-num">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
				</span>
				<span class="row-7">
					<select class="size">
                        <option value="A4">A4</option>
                        <option value="B4">B4</option>
                    </select>
				</span>
                <span class="row-8">0.1</span>
                <span class="row-9"><input type="text" class="amout" placeholder="1"></span>
                <span class="row-10">0.1</span>
                <span class="row-11"><input type="text" class="remark"></span>
                <span class="row-12">删除</span>
            </div>

            <div data-md5="2">
                <span class="row-1">2</span>
                <span class="row-2 word" title="dansjudnasnxdasubcasbc.doc">dansjudnasnxdasubcasbc.doc</span>
                <span class="row-3">4</span>
                <span class="row-4">
                    <select class="face" class="row-3">
                        <option value="单面">单面</option>
                        <option value="双面">双面</option>
                    </select>
                </span>
                <span class="row-5">
                    <select class="direction">
                        <option value="横">横</option>
                        <option value="竖">竖</option>
                    </select>
                </span>
                <span class="row-6">
                    <select class="page-num">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </span>
                <span class="row-7">
                    <select class="size">
                        <option value="A4">A4</option>
                        <option value="B4">B4</option>
                    </select>
                </span>
                <span class="row-8">0.1</span>
                <span class="row-9"><input type="text" class="amout" placeholder="1"></span>
                <span class="row-10">0.1</span>
                <span class="row-11"><input type="text" class="remark"></span>
                <span class="row-12">删除</span>
            </div>

            <div data-md5="3">
                <span class="row-1">3</span>
                <span class="row-2 word" title="dansjudnasnxdasubcasbc.doc">dansjudnasnxdasubcasbc.doc</span>
                <span class="row-3">4</span>
                <span class="row-4">
                    <select class="face" class="row-3">
                        <option value="单面">单面</option>
                        <option value="双面">双面</option>
                    </select>
                </span>
                <span class="row-5">
                    <select class="direction">
                        <option value="横">横</option>
                        <option value="竖">竖</option>
                    </select>
                </span>
                <span class="row-6">
                    <select class="page-num">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </span>
                <span class="row-7">
                    <select class="size">
                        <option value="A4">A4</option>
                        <option value="B4">B4</option>
                    </select>
                </span>
                <span class="row-8">0.1</span>
                <span class="row-9"><input type="text" class="amout" placeholder="1"></span>
                <span class="row-10">0.1</span>
                <span class="row-11"><input type="text" class="remark"></span>
                <span class="row-12">删除</span>
            </div>

            <div data-md5="4">
                <span class="row-1">4</span>
                <span class="row-2 word" title="dansjudnasnxdasubcasbc.doc">dansjudnasnxdasubcasbc.doc</span>
                <span class="row-3">4</span>
                <span class="row-4">
                    <select class="face" class="row-3">
                        <option value="单面">单面</option>
                        <option value="双面">双面</option>
                    </select>
                </span>
                <span class="row-5">
                    <select class="direction">
                        <option value="横">横</option>
                        <option value="竖">竖</option>
                    </select>
                </span>
                <span class="row-6">
                    <select class="page-num">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                    </select>
                </span>
                <span class="row-7">
                    <select class="size">
                        <option value="A4">A4</option>
                        <option value="B4">B4</option>
                    </select>
                </span>
                <span class="row-8">0.1</span>
                <span class="row-9"><input type="text" class="amout" placeholder="1"></span>
                <span class="row-10">0.1</span>
                <span class="row-11"><input type="text" class="remark"></span>
                <span class="row-12">删除</span>
            </div>
            <div class="add"><a href="upload">继续添加文件</a></div>
        </div>

        <p class="total">总价：<span class="money">120</span>元</p>

        <div class="topay">
            <span>收货方式：</span>
					<span class="delivery">
                        <input type="checkbox" class="todoor" checked="checked" name="deliv" value="todoor">送货上门
						<input type="checkbox" class="pick" name="deliv" value="pickup">到店自取
					</span>
            <select name="store" id="print-store">
                <option value="0">请选择打印店</option>
            </select>

            <div class="door">
                <select name="area" id="school-area">
                    <option value="0">请选择校区</option>
                    <option value="韵苑">韵苑</option>
                    <option value="沁苑">沁苑</option>
                    <option value="紫松">紫松</option>
                </select>
                <select name="ban" id="Ban">
                    <option value="0">楼栋</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                    <option value="17">17</option>
                    <option value="18">18</option>
                    <option value="19">19</option>
                    <option value="20">20</option>
                    <option value="21">21</option>
                    <option value="22">22</option>
                    <option value="23">23</option>
                    <option value="24">24</option>
                    <option value="25">25</option>
                    <option value="26">26</option>
                    <option value="27">27</option>
                    <option value="28">28</option>
                </select>
                <input type="text" placeholder="宿舍号" class="room">
                <select name="t" id="time">
                    <option value="0">送货时间</option>
                    <option value="1">8:00-11:00</option>
                    <option value="2">14:00-17:00</option>
                    <option value="3">18:00-21:30</option>
                </select>
                <input type="text" class="receiver" placeholder="收货人">
                <input type="text" class="phone" placeholder="联系电话">
            </div>

            <input type="button" class="pay" value="生成订单">
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
<script type="text/javascript" src="script/printList.js"></script>
</body>
</html>