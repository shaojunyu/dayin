//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
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
});