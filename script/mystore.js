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

//去空格及回车符
function delSpace(str) {
    str = str.replace(/[\r\n]/g,"");
    str = str.split(' ').join('');
    return str;
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

	//上传文件和我的文库切换
	$(".mystore").css("display", "block");
	$(".add-car").css("display", "block");

	//申请加入
	var coverBg = document.querySelector(".cover");
    var apply = document.querySelector("#apply");
    var applyX = document.querySelector(".apply-top span");
    var applyBtn = document.querySelector(".apply-btn");
    var remarkInfo = document.querySelector(".remark");
    var join = document.querySelector(".join");
    var search = document.querySelector(".search");
    addHandler(join, "click", function() {
    	showError("文库查询中，请稍候");
    	var libraryId = search.value;
    	libraryId = delSpace(libraryId);
    	var dat = {
    		libraryId: libraryId
    	};
    	$.ajax({
            url: secret("./api/searchLib"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(dat),
            success:function(data) {
            	document.querySelector(".lib-name").innerHTML = data.libName;
                showDiv(coverBg, apply);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                showError("新建文件夹失败，请重试");
            }
        });
    });
    addHandler(newX, "click", function() {
        hideDiv(coverBg, newSubmit);
    });
    addHandler(newBtn, "click", function() {
        var new_name = delSpace(newName.value);
        var temp = true;
        var data = {};
        var libraryId = document.querySelector(".brief span").innerHTML;
        for(var i = 0; i < nameLists.length; i++) {
            if(new_name == nameLists[i]) {
                showError("文件夹重名，请重新输入");
                newName.value = "";
                temp = false;
                break;
            }
        }
        if(new_name && temp) {
            data.libraryId = libraryId;
            data.folder = new_name;
            $.ajax({
                url: secret("./api/createFolder"),
                type: "POST",
                contentType:"application/json",
                dataType:"json",
                data: JSON.stringify(data),
                success:function(data) {
                    nameLists.push(new_name);
                    createFolder(new_name);
                    newName.value = "";
                    hideDiv(coverBg, newSubmit);
                },
                error: function(XMLHttpRequest, textStatus, errorThrown){  
                    showError("新建文件夹失败，请重试");
                }
            });
        }
        else if(!new_name) {
            showError("请输入文件夹名");
        }
    })




	//文库编号和文件夹的点击切换
	$(".every-store").click(function() {
		$(".every-store").css({"color":"#336598", "background-color":"#fff"});
		$(this).css({"color":"#fff", "background-color":"#0099ff"});
	});
	
	$(".every-folder").click(function() {
		$(".every-folder").css({"background-color":"#fff", "color":"#336598"});
		$(this).css({"background-color":"#acd6fe", "color":"#fff"});
	});


	//设置div滚动条样式
	$(".file-scroll").slimScroll({
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