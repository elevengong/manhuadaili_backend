$(function(){
    var close_index;
    var path = $("#path").val();
    $(document).ajaxStart(function () {
        close_index = layer.load(1, {
            shade: [0.1, '#fff'] //0.1透明度的白色背景
        });
    });
    $(document).ajaxStop(function () {
        layer.close(close_index);
    });

    $("#btn_login").click( function(){
        var name = $("#name").val();
        var pwd = $("#pwd").val();
        var code = $("#code").val();
//        var token = $("._token").val();

        if( !isUname_(name) || !( name.length >= 5 && name.length <= 20 ) ){
            layer.msg("请输入字母、数字、下划线组成的5-20位的用户名");
            $('#name').focus();
            return false;
        }

        if( !isUname(pwd) || !( pwd.length >= 5 && pwd.length <= 20 ) ){
            layer.msg("请输入字母、数字组成的5-20位的密码");
            $('#pwd').focus();
            return false;
        }

        if( code.length != 4 ){
            layer.msg("验证码只能是4位数");
            $('#code').focus();
            return false;
        }
        var data = { name: name, upwd: pwd, code: code};

        $.ajax({
            type:"post",
            url:"/backen/login",
            dataType:'json',
            headers:{'X-CSRF-TOKEN':'{{csrf_token()}}'},
            data:data,
            success:function(data){
                layer.msg( '11' );

            },
            error:function (data) {
                layer.msg( '22' );

            }

        });



        // post( path + "backend/login", data, function(result){
        //     var msg = "";
        //     var id = "";
        //     if( result == 1 ) {
        //         msg = "登陆成功，请等待跳转";
        //     }else if( result == -1 ){
        //         msg = "请输入字母、数字、下划线组成的5-20位的用户名";
        //         id = '#txt_uname';
        //     }else if( result == -2 ){
        //         msg = "请输入字母、数字组成的5-20位的密码";
        //         id = '#txt_upwd';
        //     }else if( result == -3 ){
        //         msg = "验证码只能是4位数";
        //         id = '#txt_yzm';
        //     }else if( result == -4 ){
        //         msg = "验证码输入不正确";
        //         id = '#txt_yzm';
        //     }else if( result == -5 ){
        //         msg = "此用户不存在";
        //         id = '#txt_uname';
        //     }else if( result == -6 ){
        //         msg = "用户密码不正确，请重试";
        //         id = '#txt_upwd';
        //     }else if( result == -7 ){
        //         msg = "用户已经被锁定";
        //         id = '#txt_upwd';
        //     }
        //
        //     layer.msg( msg );
        //     $(id).focus();
        //     if( result == 1 ){
        //         window.location.href = path + "main";
        //     }
        //     return false;
        // });
    } );
});