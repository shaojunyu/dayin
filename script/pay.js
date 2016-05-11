//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	promptBox.style.top = "-80px";
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

$(document).ready(function() {
	setTimeout(function loop() {
		var order_id = document.querySelector(".order-num span").innerHTML;
		order_id = {orderId: order_id};
		$.ajax({
			url: secret("./api/isPaid"),
		    type: "POST",
		    contentType:"application/json",
		    dataType:"json",
		    data: JSON.stringify(order_id),
		    success:function(data) {
		        if(data.success) {
		            showError("支付成功，当前页面即将关闭");
		            setTimeout(function() {
			            window.close();
			            document.close();
			            showError("支付成功，请关闭当前页面");
			        }, 2000);
		        }else {
		            //
		        }
		    },
		    error: function(XMLHttpRequest, textStatus, errorThrown){  
		    	//
		    }
		});

		setTimeout(loop, 2000);
	}, 2000);
});