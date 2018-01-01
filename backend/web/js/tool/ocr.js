function syntaxHighlight(json) {
    if (typeof json != 'string') {
        json = JSON.stringify(json, undefined, 2);
    }
    json = json.replace(/&/g, '&').replace(/</g, '<').replace(/>/g, '>');
    return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
        var cls = 'number';
        if (/^"/.test(match)) {
            if (/:$/.test(match)) {
                cls = 'key';
            } else {
                cls = 'string';
            }
        } else if (/true|false/.test(match)) {
            cls = 'boolean';
        } else if (/null/.test(match)) {
            cls = 'null';
        }
        return '<span class="' + cls + '">' + match + '</span>';
    });
}
$(function () {
    //0.初始化fileinput
    var oFileInput = new FileInput();
    oFileInput.Init("txt_file", "/tool/ocr");
});
(function () {
    var $list = $('.select-method').children();
    var $divs = $('.select-bar').find('div');
    function tab(index) {
        for(var i = 0; i< $list.length; i++){
            $list[i].className = '';
            $divs.eq(i).hide();
        }
        $list[index].className = 'active';
        $divs.eq(index).show();
    }
    for(var i = 0 ;i < $list.length; i++){
        $list[i].index = i;
        $list[i].onclick = function () {
            tab(this.index);
        }
    }

    function paste_img(e) {
        console.log(e)
        $('#epcBox').hide();
        $('#baiduBox').hide();
        $('#epcOcr').hide();
        $('#baiduOcr').hide();
        if ( e.clipboardData && e.clipboardData.items) {
            // google-chrome
            ele = e.clipboardData.items

            console.dir(ele)
            for (var i = 0; i < ele.length; ++i) {
                if ( ele[i].kind == 'file' && ele[i].type.indexOf('image/') !== -1 ) {
                    var blob = ele[i].getAsFile();
                    console.log(blob)
                    window.URL = window.URL || window.webkitURL;
                    var blobUrl = window.URL.createObjectURL(blob);
                    console.log(blobUrl);

                    var new_img= document.createElement('img');
                    new_img.setAttribute('src', blobUrl);
                    new_img.setAttribute('blobdata', blob);
                    document.getElementById('editable').innerHTML = "<span></span>";
                    document.getElementById('editable').style.padding = 0;
                    document.getElementById('editable').appendChild(new_img);

                    var fd = new FormData();
                    fd.append("file", blob,"image.png");
                    var data;
                    $.ajax({
                        type: 'POST',
                        url: '/tool/ocr',
                        data: fd,
                        dataType:'json',
                        processData: false,
                        contentType: false
                    }).done(function (res) {
                        console.log(res);
                        if(res.success){
                            $('#baiduOcr').show();
                            var baiduJson = {0:res.result};
                            $('#baiduResult').html(syntaxHighlight(baiduJson));
                            $('#baiduBox').show().css({'height':$('#ocr_left').height(),'overflow-y':"scroll"});
                        }
                    })
                }

            }
        } else {
            alert('non-chrome');
        }
    }
    document.getElementById('editable').onpaste=function(){paste_img(event);return false;};
})();

//初始化fileinput
var FileInput = function () {
    var oFile = new Object();
    var count = 0;
    //初始化fileinput控件（第一次初始化）
    oFile.Init = function(ctrlName, uploadUrl) {
        var control = $('#' + ctrlName);

        //初始化上传控件的样式
        control.fileinput({
            language: 'zh', //设置语言
            uploadUrl: uploadUrl, //上传的地址
            allowedFileExtensions: ['jpg', 'png', 'jpeg'],//接收的文件后缀
            showUpload: true, //是否显示上传按钮
            showCaption: false,//是否显示标题
            browseClass: "btn btn-primary", //按钮样式
            //dropZoneEnabled: false,//是否显示拖拽区域
            //minImageWidth: 50, //图片的最小宽度
            //minImageHeight: 50,//图片的最小高度
            //maxImageWidth: 1000,//图片的最大宽度
            //maxImageHeight: 1000,//图片的最大高度
            //maxFileSize: 0,//单位为kb，如果为0表示不限制文件大小
            //minFileCount: 0,
            maxFileCount: 1, //表示允许同时上传的最大文件个数
            autoReplace:true,
            enctype: 'multipart/form-data',
            validateInitialCount:true,
            previewFileIcon: "<i class='glyphicon glyphicon-king'></i>",
            msgFilesTooMany: "选择上传的文件数量({n}) 超过允许的最大数值{m}！",
        }).on("fileselect", function(event, files) {
            $('div').remove('.file-preview-success');
            $('#baiduOcr').hide();
            $('#baiduResult').html();
            $('#baiduBox').hide();
        })
        //导入文件上传完成之后的事件
        $("#txt_file")
            .on('filesuccessremove',function(event,data){
                $('#baiduOcr').hide();
                $('#baiduResult').html();
                $('#baiduBox').hide();
            })
            .on('filecleared',function(event,data){
                $('#baiduOcr').hide();
                $('#baiduResult').html();
                $('#baiduBox').hide();
            })
            .on('filepreupload', function(event, data, previewId, index) {
                var ocr_type = $('input[name=ocr_type]').val();
                data.form.append('ocr_type', ocr_type)
            })
            .on("fileuploaded", function (event, data) {
            var ret = data.response;
            if(ret.success){
                $('#baiduOcr').show();
                var baiduJson = {0:ret.result};
                $('#baiduResult').html(syntaxHighlight(baiduJson));
                $('#baiduBox').show().css({'height':$('#ocr_left').height(),'overflow-y':"scroll"});
            }else{
            }
        });
    }
    return oFile;
};
