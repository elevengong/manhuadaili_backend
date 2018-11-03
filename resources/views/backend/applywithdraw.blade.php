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

    <title>添加分类</title>
    <meta name="keywords" content="添加分类">
    <meta name="description" content="添加分类">
</head>
<body>
<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/jquery.min.1.9.1.js") ?>"></script>
<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/My97DatePicker/4.8/WdatePicker.js"); ?>"></script>
<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/layer/layer.js") ?>"></script>

<script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/include/withdraw.js?ver=1.1"); ?>"></script>
<div id="frm_account" class="col-xs-12" style="text-align: center;">
    <form class="form form-horizontal" id="form1">
        {{csrf_field()}}
        <div class="col-xs-12 row cl">
            <label class="form-label col-xs-3 col-sm-3">可提款佣金金额(元)：</label>
            <div class="col-xs-9 col-sm-9">
                <input type="text" class="input-text" value="{{$daili['current_commision']}}" id="current_commision" name="current_commision" readonly="readonly" />
            </div>
        </div>
        <div class="col-xs-12 row cl">
            <label class="form-label col-xs-3 col-sm-3">支付方式：</label>
            <div class="col-xs-9 col-sm-9">
                <select name="paytype" style="float:left;" id="paytype">
                    @if(!empty($daili['alipay']))
                    <option value="0">支付宝：{{$daili['alipay']}}--收款人：{{$daili['alipay_name']}}</option>
                    @endif
                    @if(!empty($daili['bank']))
                    <option value="1">银行帐号：{{$daili['bank']}}--开户银行：{{$daili['bank_name']}}--开户银行：{{$daili['bank_accountname']}}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-xs-12 row cl">
            <label class="form-label col-xs-3 col-sm-3">申请提款的金额(元)：</label>
            <div class="col-xs-9 col-sm-9">
                <input type="text" class="input-text" value="" id="withdrawcommision" name="withdrawcommision" />
            </div>
        </div>

        <div class="col-xs-12 row cl" style="text-align: center;">
            <div class="formControls col-xs-12 col-sm-12">
                <input type="button" onclick="applywithdrawprocess()" class="btn btn-primary" value="确认提款" id="btn_add_ok" />
            </div>
        </div>

    </form>
</div>

<script>
    function applywithdrawprocess() {
        $('#btn_add_ok').attr('disabled','disabled');
        var withdrawcommision  = $.trim( $('#withdrawcommision').val() );
        var paytype  = $.trim( $('#paytype').val() );
        $.ajax({
            type:"post",
            url:"/backend/applywithdraw",
            dataType:'json',
            headers:{'X-CSRF-TOKEN':$('input[name="_token"]').val()},
            data:{'withdrawcommision':withdrawcommision,'paytype':paytype},
            success:function(data){
                if(data.status == 0)
                {
                    layer.msg( data.msg );
                    $('#btn_add_ok').removeAttr('disabled');
                }else{
                    layer.msg( data.msg ,function () {
                        window.parent.location.reload();
                        window.location.close();
                    });
                }

            },
            error:function (data) {
                layer.msg(data.msg);
            }
        });
    }
</script>



</body>
</html>
