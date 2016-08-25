//加密函数
function secret(url) {
	var date = new Date();
	var seconds = Math.round(date.getTime()/1000);
	var md5_str = seconds + "99dayin.com";
	md5_str = calcMD5(md5_str);
	url += "?time=" + seconds + "&token=" + md5_str;
	return url;
}

//填充文件夹和文件
var car = 0;
function addFolder(data) {
	car = 0;
	$(".library-box").css("display", "none");
	$(".addto-car").show();
	$(".addto-car").html("加入打印车 ("+car+")");
	var i = 0;
	var folder = "";
	var files = "";
	var parent = $(".library-info-box");
	for(var key in data) {
		i++;
		if(i === 1) {
			folder += '<a href="javascript:;" data-index='+i+' class="folder click-folder">'+key+'</a>';
		}
		else {
			folder += '<a href="javascript:;" data-index='+i+' class="folder">'+key+'</a>';
		}

		files += '<div class="files" data-index='+i+'>';
		for(var j = 0; j < data[key].length; j++) {
			files += '<div class="file clearfix"'+' data-filemd5='+data[key][j].fileMD5+'><div class="file-name"><p>'+data[key][j].fileName+'</p></div><div class="check-box"><a href="javascript:;" class="check"></a></div></div>';
		}
		files += '</div>';
	}
	folder = '<div class="folders">' + folder + '</div>';
	parent.html(folder+files);
	parent.css("display", "block");
	$(".loading").css("display", "none");
	$(".return").css("display", "block");
	$(".hack").css("height", $(document).height() + "px");

	addClick();
}

//给文件夹添加点击事件
function addClick() {
	//点击文件夹进入相应文件夹
	$(".folder").click(function () {
		var $oldClick = $(".click-folder");
		var oldIndex = $oldClick.attr("data-index");
		$oldClick.removeClass("click-folder");
		$(this).addClass("click-folder");
		var dataIndex = $(this).attr("data-index");
		var $files = $(".files");
		$files.each(function (index, item) {
			var nowIndex = item.getAttribute("data-index");
			if(nowIndex == dataIndex) {
				item.style.display = "block";
			}
			else if(nowIndex == oldIndex) {
				item.style.display = "none";
			}
		});
	});

	//给每个复选框加上点击效果
	$(".check").click(function () {
		if($(this).hasClass("check-click")) {
			$(this).removeClass("check-click");
			car--;
			$(".addto-car").html("加入打印车 ("+car+")");
			return;
		}
		$(this).addClass("check-click");
		car++;
		$(".addto-car").html("加入打印车 ("+car+")");
	});
}

//申请加入弹出框的显示
function showApply(data, libraryId) {
	var libName = data.libName;
	$(".application").show();
	$(".cover").show();
	$(".title p").html(libName);
	$(".library-id").html(libraryId);
}

//申请加入成功后
function applySuccess(data) {
	$(".cover").hide();
	$(".application").hide();
	var parent = $(".library-box");
	var applyInfo = '<div class="library-wrap apply-status"><img src="image/apply.png"><p>机械1405班文库<br><span>000001</span></p><span class="apply">申请中</span></div>';
	parent.html(applyInfo);
}

$(function () {
	//点击进入文库
	$(".library-wrap").click(function () {
		if($(this).hasClass("apply-status")) {
			return;
		}
		var libraryId = $(this).find("span").html();
		var data = {
			libraryId: libraryId
		};
		$.ajax({
	        url: secret("../api/getLibFiles_m"),
	        contentType: "application/json",
	        dataType: "json",
	        type: "POST",
	        data: JSON.stringify(data),
	        success: function(data) {
	        	$(".loading").css("display", "block");
	        	addFolder(data);
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){  
	        	 $(".loading").css("display", "none");
	        	 alert("请求失败");
	    	}
	    });
	});

	//点击返回上一级
	$(".return").click(function () {
		$(".library-info-box").css("display", "none");
		$(this).css("display", "none");
		$(".library-box").css("display", "block");
		$(".hack").css("display", "none");
		$(".library-info-box").html("");
		$(".addto-car").hide();
	});

	//点击搜索文库
	$(".search-btn").click(function () {
		var libraryId = $.trim($(".search-input").val());
		if(!libraryId) {
			var $searchInput = $(".search-input");
			$searchInput.val("请输入文库编号");
			$searchInput.css({ "color": "red", "border": "1px solid red" });
			return;
		}
		//发送请求查询文库
		var data = {
			libraryId: libraryId
		};
		$.ajax({
            url: secret("../api/searchLib"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(data),
            success: function(data) {
            	if(typeof data.success === "undefined") {
	            	$(".search-input").val("");
	            	showApply(data, libraryId);
	            }
	            else {
	            	var $searchInput = $(".search-input");
					$searchInput.val(data.msg);
					$searchInput.css({ "color": "red", "border": "1px solid red" });
	            }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                alert("请求失败");
            }
        });
	});

	$(".search-input").focus(function () {
		$(this).val("");
		$(this).css({ "color": "#fff", "border": "1px solid #fff" });
	});

	//从申请加入弹出框回到页面
	$(".cancel").click(function () {
		$(".cover").hide();
		$(".application").hide();
	});

	//申请加入
	$(".apply-add").click(function () {
		var libraryId = $(".library-id").html();
		var remark = "";
		var data = {
			libraryId: libraryId,
			remark: remark
		};
		$.ajax({
            url: secret("../api/joinLib"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(data),
            success:function(data) {
            	if(data.success == "1") {
            		window.location.href = "../mobile/library";
            	}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                alert("请求失败，请重试");
            }
        });
	});

	//加入购物车
	var isAdding = false;
	$(".addto-car").click(function () {
		if(isAdding) {
			return;
		}
		isAdding = true;
		var choose = $(".check-click");
		var shoppingCart = [];
		choose.each(function (index, item) {
			var file = item.parentNode.parentNode;
			var fileMD5 = file.getAttribute("data-filemd5");
			var fileName = file.querySelector(".file-name p").innerHTML;
			var fileInfo = {
				fileMD5: fileMD5,
				fileName: fileName
			};
			shoppingCart.push(fileInfo);
		});

		//发送请求
		var data = {
            files: shoppingCart
        };
        $.ajax({
            url: secret("../api/addToCart"),
            type: "POST",
            contentType: "application/json",
            dataType: "json",
            data: JSON.stringify(data),
            success: function(data) {
            	isAdding = false;
            	if(data.success) {
	            	$(".added").show();
	            	$(".cover").show();
	            }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){
            	isAdding = false;  
                alert("请求失败，请重试");
            }
        });
	});

	//继续选择文件
	$(".continue").click(function () {
		$(".cover").hide();
		$(".added").hide();
		$(".library-info-box").css("display", "none");
		$(".return").css("display", "none");
		$(".library-box").css("display", "block");
		$(".hack").css("display", "none");
		$(".library-info-box").html("");
		$(".addto-car").hide();
	});
});
