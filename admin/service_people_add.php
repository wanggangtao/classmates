<?php
/**
 * Created by PhpStorm.
 * User: hhx
 * Date: 2019/3/20
 * Time: 17:07
 */
require_once('admin_init.php');
require_once('admincheck.php');
/*$brand=Dict::getListByParentid(1);*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="author" content="芝麻开花 http://www.zhimawork.com" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    <link rel="stylesheet" href="css/form.css" type="text/css" />
    <script type="text/javascript" src="js/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" src="js/layer/layer.js"></script>
    <script type="text/javascript" src="js/common.js"></script>
    <script type="text/javascript" src="js/upload.js"></script>
    <script type="text/javascript" src="laydate/laydate.js"></script>
    <script type="text/javascript" src="ckeditor/ckeditor.js"></script>
    <script type="text/javascript">
        /*window.onload = function()
         {
         //CKEDITOR.replace( 'content');

         };*/
        $(function(){
            var ckeditor = CKEDITOR.replace('content', {
                toolbar: 'Common',
                forcePasteAsPlainText: 'true',//强制纯文本格式粘贴
                tabSpaces: 1,
                filebrowserImageUploadUrl: 'ckeditor_upload.php?type=img',
                filebrowserUploadUrl: 'ckeditor_upload.php?type=file'
            });

            $('.fileinput').on('change', 'input[type=file]', function(){
                $('.fileloading').html(' 上传中...');
                ajaxFileUpload();
                return false;
            });
            function ajaxFileUpload() {
                var uploadUrl = 'service_people_do.php?act=upload';//处理文件
                var index = layer.load(2, {shade: false});
                $.ajaxFileUpload({
                    url: uploadUrl,
                    fileElementId: 'file',//文件上传控件的id属性为‘pic’
                    dataType: 'json',
                    success: function (data, sex) {
                        var code = data.code;
                        var msg = data.msg;

                        /*$('#picture').val('');
                         $('.fileloading').html('');*/

                        switch (code) {
                            case 1:
                                layer.close(index);
                                $('#upimg0').attr('src', '<?php echo $HTTP_PATH;?>' + msg);
                                $("#pictureid").val(msg);
                                // $("#input").val(msg);
                                layer.msg('上传成功');
                                break;
                            default:
                                layer.close(index);
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error: function (data, sex, e) {
                        layer.alert(e);
                    }
                });
                return false;
            }




            $("#btn_submit").click(function(){

                var title = $("#name").val();
                var picurl =$("#pictureid").val();
                var content   = ckeditor.getData();

                var titlength = $("#name").val().length;
                //var contlength = $("#content").val().length;
                if(titlength == 0){
                    layer.tips('标题不能为空', '#name');
                    return false;
                }
                if (content == '') {
                    layer.tips('内容不能为空', '#content');
                    return false;
                }
                if (picurl == '') {
                    layer.tips('图片不能为空', '#upimg0');
                    return false;
                }
                $(this).unbind();

                $.ajax({
                    type: 'post',
                    url: 'service_people_do.php?act=add',
                    data: {
                        name : title,
                        content : content,
                        img : picurl

                    },
                    dataType: 'json',
                    success: function (data) {
                        var code=data.code;
                        var msg=data.msg;
                        switch (code){
                            case 1:
                                layer.alert(msg, {icon: 6}, function(index){
                                    parent.location.reload();
                                });
                                break;
                            default:
                                layer.alert(msg, {icon: 5});
                        }
                    },
                    error: function () {
                        alert("请求失败");
                    }
                });
            });
        });

    </script>
</head>
<body>
<div id="formlist">
    <br><br><br>
    <p>
        <label><font color="#dc143c">*</font>标题</label>
        <input type="text" class="text-input input-length-30" name="name" id="name"/>
    </p>
    <p>
        <label><font color="#dc143c">*</font>内容</label>
    <div style="margin-top:0px;margin-left:16%;margin-right:16%">
        <textarea style="padding:150px;width:30%;height:70px;"  cols="100" id="content"></textarea>

    </div>
    </p>
    <p>
        <label><font color="#dc143c">*</font>封面图</label>
    <div style="padding-left:150px;" id="input">
        <img id="upimg0"  src="" width="150px;"/>
        <div style="margin-top:0px;margin-left:0%">
            <span class="fileinput">
                <input type="file" name="file" id="file" value="" />
            </span>
            <!--<span>*图片为列表缩略图，建议图片尺寸小于300*200</span>-->
            <div style="margin-top:10px;color:grey;">
                建议上传尺寸300*200
            </div>
        </div>
    </div>
    </p>



    <p align="center">
        <label> </label>
        <input type="submit" id="btn_submit" class="btn_submit" value="提　交" />
        <input type="hidden" id="pictureid" value=""/>
    </p>
</div>
</body>
</html>
