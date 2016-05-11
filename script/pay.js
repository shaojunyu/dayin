//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	promptBox.style.top = "-80px";
}

$(document).ready(function() {
	setTimeout(function loop() {
		var order_id = document.querySelector(".trade-r").innerHTML;
		order_id = {orderId: order_id};
		$.ajax({
			url: "./api/isPaid",
		    type: "POST",
		    contentType:"application/json",
		    dataType:"json",
		    data: JSON.stringify(order_id),
		    success:function(data) {
		        if(data.success) {
		            showError("支付成功，请关闭当前页面");
		            window.close();
		            document.close();
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