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

$(document).ready(function() {
	//导航栏个人中心二级菜单显示和隐藏
	$("#sign-out").mouseover(function() {
		$(".so").css("display", "block");
	});
	$("#sign-out").mouseout(function() {
		$(".so").css("display", "none");
	});

	//上传文件和我的文库切换
	$(".upload").click(function() {
		$(this).attr("id", "clicked");
		$(".my-store").attr("id", "");
		$(".upload-box").css("display", "block");
		$(".mystore").css("display", "none");
	});

	$(".my-store").click(function() {
		$(this).attr("id", "clicked");
		$(".upload").attr("id", "");
		$(".upload-box").css("display", "none");
		$(".mystore").css("display", "block");
	});

	//文库编号和文件夹的点击切换
	$(".every-store").click(function() {
		$(".every-store").css({"color":"#336598", "background-color":"#fff"});
		$(this).css({"color":"#fff", "background-color":"#0099ff"});
	});
	
	$(".every-folder").click(function() {
		$(".every-folder").css({"background-color":"#fff", "color":"#336598"});
		$(this).css({"background-color":"#acd6fe", "color":"#fff"});
	});

	//删除打印车文件
	var del = document.querySelectorAll(".scroll-bar i");
	for(var i = 0; i < del.length; i++) {
		addHandler(del[i], "click", function() {
			var md5 = $(this).attr("data-md5");
			var data = {fileMD5: md5};
			$.ajax({
				url: "./api/deleteItem",
	        	contentType: "application/json",
	        	dataType: "json",
	        	type: "POST",
	        	data: JSON.stringify(data),
	        	success: function(data) {
	        		$(this).remove();
	        	},
	        	error: function(XMLHttpRequest, textStatus, errorThrown) {
	        		showError("删除失败");
	        	}
			});
		});
	}


	//设置div滚动条样式
	$(".scroll-bar").slimScroll({
	    height: '600px', //容器高度,默认250px
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

	
	$(".file-scroll").slimScroll({
	    height: '560px', //容器高度,默认250px
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