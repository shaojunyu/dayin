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

//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

//隐藏错误提示
function hideError(box, input) {
	input.css("color", "#56a0ad");
	input.val("");
}

//显示错误提示
function showError(box, input, err) {
	input.css("color", "red");
	input.val(err);
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
	        	 
	    	}
	    });
	});

	//注册
	$(".sbm").click(function () {
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

		//发送注册请求
		var user = $(".phone").val().split("-").join("");
		var passw = $(".password").val();
		var data = {
			cellphone: user,
			smscode: smscode
		};
		$.ajax({
			url: secret("../api/loginBySmscode"),
			contentType: "application/json",
			dataType: "json",
			type: "POST",
			data: JSON.stringify(data),
			success: function(data) {
				console.log(data);
				if(data.success) {
					window.location.href = "../mobile/library";
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown){  
				alert("请求失败"); 
			}
		});
	});
});