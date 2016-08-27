/* 表单验证方法 */
var formMathod = {};

//手机号验证
formMathod.phoneReg = /^1[3|4|5|7|8]\d{9}$/;
formMathod.phoneCheck = function(input) { //参数为表单元素
	var val = input.value.split("-").join("");
	if(val.length === 0) {
		return "请输入手机号!";
	}
	else if(val.length !== 11 || !formMathod.phoneReg.test(val)) {
		return "手机号输入有误!";
	}

	return true;
};

//密码验证
formMathod.passwdRegZH = /^[^\u4E00-\u9FA5]{5,20}$/;
formMathod.passwdRegQJ = /^[^\uFF00-\uFFFF]{5,20}$/;
formMathod.passwdRegSp = /\s/;
formMathod.passwdCheck = function(input) { //验证非中文和全角
	var val = input.value;
	var msg = "";
	if(val.length === 0) {
		msg = "密码不能为空!";
	}
	else if(val.length < 8) {
		msg = "密码长度不得小于8!";
	}
	else if(!formMathod.passwdRegZH.test(val)) {
		msg = "密码不能含有中文!";
	}
	else if(formMathod.passwdRegSp.test(val)) {
		msg = "密码不能含有空格!";
	}
	else if(!formMathod.passwdRegQJ.test(val)) {
		msg = "密码不能含有全角字符!";
	}

	if(msg === "") {
		return true;
	}
	else {
		input.value = "";
		return msg;
	}
};

//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

//选择框的显示和隐藏
function selectClick() {
	var $selectBox = $(".select-box");
	var display = $selectBox.css("display");
	hideError($(".choose-default"), $(".choose-default"));
	if(display == "none") {
		$selectBox.show();
		return;
	}

	$selectBox.hide();
}

//隐藏错误提示
function hideError(box, input) {
	input.css("color", "#56a0ad");
	if(box == input) {
		input.html("选择您的学校");
		return;
	}
	input.val("");
}

//显示错误提示
function showError(box, input, err) {
	input.css("color", "red");
	if(box == input) {
		input.html(err);
		return;
	}
	input.val(err);
}

//消息显示框显示
function showMsg(msg) {
	$(".prompt-box").html(msg);
	$(".prompt-box").show();
	setTimeout(function () {
		hideMsg();
	}, 2000);
}

//消息显示框隐藏
function hideMsg() {
	$(".prompt-box").html("");
	$(".prompt-box").hide();
}

//1分钟后重试
function getDisable() {
	var time = 59;
	$(".get").html(time + "秒后重试");
	var timer = setInterval(function () {
		time--;
		if(time === 0) {
			$(".get").html("获取短信验证码");
			clearInterval(timer);
			return;
		}
		$(".get").html(time + "秒后重试");
	}, 1000);
}


$(function () {
	//选择学校
	$(".choose-default").click(function () {
		selectClick();
	});
	$(".jt").click(function () {
		selectClick();
	});
	$(".select-box a").click(function () {
		var school = $(this).html();
		$(".choose-default").html(school);
		selectClick();
	});

	//手机输入处理
	$(".phone").focus(function() { //获得焦点
		if($(this).css("color") == "red") {
			hideError($(".phone-box"), $(this));
			return;
		}
		$(this).val($(this).val().split("-").join(""));
	});
	$(".phone").blur(function() { //失去焦点
		var val = $.trim($(this).val());
		var str = "";
		if(val.length > 3 && val.length <= 7) {
			str = str.concat(val.substr(0, 3), "-", val.substr(3));
		}
		else if(val.length > 7) {
			str = str.concat(val.substr(0, 3), "-", val.substr(3, 4), "-", val.substr(7));
		}
		$(this).val(str || val);
	});

	//验证码输入处理
	$(".smscode").focus(function() { //获得焦点
		if($(this).css("color") == "red") {
			hideError($(".smscode-box"), $(this));
		}
	});

	//密码输入处理
	$(".password").focus(function() { //获得焦点
		$(this).attr("type", "password");
		if($(this).css("color") == "red") {
			hideError($(".password-box"), $(this));
		}
	});

	//获取短信验证码
	$(".get").click(function () {
		var isPhoneOk = formMathod.phoneCheck($(".phone")[0]);
		if(typeof isPhoneOk === "string") {
			showError($(".phone-box"), $(".phone"), isPhoneOk);
			return;
		}
		else if($(this).html() !== "获取短信验证码") {
			return;
		}
		//发送验证码
		var phoneNum = $(".phone").val().split("-").join("");
		var data = {
			cellphone: phoneNum
		};

		$.ajax({
	        url: secret("../api/sendSmscode"),
	        contentType: "application/json",
	        dataType: "json",
	        type: "POST",
	        data: JSON.stringify(data),
	        success: function(data) {
	        	if(data.success === true) {
	        		getDisable();
	        	}
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){  
	        	 alert("请求失败");
	    	}
	    });
	});

	//注册
	$(".sbm").click(function () {
		//验证学校是否选择
		var school = $(".choose-default").html();
		if(school == "选择您的学校") {
			showError($(".choose-default"), $(".choose-default"), "选择您的学校");
			return;
		}

		//验证手机号是否正确
		var isPhoneOk = formMathod.phoneCheck($(".phone")[0]);
		if(typeof isPhoneOk === "string") {
			showError($(".phone-box"), $(".phone"), isPhoneOk);
			return;
		}

		//验证手机验证码
		var smscode = $.trim($(".smscode").val());
		if(!smscode) {
			showError($(".smscode-box"), $(".smscode"), "请输入验证码");
			return;
		}

		//验证密码
		var isPasswordOk = formMathod.passwdCheck($(".password")[0]);
		if(typeof isPasswordOk === "string") {
			$(".password").attr("type", "text");
			showError($(".password-box"), $(".password"), isPasswordOk);
			return;
		}

		//发送注册请求
		var user = $(".phone").val().split("-").join("");
		var passw = $(".password").val();
		var data = {
			cellphone: user,
			password: passw,
			school: school,
			smscode: smscode
		};
		$.ajax({
			url: secret("../api/signup"),
			contentType: "application/json",
			dataType: "json",
			type: "POST",
			data: JSON.stringify(data),
			success: function(data) {
				if(data.success) {
					window.location.href = "../mobile/library";
				}
				else {
					showMsg(data.msg);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){  
				showMsg("请求失败"); 
			}
		});
	});
});