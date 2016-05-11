//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	setTimeout(function() {
		promptBox.style.top = "-80px";
	}, 2000);
}

//计算钱数
function countMoney(amout, size, isTwoSide, filePages, parent) {
	/*var total = 0;
	var per = 0;
	console.log(amout + size + isTwoSide);
	if(!amout) {
		amout = 1;
	}
	amout = parseInt(amout);
	if(size == paperSizeArr[0]) {
		if(isTwoSide == sidesArr[0]) {
			total = amout * perMoney[0];
			per = perMoney[0];	
		}
		else {
			total = amout * perMoney[1];
			per = perMoney[1];
		}
	}
	else {
		total = amout * perMoney[2];
		per = perMoney[2];
	}
	total *= filePages;
	total = total.toFixed(3);
	parent.find(".row-10").text(total);
	parent.find(".row-8").text(per);
	//shipment(total);*/
}

//移除div
function removeC(elem) {
    var parent = document.querySelector(".scroll-box");
    parent.removeChild(elem);
}

//删除后重新排序
function reSort() {
	var nowDiv = document.querySelectorAll(".scroll-box div");
	var len = nowDiv.length - 1;
	for(var i = 0; i < len; i++) {
		nowDiv[i].querySelector(".row-1").innerHTML = i + 1;
	}
}

//计算包含运费的钱
/*function shipment(total) {

}*/

//改变参数时发送Ajax请求
function sendMsg(data, str) {
	$.ajax({
		url:"./api/printSettings",
	    contentType:"application/json",
	    dataType:"json",
	    type:"POST",
	    data:JSON.stringify(data),
	    success:function(data) {
	    	if(data.success) {
	    		
	    	}
	   		else {
	        	showError(data.msg);
	        }
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showError("修改失败，请重设" + str);
		}
	});
}

var paperSizeArr = ["A4", "B4"];
var sidesArr = ["单面", "双面"];
var perMoney = [0.1, 0.15, 0.4];

$(document).ready(function() {
	$("#sign-out").mouseover(function() {
		$(".so").css("display", "block");
	});
	$("#sign-out").mouseout(function() {
		$(".so").css("display", "none");
	});

	//到店自取显示和隐藏
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
	
	//送货上门显示和隐藏
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


	//监听改变订单信息事件
	//打印面数
	$(".face").change(function() {
		var isTwoSides = $(this).find("option:selected").text();
		isTwoSides = isTwoSides.replace(/[\r\n]/g,"");
		isTwoSides = isTwoSides.split(' ').join('');
		var istwosides = isTwoSides;
		if(isTwoSides == "单面") {
			isTwoSides = "NO";
		}
		else {
			isTwoSides = "YES";
		}
		var parent = $(this).parent().parent();
		var filePages = parseInt(parent.find(".row-3").text());
		var md5 = parent.attr("data-md5");
		var data = {
			fileMD5: md5,
			isTwoSides: isTwoSides
		};
		var size = parent.find(".size").find("option:selected").text(); //获取大小
		size = size.replace(/[\r\n]/g,"");
		size = size.split(' ').join('');
		var amout = parent.find(".amout").val(); //获取打印份数
		countMoney(amout, size, istwosides, filePages, parent);
		sendMsg(data, "单双面");
	});

	//打印大小
	$(".size").change(function() {
		var itSize = $(this).find("option:selected").text();
		itSize = itSize.replace(/[\r\n]/g,"");
		itSize = itSize.split(' ').join('');
		var parent = $(this).parent().parent();
		var filePages = parseInt(parent.find(".row-3").text());
		var md5 = parent.attr("data-md5");
		var data = {
			fileMD5: md5,
			paperSize: itSize
		};
		var sides = parent.find(".face").find("option:selected").text(); //获取单双面
		sides = sides.replace(/[\r\n]/g,"");
		sides = sides.split(' ').join('');
		var amout = parent.find(".amout").val(); //获取打印份数
		countMoney(amout, itSize, sides, filePages, parent);
		sendMsg(data, "大小");
	});

	//打印份数
	$(".amout").change(function() {
		var parent = $(this).parent().parent();
		var filePages = parseInt(parent.find(".row-3").text());
		var md5 = parent.attr("data-md5");
		var size = parent.find(".size").find("option:selected").text();
		size = size.replace(/[\r\n]/g,"");
		size = size.split(' ').join('');
		var isTwoSide = parent.find(".face").find("option:selected").text();
		isTwoSide = isTwoSide.replace(/[\r\n]/g,"");
		isTwoSide = isTwoSide.split(' ').join('');
		var amout = $(this).val();
		var data = {};
		amout = parseInt(amout);
		if(!amout) {
			showError("请输入正确的数字");
			$(this).val('');
		}
		else {
			countMoney(amout, size, isTwoSide, filePages, parent);
			data = {
				fileMD5: md5,
				amout: amount
			};
			sendMsg(data, "份数");
		}
	});

	//打印方向
	$(".direction").change(function() {
		var parent = $(this).parent().parent();
		var md5 = parent.attr("data-md5");
		var direction = $(this).find("option:selected").text();
		direction = direction.replace(/[\r\n]/g,"");
		direction = direction.split(' ').join('');
		if(direction == "横") {
			direction = "horizontal";
		}
		else {
			direction = "vertical";
		}
		var data = {
			fileMD5: md5,
			direction: direction
		};
		sendMsg(data, "方向");
	});

	//每页ppt数量
	$(".page-num").change(function() {
		var parent = $(this).parent().parent();
		var md5 = parent.attr("data-md5");
		var pptPerPage = $(this).find("option:selected").text();
		pptPerPage = parseInt(pptPerPage);
		var data = {
			fileMD5: md5,
			pptPerPage: pptPerPage
		};
		sendMsg(data, "每面ppt数");
	});

	//备注
	$(".remark").change(function() {
		var parent = $(this).parent().parent();
		var md5 = parent.attr("data-md5");
		var remark = $(this).val();
		if(remark.length > 100) {
			$(this).val("");
			showError("请不要输入超过100个字符");
			return;
		}
		var data = {
			fileMD5: md5,
			remark: remark
		};
		sendMsg(data, "备注");
	});

	//生成订单
	$(".pay").click(function() {
		if($(".pick").prop("checked") == false && $(".todoor").prop("checked") == false ) {
			showError("请选择收货方式");
			return;
		}
		//列表中是否有文件
		var fileNum = document.querySelectorAll(".scroll-box div");
		if(fileNum.length <= 1) {
			showError("请选择文件");
			return;
		}
		
		if($(".pick").prop("checked") == true) { //到店自取

		}
		else if($(".todoor").prop("checked") == true) { //送货上门

		}
	});

	//删除订单
	$(".scroll-box .row-12").click(function() {
		var div = this.parentNode;
		var md5 = $(this).parent().attr("data-md5");
		var data = {
			fileMD5: md5
		};
		delMsg(data);
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

//删除
function delMsg(Data) {
	$.ajax({
		url:"./api/deleteItem",
	    contentType:"application/json",
	    dataType:"json",
	    type:"POST",
	    data:JSON.stringify(Data),
	    success:function(data) {
	    	if(data.success) {
				var md5 = Data.fileMD5;
				var allDiv = document.querySelectorAll(".scroll-box div");
				var data_md5;
				for(var i = 0; i < allDiv.length; i++) {
					(function(m){
						var i = m;
						if(md5 == allDiv[i].getAttribute("data-md5")) {
							removeC(allDiv[i]);
							reSort();
						}
					})(i);
				}
	    	}
	   		else {
	        	showError(data.msg);
	        }
	    },
		error: function(XMLHttpRequest, textStatus, errorThrown){
			showError("删除失败");
		}
	});
}