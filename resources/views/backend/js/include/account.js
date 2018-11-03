function editaccountprocess() {
    //$('#btn_add_ok').css('display','none');
    var alipay = $.trim( $('#alipay').val() );
    var alipay_name = $.trim( $('#alipay_name').val() );
    var bank = $.trim( $('#bank').val() );
    var bank_name = $.trim( $('#bank_name').val() );
    var bank_accountname = $.trim( $('#bank_accountname').val() );

    if(alipay != ''){
        if(alipay_name == ''){
            layer.msg( '支付宝名字不能为空！' );
            return false;
        }
    }
    if(bank != ''){
        if(bank_name == ''){
            layer.msg( '银行相关信息不能为空！' );
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
                window.location.reload();
            }else{
                layer.msg( data.msg);
            }
        },
        error:function (data) {
            layer.msg('Error!');
        }
    });
}