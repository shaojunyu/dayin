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

	//退出登录
    $(".so").click(function() {
        $.ajax({
            url: "./api/logout",
            data:'{}',
            contentType: 'application/json',
            type: "POST",
            success:function(data) {
                location.reload(true);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                showError("请求失败");
            }
        });
    });

	//删除打印车文件
	var del = document.querySelectorAll(".scroll-bar i");
	for(var i = 0; i < del.length; i++) {
		addHandler(del[i], "click", function() {
			var md5 = $(this).attr("data-md5");
			var data = {fileMD5: md5};
			$.ajax({
				url: secret("./api/deleteItem"),
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

	//管理文库
	var manage_store = document.querySelector(".manage-store");
	var library_list = document.querySelector(".library-list");

	addHandler(manage_store, "click", function(e) {
		e = e || window.event;
		e.stopPropagation();
		e.cancelBubble = true;
		library_list.style.display = "block";
	});
	addHandler(document, "click", function() {
		library_list.style.display = "none";
	});


	//设置div滚动条样式
	$(".scroll-bar").slimScroll({
	    height: '420px', //容器高度,默认250px
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