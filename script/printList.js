//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	setTimeout(function() {
		promptBox.style.top = "-80px";
	}, 2000);
}

$(document).ready(function() {
	$("#sign-out").mouseover(function() {
		$(".so").css("display", "block");
	});
	$("#sign-out").mouseout(function() {
		$(".so").css("display", "none");
	});

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