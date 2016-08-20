var now_library = []; //当前文库
var print_car = []; //打印车

//表单错误提示框显示函数
function showError(message) {
	var promptBox = document.querySelector(".prompt-box");
	promptBox.innerHTML = message;
	promptBox.style.top = "0px";
	setTimeout(function() {
		promptBox.style.top = "-80px";
	}, 1500);
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

//创建文库列表
function createLibrary(elem) {
    var wrap = document.querySelector(".library-wrapper");
    var len = elem.length;
    var p;
    var str = "";
    var libId = "";
    var inner = "";
    for(var i = 0; i < len; i++) {
        libId = elem[i].getAttribute("data-libraryid");
        inner = elem[i].innerHTML;
        p = document.createElement("p");
        p.setAttribute("data-libraryid",libId);
        p.innerHTML = inner;
        if(i == 0) {
            p.style.color = "#fff";
            p.style.backgroundColor = "#0099ff";
        }
        wrap.appendChild(p);
    }
}

//填充打印车
function addFileLists() {
    var reg = /\.(\w+)$/;
    var newelem;
    var parent;
    var str = "";
    for(var i = 0; i < print_car.length; i++) {
        str = print_car[i].fileName.match(reg)
        str = str[0].slice(1);
        str = str.toLowerCase();
        if(str == "docx" || str == "doc") {
            str = "word";
        }
        else if(str == "pptx" || str == "ppt") {
            str = "ppt";
        }
        newelem = document.createElement("div");
        parent = document.querySelector(".file-wrap");
        newelem.setAttribute("data-fileMD5", print_car[i].fileMD5);
        newelem.className = str;
        newelem.innerHTML = '<p>' + print_car[i].fileName + '</p>' + '<i></i>';  
        parent.appendChild(newelem);
        addDelEvent(newelem);
    }
}

//添加删除事件
function addDelEvent(elem) {
    var i = elem.querySelector("i");
    var bit = document.querySelector(".file-wrap");
    var checkBox = document.querySelectorAll(".add-to-printcar");
    var checkFalse;
    addHandler(i, "click", function() {
        showError("移除中...");
        var md5 = this.parentNode.getAttribute("data-fileMD5");
        var parent = this.parentNode;
        for(var i = 0; i < print_car.length; i++) {
            if(print_car[i]["fileMD5"] == md5) {
                print_car.splice(i, 1);
            }
        }
        for(var j = 0; j < checkBox.length; j++) {
            checkFalse = checkBox[j].parentNode.parentNode.querySelector("p").getAttribute("data-fileMD5");
            if(checkFalse == md5) {
                checkBox[j].checked = false;
                break;
            }
        }
        bit.removeChild(parent);
        showError("已移出购物车");
    });
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
                location.href = "http://www.99dayin.com";
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
    	if(!delSpace(search.value)) {
    		showError("请输入文库编号");
    		search.value = "";
    		return;
    	}
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
        });
    });
    addHandler(applyX, "click", function() {
        hideDiv(coverBg, apply);
    });
    addHandler(applyBtn, "click", function() {
    	var libraryId = search.value;
    	libraryId = delSpace(libraryId);
    	var remark = remarkInfo.value;
    	if(remark.length > 15) {
    		showError("备注信息不得多于15字");
    		remarkInfo.value = "";
    		return;
    	}
    	else if(!remark || delSpace(remark).length == 0) {
    		remark = "";
    	}
    	var data = {
    		libraryId: libraryId,
    		remark: remark
    	};
        $.ajax({
            url: secret("./api/joinLib"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(data),
            success:function(data) {
                showError("请求已发出，等待审核");
                hideDiv(coverBg, apply);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                showError("请求失败，请重试");
            }
        });
    });



    //管理文库
    var manage_store = document.querySelector(".manage-store");
    var library_list = document.querySelector(".library-list");

    if(manage_store) {
        addHandler(manage_store, "click", function(e) {
            e = e || window.event;
            e.stopPropagation();
            e.cancelBubble = true;
            library_list.style.display = "block";
        });
        addHandler(document, "click", function() {
            library_list.style.display = "none";
        });
    }

    //初始样式设置
    var everyStore = document.querySelectorAll(".every-store");
    var section = document.querySelectorAll("section");
    var folder = document.querySelectorAll(".every-folder");
    var index = 0;
    //初始显示文库
    now_library[0] = section[0];

    //创建文库列表
    createLibrary(everyStore);
    var libList = document.querySelectorAll(".library-wrapper p");
    var storeLen = libList.length;

    //文库初始样式
    if(section) {
        section[0].style.display = "block";
    }

    //文件夹初始样式
    var folders;
    for(var m = 0; m < section.length; m++) {
        folders = section[m].querySelectorAll(".every-folder");
        folders[0].style.color = "#fff";
        folders[0].style.backgroundColor = "#acd6fe";
        section[m].querySelectorAll("span")[0].style.display = "inline";
    }

    //切换文库
    if(storeLen) {
        for(var l = 0; l < storeLen; l++) {
            addHandler(libList[l], "click", function() {
                $(".library-wrapper p").css({"color":"#336598", "background-color":"#fff"});
                this.style.color = "#fff";
                this.style.backgroundColor = "#0099ff";
                var library_id = this.getAttribute("data-libraryid");
                var section_show;
                for(var m = 0; m < section.length; m++) {
                    if(section[m].getAttribute("data-libraryid") == library_id) {
                        section_show = section[m];
                    }
                    section[m].style.display = "none";
                }
                section_show.style.display = "block";
                now_library[0] = section_show;
            });
        }
    }

    //点击文件夹切换
    var folder_len = folder.length;
    for(var n = 0; n < folder_len; n++) {
        addHandler(folder[n], "click", function() {
            var parent = this.parentNode;
            var brother = parent.querySelectorAll(".every-folder");
            var folderName = this.innerHTML;
            var span = now_library[0].querySelectorAll("span");
            for(var i = 0; i < span.length; i++) {
                if(span[i].getAttribute("data-filename") == folderName) {
                    span[i].style.display = "inline";
                }
                else {
                    span[i].style.display = "none";
                }
            }
            for(var l = 0; l < brother.length; l++) {
                brother[l].style.color = "#336598";
                brother[l].style.backgroundColor = "#fff";
            }
            this.style.color = "#fff";
            this.style.backgroundColor = "#acd6fe";
        });
    }

    //选择打印车文件
    $(".add-to-printcar").click(function() {
        var fileMD5 = $(this).parent().parent().find("p").attr("data-fileMD5");
        if( $(this).prop("checked") == true ) {
            /*$(".pick").prop("checked", false);*/
            var fileName = $(this).parent().parent().find("p").html();
            var file = {
                "fileName": fileName,
                "fileMD5": fileMD5
            };
            print_car.push(file);
        }
        else {
            for(var i = 0; i < print_car.length; i++) {
                if(print_car[i]["fileMD5"] == fileMD5) {
                    print_car.splice(i, 1);
                }
            }
        }
    });

    //点击打印车
    var printcar_list = document.querySelector(".printcar-list");
    var printcarX = document.querySelector(".list-title span");
    var printcarBtn = document.querySelector(".print-btn");
    var addCar = document.querySelector(".add-car");
    var fileWrap = document.querySelector(".file-wrap");

    addHandler(addCar, "click", function() {
        if(print_car.length == 0)  {
            showError("请选择要打印的文件");
            return;
        }
        showDiv(coverBg, printcar_list);
        fileWrap.innerHTML = "";
        addFileLists(); //填充打印车文件
    });
    addHandler(printcarX, "click", function() {
        hideDiv(coverBg, printcar_list);
    });
    addHandler(printcarBtn, "click", function() {
        var data = {
            files: print_car
        };
        $.ajax({
            url: secret("./api/addToCart"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(data),
            success:function(data) {
                location.href = "upload";
                hideDiv(coverBg, printcar_list);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                showError("请求失败，请重试");
            }
        });
    });


    //在线预览
    var url = "";
    $(".view").click(function() {
        //获取pdf格式文件
        var fileMD5 = $(this).prev().attr("data-fileMD5");
        var data = { fileMD5: fileMD5 };
        $.ajax({
            url: secret("./api/getPreview"),
            type: "POST",
            contentType:"application/json",
            dataType:"json",
            data: JSON.stringify(data),
            success:function(data) {
                console.log(data);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown){  
                showError("请求失败，请重试");
            }
        });
        $(".file-show-box").css("display", "block");
    });
    $(".pdfView").css("min-height", $(window).height()+"px");
    PDFJS.workerSrc = "script/pdf.worker.js";
    //传入pdf文件地址
    //var newPDF = attPreLoad(url);

    $('body').delegate('.next','click',function(){
        newPDF.nextPage();
    });
    $('body').delegate('.prev','click',function(){
        newPDF.prevPage();
    });
    $('body').delegate('.jumpTo','click',function(){
        newPDF.jumpPage();
    });
    $('body').delegate('.cancel','click',function(){
        $(".file-show-box").css("display", "none");
        //newPDF.destroy();
    }); 

    //点击预览


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

    $(".file-wrap").slimScroll({
        height: '280px', //容器高度,默认250px
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


//在线预览pdf文件
function attPreLoad(url){
    var pdfDoc = null,
        scale = 1.5,
        pageNum = 1,
        pageCount = 1,
        timestamp = new Date().getTime(),
        pageRendering = false,
        pageNumPending = null,
        canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d'),
        iUrl = url;

        /*这里是动态生成canvas的部分，因为打开多个pdf可能会存在绘制重叠的bug*/ 
        canvas.id = "canvas-" + timestamp; 
        $('.pdfView').append(canvas);

    showPdfWait("文件加载中，请稍候...");

    function renderPage(num){
        pageRendering = true;
        pdfDoc.getPage(num).then(function(page){
            var viewport = page.getViewport(scale);
            canvas.height = viewport.height;
            canvas.width = viewport.width;
            var renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            var renderTask = page.render(renderContext);
            //完成准备, 可以开始显示
            renderTask.promise.then(function () {
                $(".prompt-box").css("top", "-80px");
                pageRendering = false;
                if (pageNumPending !== null) {
                    renderPage(pageNumPending);
                    pageNumPending = null;
                }
            });
            //当前页
            $('.curPage').val(pageNum);
        });
    }
    function queueRenderPage(num) {
        if (pageRendering) {
            pageNumPending = num;
        } else {
            renderPage(num);
        }
    }
    PDFJS.getDocument(iUrl).then(function (pdfDoc_) {
        pdfDoc = pdfDoc_;
        pageCount = pdfDoc.numPages;
        //总页数
        $('.tolPage').html(pageCount);
        // Initial/first page rendering
        renderPage(pageNum);
    });
    return {
        prevPage: function() {
            if (pageNum > 1) {
                pageNum--;
                queueRenderPage(pageNum);
            }
        },
        nextPage: function() {
            if (pageNum < pageCount) {
                pageNum++;
                queueRenderPage(pageNum);
            } 
        },
        jumpPage: function(){
            var newPage = Number($(".curPage").val());
            if(newPage >= 1 && newPage <= pageCount) {
                pageNum = newPage;
                pageRendering = false;
                queueRenderPage(pageNum);
            } else {
                $('.curPage').val(pageNum);
            }
        },
        destroy: function(){
            $('.pdfView').empty();
            $('.pdfView').css("display", "none");
            $('.control').css("display", "none");
            pdfDoc.destroy();
        }
    };
}

//信息提示框显示函数
function showPdfWait(message) {
    var promptBox = document.querySelector(".prompt-box");
    promptBox.innerHTML = message;
    promptBox.style.top = "0px";
}