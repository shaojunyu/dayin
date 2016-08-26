//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

/* 根据类名获取一个元素 */
function getClass(parent, klassName) {
	if(document.querySelector) {
		return parent.querySelector("." + klassName);
	}
	else if(document.getElementsByClassName) {
		return parent.getElementsByClassName(klassName)[0];
	}

	var children = parent.getElementsByTagName("*"),
	           i = children.length;
	while(i > 0) {
		i--;
		if(children[i].className.indexOf(klassName) >= 0) {
			return children[i];
		}
	}
}

//获取小计
function getPrice(box) {

}

//计算选中的文件的总价
function compTotal() {
	var total = 0.00;
	$(".check-click").each(function (index, item) {
		var price = parseFloat(getClass(item.parentNode.parentNode, "sub-price").innerHTML);
		total += price;
	});
	$(".to-pay").html("提交订单 ¥"+total.toFixed(2));
}

$(function () {
	//判断是否为word文档
	$(".file-name").each(function (index, item) {
		if(item.innerHTML.indexOf("doc") !== -1 || item.innerHTML.indexOf("docx") !== -1) {
			var temp = item.parentNode.parentNode.querySelectorAll(".choose-box")[0];
			temp.innerHTML = "/";
			temp.style.textAlign = "center";
		}
	});

	//选择打印店
	$(".default").click(function () {
		var $option = $(".option-box");
		if($option.css("display") == "none") {
			$option.show();
			return;
		}
		$option.hide();
	});
	$(".icon").click(function () {
		var $option = $(".option-box");
		if($option.css("display") == "none") {
			$option.show();
			return;
		}
		$option.hide();
	});
	$(".option").click(function () {
		$(".default").html($(this).html());
		$(".option-box").hide();
	});

	//复选框
	$(".check").click(function () {
		if($(this).hasClass("check-click")) {
			$(this).removeClass("check-click");
			return;
		}
		$(this).addClass("check-click");
	});

	//全选
	$(".select-all").click(function () {
		$(".check").addClass("check-click");
	});

	//每个文件下的select
	$(".choose-default").tap(function () {
		var change = $(this).parent().find(".select-box");
		if(change.css("display") == "none") {
			$(".select-box").hide();
			change.show();
		}
		else {
			change.hide();
		}
	});
	//改变设置
	$(".select-box a").tap(function () {
		$parent = $(this).parent();
		$parent.parent().find(".choose-default").html($(this).html());
		$parent.hide();
		getPrice($parent.parent().parent().parent());
	});
	$(".copies").blur(function () {
		getPrice($(this).parent().parent());
	});

	//提交订单
	$(".to-pay").click(function () {
		
	});
});