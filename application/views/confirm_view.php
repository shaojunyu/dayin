<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>九九打印-打印设定</title>
    <link rel="stylesheet" href="css/printList.css">
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
            <li><a href="upload">我的文库</a></li>
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
                <span class="row-10">小计</span>
                <span class="row-11">备注</span>
                <span class="row-12">操作</span>
            </div>
        </div>

        <div class="scroll-box">
<?php

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
        if ($item['pages'] != 0){
            $pages = $item['pages'];
        }else{
            $this->db->where('fileMD5',$item['fileMD5']);
            $r = $this->db->get('file_info')->result_array();

            if (count($r) == 0){//没有该文件的信息,删除购物车条目
                
                $this->Cart->delete_item($item['fileMD5']);
                continue;
            }else{//pages信息写入cart表
                $r = $r[0];
                $pages = $r['pages'];
                //如果pages 0 文件解析出错
                if ($pages == 0){
                    $this->Cart->delete_item($item['fileMD5']);
                    continue;
                }else{
                    $this->db->where('fileMD5',$item['fileMD5']);
                    $this->db->update('cart',array('pages'=>$pages));
                }

            }

        }

        ?>
        <div data-md5="<?php echo $item['fileMD5']; ?>">
            <span class="row-1"><?php echo $i; ?></span>
            <span class="row-2 <?php echo $class; ?>"
                  title="<?php echo $item['fileName']; ?>"><?php echo $item['fileName']; ?></span>
            <span class="row-3"><?php echo $pages;  ?></span>
				<span class="row-4">
					<select class="face" class="row-3">
                        <option value="单面" <?php if ($item['isTwoSides'] == "NO"){ echo 'SELECTED';}?> >单面
                        </option>
                        <option value="双面" <?php if ($item['isTwoSides'] == "YES"){ echo 'SELECTED';}?> >双面
                        </option>
                    </select>
				</span>
				<span class="row-5">
					<select class="direction">
                        <option value="horizontal" <?php if ($item['direction'] == 'horizontal') {
                            echo 'SELECTED';
                        } ?> >横
                        </option>
                        <option value="vertical" <?php if ($item['direction'] == 'vertical') {
                            echo 'SELECTED';
                        } ?> >竖
                        </option>
                    </select>
				</span>
				<span class="row-6">
                    <?php if ($class == 'ppt' or $class == 'pdf'){ ?>
					<select class="page-num">
                        <option value="1" <?php if ($item['pptPerPage']== 1 ){ echo 'SELECTED'; }?> >1</option>
                        <option value="4" <?php if ($item['pptPerPage']== 4 ){ echo 'SELECTED'; }?> >4</option>
                        <option value="6" <?php if ($item['pptPerPage']== 6 ){ echo 'SELECTED'; }?> >6</option>
                        <option value="9" <?php if ($item['pptPerPage']== 9 ){ echo 'SELECTED'; }?> >9</option>
                    </select>
                    <?php }else{ echo '\\';}?>
				</span>
				<span class="row-7">
					<select class="size">
                        <option value="A4" <?php if ($item['paperSize']== 'A4' ){ echo 'SELECTED'; }?> >A4</option>
                        <option value="B4" <?php if ($item['paperSize']== 'B4' ){ echo 'SELECTED'; }?> >B4</option>
                    </select>
				</span>
            <span class="row-8">
                <?php
                if ($item['price'] == null){//单价
                    $price_info = $this->Cart->calculate_price($item['fileMD5']);
                    echo $price_info['price'];
                }else{
                    echo $item['price']; //单价
                }
                ?></span>
            <span class="row-9"><input type="text" class="amout" placeholder="" value="<?php echo $item['amount']; ?>"></span>
            <span class="row-10"><?php
                if ($item['subTotal'] == null){
                    echo $price_info['subTotal'];
                }else{
                    echo $item['subTotal'];
                }
                ?></span>
            <span class="row-11"><input type="text" class="remark"></span>
            <span class="row-12">删除</span>
        </div>
        <?php
        $i++;
        }
            //end foreach
            ?>
            <div class="add"><a href="upload">继续添加文件</a></div>
        </div>

        <p class="total">总价：<span class="money">120</span>元</p>

        <div class="topay">
            <span>收货方式：</span>
					<span class="delivery">
                        <input type="checkbox" class="todoor" checked="checked" name="deliv" value="todoor">免费送货上门
						
					</span>
            <select name="store" id="print-store">
                <option value="0">请选择打印店</option>
                <option value="东篱阳光图文">东篱阳光图文</option>
                <option value="韵苑二栋打印社">韵苑二栋打印社</option>
                <option value="紫菘打印店">紫菘打印店</option>
                <option value="沁苑打印店">沁苑打印店</option>
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
                <!-- <select name="t" id="time">
                    <option value="0">送货时间</option>
                    <option value="1">8:00-11:00</option>
                    <option value="2">14:00-17:00</option>
                    <option value="3">18:00-21:30</option>
                </select> -->
                <input type="text" class="receiver" placeholder="收货人">
                <input type="text" class="phone" placeholder="联系电话">
            </div>

            <input type="button" class="pay" value="提交订单">
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
<script type="text/javascript" src="http://7xnadt.com1.z0.glb.clouddn.com/md5.js"></script>
<script type="text/javascript" src="script/printList.js"></script>
</body>
</html>