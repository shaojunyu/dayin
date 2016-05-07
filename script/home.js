//封装了页面滚动的函数及变量
var scrollAnimate = (function() {
	//滚动速度, 越大速度越慢
	var scroll_speed = 5;
	//页数
	var pageNum = 2;
	//视口高度
	var bodyH = document.documentElement.clientHeight || document.body.clientHeight;

	//页面滚动动画, flag为正为上滚, 为负则下滚
	function scrollMove(element, flag) {
		var dist = 0;
		if(scrollAnimate.timer) { //判断是否还在滚动中
			return;
		}
		if(flag < 0) {
			if(scrollAnimate.count % pageNum == 0) { //判断是否是顶部
				return;
			}
		}
		else {
			if(scrollAnimate.count % pageNum == pageNum - 1) { //判断是否是底部
				return;
			}
		}

		scrollAnimate.timer = 1;

		time = setInterval(function() {
			var front;
			var behind;
			
			if(flag > 0) {
				front = parseInt(element[scrollAnimate.count%pageNum].style.top);
				behind = parseInt(element[(scrollAnimate.count+1)%pageNum].style.top);
				if((scrollAnimate.bodyH - behind) > 0 && (scrollAnimate.bodyH - behind) < 1){
					dist = scrollAnimate.bodyH - behind;
				}
				else{
					dist = Math.ceil(behind / scroll_speed);
				}

				if(behind > 0) {
					element[scrollAnimate.count%pageNum].style.top = front - dist + "px";
					element[(scrollAnimate.count+1)%pageNum].style.top = behind - dist + "px";
				}
				else {
					scrollAnimate.count++;
					scrollAnimate.timer = 0;
					scrollAnimate.resetLocat(element);
					clearInterval(time);
				}
			}
			else {
				behind = parseInt(element[scrollAnimate.count%pageNum].style.top);
				front = parseInt(element[(scrollAnimate.count-1)%pageNum].style.top);
				if((scrollAnimate.bodyH + front) > 0 && (scrollAnimate.bodyH + front) < 1){
					dist = front - scrollAnimate.bodyH;
				}
				else{
					dist = Math.floor(front / scroll_speed);
				}

				if(front < 0) {
					element[(scrollAnimate.count-1)%pageNum].style.top = front - dist + "px";
					element[scrollAnimate.count%pageNum].style.top = behind - dist + "px";
				}
				else {
					scrollAnimate.count--;
					scrollAnimate.timer = 0;
					scrollAnimate.resetLocat(element);
					clearInterval(time);
				}
			}
		}, 15);
	}

	function resetLocat(element) { //重置各页的位置
		var now = scrollAnimate.count % pageNum;
		for(var i = 0, j = -now; i <= now; i++, j++) {
			element[i].style.top = j * scrollAnimate.bodyH + "px";
		}
		for(var k = now + 1, l = 1; k < pageNum; k++, l++) {
			element[k].style.top = l * scrollAnimate.bodyH + "px";
		}
	}

	return {
		count: 1000, //页面的页数的倍数
		timer: 0,
		pageNum: pageNum,
		bodyH: bodyH,
		scrollMove: scrollMove,
		resetLocat: resetLocat
	};
})();

//为onload事件添加多个回调函数
function addLoadEvent(func){
	var oldonload = window.onload;
	if(typeof window.onload != "function"){
		window.onload = func;
	}
	else{
		window.onload = function(){
			oldonload();
			func();
		};
	}
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
	if((scrollAnimate.count % 2) == 1) {
		sign.style.top = pageH + offsetH + "px";
	}
	else {
		sign.style.top = offsetH + "px";
	}
	cover.style.display = "block";
	sign.style.display = "block";
	scrollAnimate.timer = 1;
}
//隐藏浮层
function hideDiv(cover, sign) {
	cover.style.display = "none";
	sign.style.display = "none";
	scrollAnimate.timer = 0;
}
//居中显示浮层
function centerDiv(sign) {
	var pageW = document.documentElement.clientWidth || document.body.clientWidth;
	var pageH = document.documentElement.clientHeight || document.body.clientHeight;
	if((scrollAnimate.count % 2) == 0) {
		sign.style.top = (pageH - sign.offsetHeight) / 2 + "px";
	}
	else {
		sign.style.top = pageH + (pageH - sign.offsetHeight) / 2 + "px";
	}
	sign.style.left = (pageW - sign.offsetWidth) / 2 + "px";
}

//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	setTimeout(function() {
		promptBox.style.top = "-80px";
	}, 2000);
}

//检测IE版本
function isIE(ver){
    var b = document.createElement("b");
    b.innerHTML = "<!--[if IE " + ver + "]><i></i><![endif]-->";
    return b.getElementsByTagName("i").length === 1;
}

//判断是否是IE8
if(isIE(8)) {
	alert("您的浏览器版本过低，请在高版本浏览器中浏览此页面！");
} 
else if(isIE(7)) {
	alert("您的浏览器版本过低，请在高版本浏览器中浏览此页面！");
}

addLoadEvent(function(){
	var scroll = document.querySelectorAll(".scroll"); //要滚动的页面
	var down = document.querySelector(".down"); //点击后向下滚动的图片
	var signIn = document.querySelector(".sign_in"); //点击的登录按钮
	var signUp = document.querySelector(".sign_up"); //点击的注册按钮
	var cover = document.querySelector(".cover"); //登录模糊背景
	var signInBox = document.querySelector(".sign-in"); //登录框
	var signUpBox = document.querySelector(".sign-up"); //注册框
	var xbtnIn = document.querySelector("div.sign-in>p>i"); //登录框上的叉叉
	var xbtnUp = document.querySelector("div.sign-up>p>i"); //注册框上的叉叉
	var useidc = document.querySelector(".useidc"); //点击出现验证码登录框
	var idBox = document.querySelector(".identify-code"); //验证码登录框
	var xidC = document.querySelector("div.identify-code>p>i"); //叉叉
	var signOut = document.querySelector("#sign-out");
	var sO = document.querySelector(".so");

	window.resize = function() {
		if(signInBox.style.display === "block") {
			centerDiv(signInBox);
			centerDiv(signUpBox);
			centerDiv(idBox);
		}
	};

	//导航栏个人中心二级菜单显示和隐藏
	addHandler(signOut, "mouseover", function() {
		sO.style.display = "block";
	});
	addHandler(signOut, "mouseout", function() {
		sO.style.display = "none";
	});

	setTimeout(function reset(){
		var srcH = document.documentElement.clientHeight || document.body.clientHeight;

		scrollAnimate.bodyH = srcH;
		if(scrollAnimate.timer == 0) {
			scrollAnimate.resetLocat(scroll);
		}
		setTimeout(reset, 100);
	},100);

	//方向键事件
	addHandler(document, "keydown", function(e) {
		e = e || window.event;
		var currKey = e.keyCode || e.which;

		if(currKey == 38 || currKey == 33) {
			if(e.preventDefault) {
				e.preventDefault();
			}
			else if(e.returnValue) {
				e.returnValue = false;
			}
			scrollAnimate.scrollMove(scroll, -1);
		}
		else if(currKey == 40 || currKey == 34) {
			if(e.preventDefault) {
				e.preventDefault();
			}
			else if(e.returnValue) {
				e.returnValue = false;
			}
			scrollAnimate.scrollMove(scroll, 1);
		}
	});

	//滚轮事件
	addHandler(window, "mousewheel", function(e) {
		var isUp = 0;
		e = e || window.event;
		if(e.wheelDelta) {
			isUp = e.wheelDelta;
		}
		else if(e.detail){
			isUp = -e.detail;
		}

		if(isUp < 0) {  //向上滚动
			scrollAnimate.scrollMove(scroll, 1);
		}		
		else if(isUp > 0) {  //向下滚动
			scrollAnimate.scrollMove(scroll, -1);
		}
	});

	addHandler(document, "DOMMouseScroll", function(e) {
		var isUp = 0;
		e = e || window.event;
		if(e.wheelDelta) {
			isUp = e.wheelDelta;
		}
		else if(e.detail){
			isUp = -e.detail;
		}

		if(isUp < 0) {  //向上滚动
			scrollAnimate.scrollMove(scroll, 1);
		}
		else if(isUp > 0) {  //向下滚动
			scrollAnimate.scrollMove(scroll, -1);
		}
	});
	//给箭头添加点击事件
	addHandler(down, "click", function() {
		scrollAnimate.scrollMove(scroll, 1);
	});

	//登录框显示隐藏
	addHandler(signIn, "click", function() {
		showDiv(cover, signInBox);
	});
	addHandler(xbtnIn, "click", function() {
		hideDiv(cover, signInBox);
	});

	//注册框显示隐藏
	addHandler(signUp, "click", function() {
		showDiv(cover, signUpBox);
	});
	addHandler(xbtnUp, "click", function() {
		hideDiv(cover, signUpBox);
	});

	//验证码登录框显示隐藏
	addHandler(useidc, "click", function() {
		hideDiv(cover, signInBox);
		showDiv(cover, idBox);
	});
	addHandler(xidC, "click", function() {
		hideDiv(cover, idBox);
	});
});