var path = "";
$(function () {

    path = $("#hid_path").val();


    if( $("#btn_ip").length > 0 ){
        $("#btn_ip").click(function(){
            layer.prompt( {title: '请输入新增IP', formType: 0}, function(ip, index){
                if( !isIP(ip) ){
                    layer.msg( "IP格式不正确" );
                    layer.close(index);
                    return;
                }

                post( path + "auth/addip", { newip: ip }, function(data){
                    if( data > 0 ){
                        layer.msg( "新增成功" );
                        window.location.reload();
                    }else{
                        layer.msg( "新增错误" );
                    }
                } );
            });
        });
    }

});

function edit_ip(tid){
    $("#hid_tid").val(tid);

    $.post( path + "auth/getip/" + tid, function(data){
        data = $.parseJSON( data );
        if( data.length == 1 ){
            data = data[0];
            layer.prompt( { title: "请输入修改的IP", value: data.whiteip, formType: 0 }, function( ip, index ){
                if( !isIP(ip) ){
                    layer.msg( "IP格式不正确" );
                    layer.close(index);
                    return;
                }

                post( path + "auth/addip/", { newip: ip, tid: tid }, function(data){
                    if( data > 0 ){
                        layer.msg( "编辑成功" );
                        layer.close(index);
                    }else{
                        layer.msg( "编辑错误" );
                        layer.close(index);
                    }
                } );
            });
        }else {
            layer.msg("准备修改的IP不存在");
            layer.close(index);
        }
    })
}

function del_ip(tid){
    layer.confirm( "是否真的删除记录", function( data ){
        get( path + "auth/delip/" + tid, function(data){
            if( data > 0 ){
                layer.msg( "删除成功" );
                window.location.reload();
            }else{
                layer.msg( "删除错误" );
            }
        } );
    });
}


