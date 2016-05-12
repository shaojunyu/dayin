//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	setTimeout(function() {
		promptBox.style.top = "-80px";
	}, 2000);
}

//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

//移除div
function removeC(elem) {
    var parent = document.querySelector(".scroll-box");
    parent.removeChild(elem);
}

//删除后重新排序
function reSort() {
	var nowDiv = document.querySelectorAll(".scroll-box div");
	var len = nowDiv.length - 1;
	for(var i = 0; i < len; i++) {
		nowDiv[i].querySelector(".row-1").innerHTML = i + 1;
	}
}

//计算总价
function shipment() {
	var everyTotal = document.querySelectorAll(".scroll-box .row-10");
	var all = 0;
	var temp = 0;
	for(var i = 0; i < everyTotal.length; i++) {
		temp = parseFloat(everyTotal[i].innerHTML);
		all += temp;
	}
	all = all.toFixed(3);
	$(".money").text(all);
}

//改变参数时发送Ajax请求
function sendMsg(data, str, parent) {
	$.ajax({
		url:secret("./api/printSettings"),
	    contentType:"application/json",
	    dataType:"json",
	    type:"POST",
	    data:JSON.stringify(data),
	    success:function(data) {
	    	if(data.success) {
	    		if(data.price) {
	    			parent.find(".row-8").text(data.price);
	    		}
	    		if(data.subTotal) {
	    			parent.find(".row-10").text(data.subTotal);
	    		}
	    		shipment();
	    	}
	   		else {
	        	showError(data.msg);
	        }
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showError("修改失败，请刷新重设" + str);
		}
	});
}

var paperSizeArr = ["A4", "B4"];
var sidesArr = ["单面", "双面"];
var perMoney = [0.1, 0.15, 0.4];

$(document).ready(function() {
	$("#sign-out").mouseover(function() {
		$(".so").css("display", "block");
	});
	$("#sign-out").mouseout(function() {
		$(".so").css("display", "none");
	});

	//到店自取显示和隐藏
	$(".pick").click(function() {
		if( $(".pick").prop("checked") == true ) {
			$(".todoor").prop("checked", false);
			$("#print-store").css("display", "inline-block");
			$(".door").css("display", "none");
		}
		else {
			$("#print-store").css("display", "none");
		}
	});
	
	//送货上门显示和隐藏
	$(".todoor").click(function() {
		if( $(".todoor").prop("checked") == true ) {
			$(".pick").prop("checked", false);
			$(".door").css("display", "inline-block");
			$("#print-store").css("display", "none");
		}
		else {
			$(".door").css("display", "none");
		}
	});

	shipment();

	//监听改变订单信息事件
	//打印面数
	$(".face").change(function() {
		var isTwoSides = $(this).find("option:selected").text();
		isTwoSides = isTwoSides.replace(/[\r\n]/g,"");
		isTwoSides = isTwoSides.split(' ').join('');
		var istwosides = isTwoSides;
		if(isTwoSides == "单面") {
			isTwoSides = "NO";
		}
		else {
			isTwoSides = "YES";
		}
		var parent = $(this).parent().parent();
		var filePages = parseInt(parent.find(".row-3").text());
		var md5 = parent.attr("data-md5");
		var data = {
			fileMD5: md5,
			isTwoSides: isTwoSides
		};
		var size = parent.find(".size").find("option:selected").text(); //获取大小
		size = size.replace(/[\r\n]/g,"");
		size = size.split(' ').join('');
		var amout = parent.find(".amout").val(); //获取打印份数
		//countMoney(amout, size, istwosides, filePages, parent);
		sendMsg(data, "单双面", parent);
	});

	//打印大小
	$(".size").change(function() {
		var itSize = $(this).find("option:selected").text();
		itSize = itSize.replace(/[\r\n]/g,"");
		itSize = itSize.split(' ').join('');
		var parent = $(this).parent().parent();
		var filePages = parseInt(parent.find(".row-3").text());
		var md5 = parent.attr("data-md5");
		var data = {
			fileMD5: md5,
			paperSize: itSize
		};
		var sides = parent.find(".face").find("option:selected").text(); //获取单双面
		sides = sides.replace(/[\r\n]/g,"");
		sides = sides.split(' ').join('');
		var amout = parent.find(".amout").val(); //获取打印份数
		//countMoney(amout, itSize, sides, filePages, parent);
		sendMsg(data, "大小", parent);
	});

	//打印份数
	$(".amout").change(function() {
		var parent = $(this).parent().parent();
		var filePages = parseInt(parent.find(".row-3").text());
		var md5 = parent.attr("data-md5");
		var size = parent.find(".size").find("option:selected").text();
		size = size.replace(/[\r\n]/g,"");
		size = size.split(' ').join('');
		var isTwoSide = parent.find(".face").find("option:selected").text();
		isTwoSide = isTwoSide.replace(/[\r\n]/g,"");
		isTwoSide = isTwoSide.split(' ').join('');
		var amout = $(this).val();
		var data = {};
		amout = parseInt(amout);
		if(!amout) {
			showError("请输入正确的数字");
			$(this).val('');
		}
		else {
			//countMoney(amout, size, isTwoSide, filePages, parent);
			data = {
				fileMD5: md5,
				amount: amout
			};
			sendMsg(data, "份数", parent);
		}
	});

	//打印方向
	$(".direction").change(function() {
		var parent = $(this).parent().parent();
		var md5 = parent.attr("data-md5");
		var direction = $(this).find("option:selected").text();
		direction = direction.replace(/[\r\n]/g,"");
		direction = direction.split(' ').join('');
		if(direction == "横") {
			direction = "horizontal";
		}
		else {
			direction = "vertical";
		}
		var data = {
			fileMD5: md5,
			direction: direction
		};
		sendMsg(data, "方向", parent);
	});

	//每页ppt数量
	$(".page-num").change(function() {
		var parent = $(this).parent().parent();
		var md5 = parent.attr("data-md5");
		var pptPerPage = $(this).find("option:selected").text();
		pptPerPage = parseInt(pptPerPage);
		var data = {
			fileMD5: md5,
			pptPerPage: pptPerPage
		};
		sendMsg(data, "每面ppt数", parent);
	});

	//备注
	$(".remark").change(function() {
		var parent = $(this).parent().parent();
		var md5 = parent.attr("data-md5");
		var remark = $(this).val();
		if(remark.length > 100) {
			$(this).val("");
			showError("请不要输入超过100个字符");
			return;
		}
		var data = {
			fileMD5: md5,
			remark: remark
		};
		sendMsg(data, "备注", parent);
	});

	//送货时间
	$("#time").change(function() {
		var value = $(this).val();
		var status = true;
		if(value == 1) {

		}
		else if(value == 2) {

		}
		else if(value == 3) {

		}

		if(!status) {
			showError("您的文件将于明天送达");
		}
	});

	//生成订单
	$(".pay").click(function() {
		if($(".pick").prop("checked") == false && $(".todoor").prop("checked") == false ) {
			showError("请选择收货方式");
			return;
		}
		//列表中是否有文件
		/*var fileNum = document.querySelectorAll(".scroll-box div");
		if(fileNum.length <= 1) {
			showError("请选择文件");
			return;
		}*/
		
		showError("提交中，请稍候...");
		if($(".pick").prop("checked") == true) { //到店自取
			test("get");
		}
		else if($(".todoor").prop("checked") == true) { //送货上门
			test("send");
		}
	});

	//删除订单
	$(".scroll-box .row-12").click(function() {
		var div = this.parentNode;
		var md5 = $(this).parent().attr("data-md5");
		var data = {
			fileMD5: md5
		};
		delMsg(data);
	});

	//设置div滚动条样式
	$(".scroll-box").slimScroll({
	    height: '470px', //容器高度,默认250px
	    size: '7px', //滚动条宽度,默认7px
	    color: '#ffcc00', //滚动条颜色,默认#000000
	    alwaysVisible: true, //是否禁用隐藏滚动条,默认false
	    distance: '10px', //距离边框距离,位置由position参数决定,默认1px
	    railVisible: true, //滚动条背景轨迹,默认false
	    railColor: '#222', //滚动条背景轨迹颜色,默认#333333
	    railOpacity: 0.3, //滚动条背景轨迹透明度,默认0.2
	    wheelStep: 20, //滚动条滚动值,默认20
	    allowPageScroll: false, //滚动条滚动到顶部或底部时是否允许页面滚动,默认false
	    disableFadeOut: false //是否禁用鼠标在内容处一定时间不动隐藏滚动条,当设置alwaysVisible为true时该参数无效,默认false
	});
});

//删除
function delMsg(Data) {
	$.ajax({
		url:secret("./api/deleteItem"),
	    contentType:"application/json",
	    dataType:"json",
	    type:"POST",
	    data:JSON.stringify(Data),
	    success:function(data) {
	    	if(data.success) {
				var md5 = Data.fileMD5;
				var allDiv = document.querySelectorAll(".scroll-box div");
				var data_md5;
				for(var i = 0; i < allDiv.length; i++) {
					(function(m){
						var i = m;
						if(md5 == allDiv[i].getAttribute("data-md5")) {
							removeC(allDiv[i]);
							reSort();
						}
					})(i);
				}
	    	}
	   		else {
	        	showError(data.msg);
	        }
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showError("删除失败");
		}
	});
}

//提交订单
function submitOrder(Data) {
	$.ajax({
		url:secret("./api/createOrder"),
	    contentType:"application/json",
	    dataType:"json",
	    type:"POST",
	    data:JSON.stringify(Data),
	    success:function(data) {
	    	if(data.success) {
				location.href = "myself";
	    	}
	   		else {
	        	showError(data.msg);
	        }
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showError("提交失败，请重试");
		}
	});
}

//验证信息是否正确
function test(way) {
	var data = {};
	if(way == "get") { //到店自取
		var print_store = $("#print-store").find("option:selected").text();
		print_store = delSpace(print_store);
		if(print_store == "请选择打印店") {
			showError("请选择打印店");
			return ;
		}
		else {
			data.shop = print_store;
			data.deliveryMode = "self";
		}
	}
	else { //送货上门
		data.deliveryMode = "delivery";
		//校区
		var area = $("#school-area").find("option:selected").text();
		area = delSpace(area);
		if(area == "请选择校区") {
			showError("请选择校区");
			return;
		}
		else {
			data.area = area;
		}
		//楼栋号
		var buildingNum = $("#Ban").find("option:selected").text();
		buildingNum = delSpace(buildingNum);
		if(buildingNum == "楼栋") {
			showError("请选择楼栋");
			return;
		}
		else {
			data.buildingNum = buildingNum;
		}
		//宿舍号
		var roomNum = $(".room").val();
		if(!roomNum) {
			showError("请输入宿舍号");
			return;
		}
		else if(roomNum.length >= 10) {
			$(".room").val("");
			showError("请输入合理的宿舍号");
			return;
		}
		else {
			data.roomNum = roomNum;
		}
		//收货人
		var receiver = $(".receiver").val();
		if(!receiver) {
			showError("请输入收货人姓名");
			return;
		}
		else if(receiver.length >= 20) {
			$(".receiver").val("");
			showError("请输入正确的姓名");
			return;
		}
		else {
			data.receiver = receiver;
		}
		//收货人手机
		var receiverPhone = $(".phone").val();
		var tel = /^1[3|4|5|7|8]\d{9}$/;
		if(tel.test(receiverPhone)) {
			data.receiverPhone = receiverPhone;
		}
		else {
			showError("请输入正确的手机号");
			$(".phone").val("");
			return;
		}
		//送货时间
		var time = $("#time").find("option:selected").text();
		time = delSpace(time);
		if(time == "送货时间") {
			showError("请选择送货时间");
			return;
		}
		else {
			data.deliveryTime = time;
		}
	}

	data.total = $(".money").text();
	submitOrder(data);
}

//去空格及回车符
function delSpace(str) {
	str = str.replace(/[\r\n]/g,"");
	str = str.split(' ').join('');
	return str;
}