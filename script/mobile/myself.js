//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

//消息显示框显示
function showMsg(msg) {
	$(".prompt-box").html(msg);
	$(".prompt-box").show();
	setTimeout(function () {
		hideMsg();
	}, 3000);
}

//消息显示框隐藏
function hideMsg() {
	$(".prompt-box").html("");
	$(".prompt-box").hide();
}

//生成二维码
function createEwm(data) {
	$(".ewm-pic").attr("src", "http://qr.liantu.com/api.php?text="+data.msg.wx_pub_qr);
	$(".we-cancel").show();
	$(".cover").show();
	$(".ewm").show();
	$(".pay-box").hide();
}

$(function () {
	$(".order-status a").click(function () {
		var prev = $(".click").attr("data-index");
		$(".click").removeClass("click");
		$(this).find("div").addClass("click");
		var now = $(".click").attr("data-index");
		if(now == prev) {
			return;
		}

		if(prev == "1") {
			$(".wait-info").hide();
		}
		else if(prev == "2") {
			$(".processing-info").hide();
		}
		else {
			$(".done-info").hide();
		}

		if(now == "1") {
			$(".wait-info").show();
		}
		else if(now == "2") {
			$(".processing-info").show();
		}
		else {
			$(".done-info").show();
		}
	});

	//退出登录
	$(".sign-out").click(function() {
		$.ajax({
			url: "../api/logout",
			data:'{}',
			contentType: 'application/json',
			type: "POST",
			success:function(data) {
	    		if(data.msg) {
	    			showMsg(data.msg);
	    		}
	    		else {
	    			window.location.href = "../mobile";
	    		}
	       	},
	    	error: function(XMLHttpRequest, textStatus, errorThrown){  
	    		showMsg("请求失败，请刷新重试");
	   		}
		});
	});

	//取消订单
	var cancelData;
	var parent;
	$(".cancel").click(function () {
		parent = $(this)[0].parentNode.parentNode.parentNode;
		var orderId = parent.getAttribute("data-orderId");
		cancelData = {
			orderId: orderId
		};
		$(".cover").show();
		$(".cancel-box").show();
	});
	$(".return").click(function () {
		$(".cover").hide();
		$(".cancel-box").hide();
	});
	$(".cancel-btn").click(function () {
		$.ajax({
			url: secret("../api/cancelOrder"),
		    type: "POST",
	        contentType:"application/json",
	        dataType: "json",
	        data: JSON.stringify(cancelData),
	        success: function(data) {
	            if(data.success) {
	            	parent.parentNode.removeChild(parent);
	            }
	            $(".cover").hide();
				$(".cancel-box").hide();
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){  
	            showMsg("请求失败"); 
	            $(".cover").hide();
				$(".cancel-box").hide();
	        }
		});
	});

	//去支付
	var orderId = "";
	$(".pay").click(function () {
		$(".pay-box").show();
		$(".cover").show();
		orderId = $(this).parent().parent().parent().attr("data-orderid");
	});

	//取消支付
	$(".hide-pay-box").click(function () {
		$(".pay-box").hide();
		$(".cover").hide();
		orderId = "";
	});


	//微信支付
	$(".wexin").click(function () {
		showMsg("请稍候");
		var data = {
			orderId: orderId
		};
		$.ajax({
			url: secret("../api/wxPayQr"),
		    type: "POST",
	        contentType:"application/json",
	        dataType: "json",
	        data: JSON.stringify(data),
	        success: function(data) {
	            if(data.success) {
	            	createEwm(data);
	            }
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){  
	            showMsg("请求失败");
	        }
		});
	});

	//取消支付
	$(".we-cancel").click(function () {
		$(".cover").hide();
		$(".ewm").hide();
		$(this).hide();
		window.location.href = "../mobile/myself";
	});
});
