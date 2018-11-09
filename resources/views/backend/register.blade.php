<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link href="<?php echo asset( "/resources/views/backend/static/h-ui/css/H-ui.min.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/h-ui.admin/css/H-ui.login.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/h-ui.admin/css/style.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/Hui-iconfont/1.0.8/iconfont.css") ?>" rel="stylesheet" type="text/css" />

    <title>漫画代理注册</title>
    <meta name="keywords" content="漫画代理注册">
    <meta name="description" content="漫画代理注册">
    <style>
        .loginBox {
            height: 355px !important;
            margin-left: -309px !important;
            margin-top: -192px !important;
            padding-top: 13px !important;
        }

    </style>
</head>
<body>
<div class="header1"></div>
<div class="loginWraper">
    <div id="loginform" class="loginBox">
        <form class="form form-horizontal" action="#" method="post">
            <div style="text-align: center;font-size: 16px;"><a href="/">登陆</a>&nbsp;|&nbsp;注册</div>
            {{csrf_field()}}
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60d;</i></label>
                <div class="formControls col-xs-8">
                    <input id="name" name="name" type="text" placeholder="账户" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                <div class="formControls col-xs-8">
                    <input id="pwd" name="pwd" type="password" placeholder="密码" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <label class="form-label col-xs-3"><i class="Hui-iconfont">&#xe60e;</i></label>
                <div class="formControls col-xs-8">
                    <input id="repwd" name="repwd" type="password" placeholder="重复密码" class="input-text size-L">
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input class="input-text size-L" type="text" id="code" name="code" placeholder="验证码" style="width:150px;">
                    <img src="/backend/code" onclick="javascript:this.src='/backend/code?'+Math.random()" style="width: 110px; height: 40px; cursor: pointer;">
                </div>
            </div>
            <div class="row cl">
                <div class="formControls col-xs-8 col-xs-offset-3">
                    <input id="btn_login" name="btn_login" type="button" class="btn btn-success radius size-L" value="&nbsp;注&nbsp;&nbsp;&nbsp;&nbsp;册&nbsp;">
                    <input id="btn_clear" name="btn_clear" type="reset" class="btn btn-default radius size-L" value="&nbsp;取&nbsp;&nbsp;&nbsp;&nbsp;消&nbsp;">
                </div>
            </div>
        </form>
    </div>
</div>
<div class="footer">Copyright myweb</div>

<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/jquery.min.1.9.1.js") ?>"></script>
<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/static/h-ui/js/H-ui.js") ?>"></script>
<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/layer/layer.js") ?>"></script>
<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/baseCheck.js");?>"></script>
<script>
    $(function(){
        $("#btn_login").click( function() {
            var name = $("#name").val();
            var pwd = $("#pwd").val();
            var repwd = $("#repwd").val();
            var code = $("#code").val();

            if (!isUname_(name) || !( name.length >= 5 && name.length <= 20 )) {
                layer.msg("请输入字母、数字、下划线组成的6-20位的用户名");
                $('#name').focus();
                return false;
            }

            if (!isUname(pwd) || !( pwd.length >= 6 && pwd.length <= 20 )) {
                layer.msg("请输入字母、数字组成的6-20位的密码");
                $('#pwd').focus();
                return false;
            }

            if (repwd != pwd){
                layer.msg("密码不相同");
                $('#pwd').focus();
                return false;
            }

            if( code.length != 4 ){
                layer.msg("验证码只能是4位数");
                $('#code').focus();
                return false;
            }
            var datas = { name: name, pwd: pwd, code: code, repwd: repwd};
            var msg = '';
            var id = '';

            $.ajax({
                type:"post",
                url:"/register",
                dataType:'json',
                headers:{'X-CSRF-TOKEN':$('input[name="_token"]').val()},
                data:datas,
                success:function(data){
                    if(data.status == 0)
                    {
                        layer.msg( data.msg );
                        return false;

                    }else{
                        layer.msg( data.msg ,function () {
                            window.location.href = "/backend/index";
                        });
                    }

                    return false;
                },
                error:function (data) {
                    return false;

                }

            });

        } );
    });

</script>
</body>
</html>