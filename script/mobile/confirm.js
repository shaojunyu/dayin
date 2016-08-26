//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

/* 根据类名获取parent下一个元素 */
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
function getPrice(box, select) {
	var fileMD5 = box.attr("data-md5");
	var isTwoSides; //单双面打印YES NO
	var amount; //打印份数
	var paperSize; //纸张大小A4 B4
	var pptPerPage; //int
	var direction; //方向vertical,horizontal
	var data = {
		fileMD5: fileMD5
	};
	if(select === "per-page") {
		pptPerPage = parseInt(box.find("."+select).html(), 10);
		data.pptPerPage = pptPerPage;
	}
	else if(select === "direction") {
		direction = box.find("."+select).html();
		if(direction === "横") {
			direction = "horizontal";
		}
		else {
			direction = "vertical";
		}
		data.direction = direction;
	}
	else if(select === "pos-and-neg") {
		isTwoSides = (box.find("."+select).html() == "单面" ? "NO" : "YES");
		data.isTwoSides = isTwoSides;
	}
	else if(select === "page-size") {
		paperSize = box.find("."+select).html();
		data.paperSize = paperSize;
	}
	else {
		amount = parseInt(box.find("."+select).val(), 10)+"";
		data.amount = amount;
	}

	$.ajax({
		url:secret("../api/printSettings"),
	    contentType:"application/json",
	    dataType:"json",
	    type:"POST",
	    data:JSON.stringify(data),
	    success:function(data) {
	    	if(data.success) {
	    		box.find(".sub-price").html(data.subTotal);
	    		compTotal();
	    	}
	    	else {
	    		showMsg(data.msg);
	    	}
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showMsg("修改失败，请刷新重设");
		}
	});
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

//计算选中的文件的总价
function compTotal() {
	var total = 0.00;
	$(".file-set-box").each(function (index, item) {
		var price = parseFloat(getClass(item, "sub-price").innerHTML);
		total += price;
	});
	$(".to-pay span").html(total.toFixed(2));
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
	/*$(".check").click(function () {
		if($(this).hasClass("check-click")) {
			$(this).removeClass("check-click");
			compTotal();
			return;
		}
		$(this).addClass("check-click");
		compTotal();
	});*/

	//全选
	/*$(".select-all").click(function () {
		$(".check").addClass("check-click");
		compTotal();
	});*/

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
		var clickSelect;
		var getSelect = $parent.parent().find(".choose-default");
		if(getSelect.hasClass("per-page")) {
			clickSelect = "per-page";
		}
		else if(getSelect.hasClass("direction")) {
			clickSelect = "direction";
		}
		else if(getSelect.hasClass("pos-and-neg")) {
			clickSelect = "pos-and-neg";
		}
		else {
			clickSelect = "page-size";
		}
		getPrice($parent.parent().parent(), clickSelect);
	});
	//打印份数输入框
	var isBlur = true;
	$(".copies").focus(function () {
		isBlur = false;
	});
	$(".copies").blur(function () {
		isBlur = true;
		var isEmpty = $.trim($(this).val());
		var isNumber = parseInt($.trim($(this).val()), 10);
		if(isEmpty === "" || isNumber <= 0 || isNaN(isNumber)) {
			$(this).val("1");
		}
		getPrice($(this).parent().parent(), "copies");
	});

	//提交订单
	var isSubmit = false;
	$(".to-pay").tap(function () {
		if(isSubmit) {
			return;
		}
		if(!isBlur) {
			return;
		}
/*		if($(".check-click").get().length === 0) {
			showMsg("请选择要打印的文件");
			return;
		}*/
		if($(".default").html() == "选择一个打印店") {
			showMsg("请选择打印店");
			return;
		}
		showMsg("提交中，请稍候");
		isSubmit = true;
		//提交订单
		var shop = $(".default").html();
		var total = $(".to-pay span").html();
		var deliveryMode = "self";
		var data = {
			shop: shop,
			total: total,
			deliveryMode: deliveryMode
		};
		$.ajax({
			url: secret("../api/createOrder"),
		    contentType: "application/json",
		    dataType: "json",
		    type: "POST",
		    data: JSON.stringify(data),
		    success: function(data) {
		    	if(data.success) {
		    		window.location.href = "../mobile/myself";
		    	}
		    	else {
		    		showMsg(data.msg);
		    	}
		    	isSubmit = false;
		    },
			error: function(XMLHttpRequest, textStatus, errorThrown){
				showMsg("提交失败，请重试");
				isSubmit = false;
			}
		});
	});

	//初始化总价
	compTotal();
});

/*
	$.ajax({
		url: secret("../api/createOrder"),
	    contentType: "application/json",
	    dataType: "json",
	    type: "POST",
	    data: JSON.stringify(data),
	    success:function(data) {
	    	if(data.success) {
				
	    	}
	   		else {
	        	
	        }
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showMsg("提交失败，请重试");
		}
	});
*/