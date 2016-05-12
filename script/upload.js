
accessid = ''
accesskey = ''
host = ''
policyBase64 = ''
signature = ''
callbackbody = ''
filename = ''
key = ''
expire = 0
g_object_name = ''
g_object_name_type = ''
now = timestamp = Date.parse(new Date()) / 1000; 

var newElem = {};
var file_status = ["processing", "fail", "done"];
var status_list = [];

function get_signature()
{
    //可以判断当前expire是否超过了当前时间,如果超过了当前时间,就重新取一下.3s 做为缓冲
    now = timestamp = Date.parse(new Date()) / 1000; 
    if (expire < now + 3)
    {
        $.ajax({
            url: secret('./api/getUploadToken'),
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: '{}',
            async: false,
            success: function(data) {
                console.log(data);
                host = data['host'];
                policyBase64 = data['policy'];
                accessid = data['accessid'];
                signature = data['signature'];
                expire = parseInt(data['expire']);
                callbackbody = data['callback']; 
                key = data['dir'];
            }
        });
        return true;
    }
    return false;
};

function random_string(len) {
　　len = len || 32;
　　var chars = 'ABCDEFGHJKMNPQRSTWXYZabcdefhijkmnprstwxyz2345678';   
　　var maxPos = chars.length;
　　var pwd = '';
　　for (i = 0; i < len; i++) {
    　　pwd += chars.charAt(Math.floor(Math.random() * maxPos));
    }
    return pwd;
}

function get_suffix(filename) {
    pos = filename.lastIndexOf('.')
    suffix = ''
    if (pos != -1) {
        suffix = filename.substring(pos)
    }
    return suffix;
}

function calculate_object_name(filename)
{
    if (g_object_name_type == 'local_name')
    {
        g_object_name += "${filename}"
    }
    else if (g_object_name_type == 'random_name')
    {
        suffix = get_suffix(filename)
        g_object_name = key + random_string(10) + suffix
    }
    return ''
}

function get_uploaded_object_name(filename)
{
    if (g_object_name_type == 'local_name')
    {
        tmp_name = g_object_name
        tmp_name = tmp_name.replace("${filename}", filename);
        return tmp_name
    }
    else if(g_object_name_type == 'random_name')
    {
        return g_object_name
    }
}

function set_upload_param(up, filename, ret)
{
    var date = new Date();
    date = date.getTime();
    if (ret == false)
    {
        ret = get_signature()
    }
    filename = date + '-' + filename;
    g_object_name = key + filename;
    if (filename != '') { 
        suffix = get_suffix(filename);
        calculate_object_name(filename);
    }
    new_multipart_params = {
        'key' : g_object_name,
        'policy': policyBase64,
        'OSSAccessKeyId': accessid, 
        'success_action_status' : '200', //让服务端返回200,不然，默认会返回204
        'callback' : callbackbody,
        'signature': signature,
    };

    up.setOption({
        'url': host,
        'multipart_params': new_multipart_params
    });
    up.start();

}

var uploader = new plupload.Uploader({
    browse_button : 'ul', //触发文件选择对话框的按钮，为那个元素id
    url : 'http://oss.aliyuncs.com', //服务器端的上传页面地址
    container: 'file',
    filters: {
      mime_types : [ //只允许上传图片和zip文件
        { title : "Word files", extensions : "doc,docx" }, 
        { title : "PPT files", extensions : "ppt,pptx" },
        { title : "PDF files", extensions : "pdf" }
      ],
      max_file_size : '50mb', //最大只能上传50mb的文件
      prevent_duplicates : true //不允许选取重复文件
    },
    init: {
        FilesAdded: function(up,files) {
            plupload.each(files, function(file){
                var isSame = sameName(file.name);
                if(!isSame) {
                    up.removeFile(file);
                    showError("文件重复");
                    return;
                }
                var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
                chunkSize = 2097152,                         // Read in chunks of 2MB
                chunks = Math.ceil(file.size / chunkSize),
                currentChunk = 0,
                spark = new SparkMD5.ArrayBuffer(),
                fileReader = new FileReader();
                
                var div = createNew(file.name);

                fileReader.onload = function (e) {
                    spark.append(e.target.result); // Append array buffer
                    currentChunk++;
                    
                    if (currentChunk < chunks) {
                        newElem[file.name][0].getElementsByTagName("p")[1].innerHTML = "校验中...  " + 100 * (Math.round(currentChunk/chunks)) + "%";
                        loadNext();
                    } else {
                        newElem[file.name][1] = spark.end();
                        var success = sendMD5(spark.end()); //校验md5值

                        if(success) {  //已有文件
                            addFile(file.name);
                            removeC(newElem[file.name][0]);
                            delete newElem[file.name];
                            showError("上传成功");
                        }
                        else {  //获取token并上传文件
                            set_upload_param(uploader, file.name, false);   
                        }
                    }
                };
                fileReader.onerror = function () {
                    console.warn('oops, something went wrong.');
                };
                function loadNext() {
                    var start = currentChunk * chunkSize,
                        end = ((start + chunkSize) >= file.size) ? file.size : start + chunkSize;
                    fileReader.readAsArrayBuffer(blobSlice.call(file.getNative(), start, end));
                }
                loadNext();
            });
        },

        BeforeUpload: function(up, file) {
            set_upload_param(up, file.name, true);
        },
        /*FileFiltered: function(up, file) {
            var isSame = sameName(file.name);
            if(!isSame) {
                up.removeFile(file);
            }
        },*/

        UploadProgress: function(up, file) { //文件上传中
            newElem[file.name][0].getElementsByTagName("p")[1].innerHTML = "上传中：" + file.percent + "%";
        },

        FileUploaded: function(up, file, info) { //上传完成后
            if (info.status == 200)
            {
                var filename = file.name;
                var fileMd5 = newElem[filename][1];
                var data = {fileName: filename, fileMD5: fileMd5};
                $.ajax({
                    url: secret('./api/uploadACK'),
                    type: 'POST',
                    data: JSON.stringify(data),
                    dataType: 'json',
                    contentType: 'application/json',
                    success: function(data) {
                        var self_md5 = newElem[file.name][1];
                        if(data.success) {
                            removeC(newElem[file.name][0]);
                            delete newElem[file.name];
                            createStatus(file.name, self_md5);
                            showError("上传成功");
                        }
                        else {
                            removeC(newElem[file.name][0]);
                            delete newElem[file.name];
                            createStatus(file.name, self_md5);
                            showError(data.msg);
                        }
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown){  
                        showError("请求失败");
                    }
                });
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = 'upload to oss success, object name:' + get_uploaded_object_name(file.name) + ' 回调服务器返回的内容是:' + info.response;
            }
            else if (info.status == 203)
            {
                showError("上传失败，请重试");
                removeC(newElem[file.name][0]);
                delete newElem[file.name];
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '上传到OSS成功，但是oss访问用户设置的上传回调服务器失败，失败原因是:' + info.response;
            }
            else
            {
                showError("上传失败，请重试");
                removeC(newElem[file.name][0]);
                delete newElem[file.name];
                //document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = info.response;
            } 
        }
    }
});

uploader.init();

//发送文件md5值
function sendMD5(md5) {
    var data = {fileMD5: md5};
    $.ajax({
        url: secret("./api/confirmMD5"),
        type: "POST",
        contentType:"application/json",
        dataType:"json",
        data: JSON.stringify(data),
        success:function(data) {
            if(data.msg == "yes") {
                return true;
            }else {
                return false;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown){  
            showError("请求失败");  
        }
    });
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

//上传过程创建的进度div
function createNew(name) {
    var newelem = document.createElement("div");
    var next = document.querySelector("#file");
    var parent = document.querySelector(".scroll-bar");
    newelem.innerHTML = '<p>' + name + '</p>' + '<p>校验中...' + '</p>';
    parent.insertBefore(newelem, next);
    newElem[name] = [];
    newElem[name][0] = newelem;
    return newelem;
}

//解析过程的进度div
function createStatus(name, md5) {
    var newelem = document.createElement("div");
    var next = document.querySelector("#file");
    var parent = document.querySelector(".scroll-bar");
    newelem.innerHTML = '<p>' + name + '</p>' + '<p>后台解析中...' + '</p>';
    newelem.setAttribute("data-status", file_status[0]);
    parent.insertBefore(newelem, next);
    newElem[name] = [];
    newElem[name][0] = newelem;
    newElem[name][1] = md5;
    status_list.push(newelem);
}

//移除div
function removeC(elem) {
    var parent = document.querySelector(".scroll-bar");
    parent.removeChild(elem);
}

//文件上传成功后显示在页面上
function addFile(filename) {
    var reg = /\.(\w+)$/;
    var str = filename.match(reg);
    var date = new Date();
    var hours = date.getHours();
    var seconds = date.getMinutes();

    if(hours < 10) {
        hours = '0' + hours;
    }
    if(seconds < 10) {
        seconds = '0' + seconds;
    }

    str = str[0].slice(1);
    str = str.toLowerCase();
    if(str == "docx" || str == "doc") {
        str = "word";
    }
    else if(str == "pptx" || str == "ppt") {
        str = "ppt";
    }

    var newelem = document.createElement("div");
    var next = document.querySelector("#file");
    var parent = document.querySelector(".scroll-bar");
    newelem.setAttribute("data-md5", newElem[filename][1]);
    newelem.className = str;
    newelem.innerHTML = '<p>' + filename + '</p>' + '<p>上传时间：' + date.toLocaleDateString() + ' ' + hours + ':' + seconds + '</p>' + '<i></i>';    
    parent.insertBefore(newelem, next);
    addDelEvent(newelem);
}

//添加删除事件
function addDelEvent(elem) {
    var i = elem.querySelector("i");
    addHandler(i, "click", function() {
        var self = $(this);
        var md5 = $(this).parent().attr("data-md5");
        var data = {fileMD5: md5};
        $.ajax({
            url: secret("./api/deleteItem"),
            contentType: "application/json",
            dataType: "json",
            type: "POST",
            data: JSON.stringify(data),
            success: function(data) {
                self.parent().remove();
                showError("删除成功");
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                showError("删除失败");
            }
        });
    });
}

//提示重名
function sameName(filename) {
    var havenName = document.querySelectorAll(".scroll-bar div p:first-child");
    var len = havenName.length;
    for(var i = 0; i < len; i++) {
        if(havenName[i].innerHTML == filename) {
            return false;
        }
    }
    return true;
}

$(document).ready(function() {
    var uploadBox = document.querySelector(".scroll-bar");
    var uploadFile = uploadBox.querySelectorAll("div");
    if(uploadFile) {
        var len = uploadFile.length;
        for(var i = 0; i < len; i++) {
            addDelEvent(uploadFile[i]);
        }
    }

    //文件状态初始化
    var div_len = uploadFile.length;
    for(var i = 0; i < div_len; i++) {
        var file_Name;
        if(uploadFile[i].getAttribute("data-status") == file_status[0]) {
            file_Name = uploadFile[i].querySelector("p:first-child").innerHTML;
            status_list.push(uploadFile[i]);
            newElem[file_Name] = [];
            newElem[file_Name][0] = uploadFile[i];
            newElem[file_Name][1] = uploadFile[i].getAttribute("data-md5");
        }
    }

    //文件解析
    setTimeout(function loop() { //隔2s轮询一次
        if(status_list.length) {
            var now_status = status_list[0].getAttribute("data-status");
            var file_name = status_list[0].querySelector("p:first-child").innerHTML;
            var md5 = newElem[file_name][1];
            var status = {fileMD5: md5};
            $.ajax({
                url: secret('./api/getProgess'),
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(status),
                dataType: 'json',
                success: function(data) {
                    if(data.msg == file_status[0]) {
                        //
                    }
                    else if(data.msg == file_status[1]){
                        removeC(status_list[0]);
                        status_list.splice(0,1);
                        delete newElem[file_name];
                        showError("解析失败");
                    }
                    else if(data.msg == file_status[2]) {
                        removeC(status_list[0]);
                        status_list.splice(0,1);
                        addFile(file_name);
                        delete newElem[file_name];
                        showError("解析成功");
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    showError("请求失败，请刷新重试");
                }
            });
        }
        setTimeout(loop, 2000);
    }, 2000);


    //去下单
    $(".to-order").click(function() {
        var len = document.querySelectorAll(".scroll-bar div").length - 1;
        if(status_list.length == 0 && len > 0) {
            location.href = "confirm";
        }
        else if(len == 0) {
            showError("请选择打印文件");
        }
        else {
            showError("请稍后，正在处理中...");
        }
    });
    
});