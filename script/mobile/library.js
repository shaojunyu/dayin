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
function addFolder(data) {
	$(".library-box").css("display", "none");
	$(".loading").css("display", "block");
	var i = 0;
	var folder = "";
	var files = "";
	var parent = $(".library-info-box");
	for(var key in data) {
		i++;
		if(i === 1) {
			folder += '<a href="javascript:;" class="folder click-folder">'+key+'</a>';
		}
		else {
			folder += '<a href="javascript:;" class="folder">'+key+'</a>';
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
	        	addFolder(data);
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown){  
	        	 
	    	}
	    });
	});

	//点击返回上一级
	$(".return").click(function () {
		$(".library-info-box").css("display", "none");
		$(this).css("display", "none");
		$(".library-box").css("display", "block");
		$(".hack").css("display", "none");
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
            	console.log(data);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                //showError("文库查询失败，请重试");
            }
        });
	});

	$(".search-input").focus(function () {
		$(this).val("");
		$(this).css({ "color": "#fff", "border": "1px solid #fff" });
	});


	/*var dat = {
    		libraryId: libraryId
    	};
    	$.ajax({
            url: secret("./api/searchLib"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(dat),
            success:function(data) {
            	if(data.msg == "文库不存在") {
            		showError("文库不存在，请输入正确编号");
            		search.value = "";
            		return;
            	}
            	document.querySelector(".lib-name").innerHTML = data.libName;
                showDiv(coverBg, apply);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                showError("文库查询失败，请重试");
            }
        });*/
});