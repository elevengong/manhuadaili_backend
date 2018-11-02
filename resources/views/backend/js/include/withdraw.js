function cancelorder(withdraw_id) {
    layer.confirm("是否取消该订单？", function() {
        $('#cancelorder').css('display','none');
        $.ajax({
            type: "delete",
            url: "/backend/cancelwithdraw/" + withdraw_id,
            dataType: 'json',
            headers: {'X-CSRF-TOKEN': $('input[name="_token"]').val()},
            success: function (data) {
                if (data.status == 0) {
                    layer.msg(data.msg);
                    $('#cancelorder').css('display','block');
                } else {
                    layer.msg( data.msg ,function () {
                        window.location.reload();
                    });
                }
                return false;
            },
            error: function (data) {
                layer.msg(data.msg);
                return false;
            }
        });
    });
}

function applywithdraw() {
    $.ajax({
        type:"post",
        url:"/backend/withdraw",
        dataType:'json',
        headers:{'X-CSRF-TOKEN':$('input[name="_token"]').val()},
        success:function(data){
            if(data.status == 0)
            {
                layer.msg( data.msg );
            }else{
                var index = layer.open({
                    type: 2,
                    title: "申请提款",
                    closeBtn: 0,
                    area: ['800px', '400px'], //宽高
                    shadeClose: true,
                    resize:false,
                    content: '/backend/applywithdraw'
                });
            }
        },
        error:function (data) {
            layer.msg('Error!');
        }
    });
}

function setaccount() {
    window.location.href ='/backend/setwithdrawaccount';
}