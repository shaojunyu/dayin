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

//添加事件监听
function addHandler(element, type, handler) {
	if(element.addEventListener) {
		element.addEventListener(type, handler, false);
	}
	else if(element.attachEvent) {
		element.attachEvent("on" + type, handler);
	}
	else {
		element["on" + type] = handler;
	}
}

//显示浮层
function showDiv(cover, sign) {
	var pageH = document.documentElement.clientHeight || document.body.clientHeight;
	var offsetH = (pageH - sign.offsetHeight) / 2;
	cover.style.display = "block";
	sign.style.display = "block";
}
//隐藏浮层
function hideDiv(cover, sign) {
	cover.style.display = "none";
	sign.style.display = "none";
}
//居中显示浮层
function centerDiv(sign) {
	var pageW = document.documentElement.clientWidth || document.body.clientWidth;
	var pageH = document.documentElement.clientHeight || document.body.clientHeight;
	sign.style.left = (pageW - sign.offsetWidth) / 2 + "px";
}


$(document).ready(function() {
	$("#sign-out").mouseover(function() {
		$(".so").css("display", "block");
	});
	$("#sign-out").mouseout(function() {
		$(".so").css("display", "none");
	});

	$(".untreated").click(function() {
		$(".untreated div").css("display", "block");
		$(".history div").css("display", "none");
		$(".nodo").css("display", "block");
		$(".done").css("display", "none");
	});

	$(".history").click(function() {
		$(".untreated div").css("display", "none");
		$(".history div").css("display", "block");
		$(".done").css("display", "block");
		$(".nodo").css("display", "none");
	});

	//编辑地址信息
	/*$(".edit-info").click(function() {
		$(".now-address").css("display", "none");
		$(".change-address").css("display", "inline-block");
		$(".edit-info").css("display", "none");
		$(".save-info").css("display", "block");
	});

	$(".save-info").click(function() {
		$(".change-address").css("display", "none");
		$(".now-address").css("display", "inline-block");
		$(".save-info").css("display", "none");
		$(".edit-info").css("display", "block");
	});*/

	//支付框
	var toPay = document.querySelectorAll(".toPay span");
	var cancel = document.querySelectorAll(".cancel span");
	var cover = document.querySelector(".cover");
	var pay = document.querySelector("#pay");
	var paying = document.querySelector("#paying");
	var payX = pay.querySelector(".pay-way span");
	var payingX = paying.querySelector(".inpay span");
	var wx = document.querySelector(".wx");
	var zfb = document.querySelector(".zfb");
	var payDone = document.querySelector(".pay-done");
	var payProblem = document.querySelector(".pay-problem");

	window.resize = function() {
		if(pay.style.display === "block") {
			centerDiv(pay);
		}
		else if(paying.style.display === "block") {
			centerDiv(paying);
		}
	};

	//支付框的显示
	var len = 0;
	if(toPay) {
		len = toPay.length;
	}
	for(var i = 0; i < len; i++) {
		addHandler(toPay[i], "click", function() { //给每一个去付款添加点击事件
			showDiv(cover, pay);
			var orderid = getOrderId(this);
			var wx_pay = {
				orderId: orderid,
				channel: "wx_pub_qr"
			};
			var zfb_pay = {
				orderId: orderid,
				channel: "alipay_pc_direct"
			};
			wx_pay = window.btoa(JSON.stringify(wx_pay));
			zfb_pay = window.btoa(JSON.stringify(zfb_pay));
			wx.setAttribute("href", "pay?pay="+wx_pay);
			zfb.setAttribute("href", "pay?pay="+zfb_pay);
		});
	}
	addHandler(payX, "click", function() { //支付框的隐藏
		hideDiv(cover, pay);
	});
	addHandler(wx, "click", function() { //微信支付弹出支付中
		hideDiv(cover, pay);
		showDiv(cover, paying);
		//跳转

	});
	addHandler(zfb, "click", function() { //支付宝支付弹出支付中
		hideDiv(cover, pay);
		showDiv(cover, paying);
		//跳转
		
	});

	//支付中框的隐藏, 并刷新页面
	addHandler(payingX, "click", function() {
		hideDiv(cover, paying);
		location.reload(true);
	});
	addHandler(payDone, "click", function() {
		hideDiv(cover, paying);
		location.reload(true);
	});
	addHandler(payProblem, "click", function() {
		hideDiv(cover, paying);
		location.reload(true);
	});

	//取消订单
	var cancel_len = 0;
	if(cancel) {
		cancel_len = cancel.length;
	}
	for(var i = 0; i < cancel_len; i++) {
		addHandler(cancel[i], "click", function() { //给每一个去付款添加点击事件
			var self = this;
			var parent = self.parentNode.parentNode.parentNode;
			var order_id = parent.querySelector(".order-num span").innerHTML;
			order_id = {orderId: order_id};
			$.ajax({
				url: secret("./api/cancelOrder"),
		        type: "POST",
		        contentType:"application/json",
		        dataType:"json",
		        data: JSON.stringify(order_id),
		        success:function(data) {
		            if(data.success) {
		            	document.querySelector(".order-list").removeChild(parent);
		                showError("取消成功");
		            }else {
		                showError(data.msg);
		            }
		        },
		        error: function(XMLHttpRequest, textStatus, errorThrown){  
		            showError("取消失败"); 
		        }
			});
		});
	}


	//设置div滚动条样式
	$(".order-list").slimScroll({
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


function getOrderId(orderId) {
	var parent = orderId.parentNode.parentNode.parentNode;
	var order_id = parent.querySelector(".order-num span").innerHTML;
	return order_id;
}