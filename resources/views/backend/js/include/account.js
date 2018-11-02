function editaccountprocess() {
    //$('#btn_add_ok').css('display','none');
    var alipay = $.trim( $('#alipay').val() );
    var alipay_name = $.trim( $('#alipay_name').val() );
    var weixin = $.trim( $('#weixin').val() );
    var weixin_name = $.trim( $('#weixin_name').val() );

    if(alipay != ''){
        if(alipay_name == ''){
            layer.msg( '支付宝名字不能为空！' );
            return false;
        }
    }
    if(weixin != ''){
        if(weixin_name == ''){
            layer.msg( '微信名字不能为空！' );
            return false;
        }
    }

    $.ajax({
        type:"post",
        url:"/backend/setwithdrawaccount",
        dataType:'json',
        headers:{'X-CSRF-TOKEN':$('input[name="_token"]').val()},
        data:$("#form1").serialize(),
        success:function(data){
            if(data.status == 0)
            {
                layer.msg( data.msg );
            }else{
                layer.msg( data.msg);
            }
        },
        error:function (data) {
            layer.msg('Error!');
        }
    });
}