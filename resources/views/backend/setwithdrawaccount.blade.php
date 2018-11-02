@extends("backend.layout.layout")
@section("content")
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/include/account.js?ver=1.3"); ?>"></script>
    <section class="Hui-article-box">
        <div class="Hui-article">
            <form class="form form-horizontal" id="form1">
                {{csrf_field()}}
                <div class="col-xs-12 row cl">
                    <label class="form-label col-xs-3 col-sm-3">支付宝帐号：</label>
                    <input type="text" class="input-text" value="{{$daili['alipay']}}" id="alipay" name="alipay" autocomplete="off" style="width: 230px !important;"/>&nbsp;
                    支付宝姓名：<input type="text" class="input-text" value="{{$daili['alipay_name']}}" id="alipay_name" autocomplete="off" name="alipay_name" style="width: 130px !important;"/>
                </div>
                <div class="col-xs-12 row cl">
                    <label class="form-label col-xs-3 col-sm-3">微信帐号：</label>
                    <input type="text" class="input-text" value="{{$daili['weixin']}}" id="weixin" name="weixin" autocomplete="off" style="width: 230px !important;"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    微信姓名：<input type="text" class="input-text" value="{{$daili['weixin_name']}}" id="weixin_name" name="weixin_name" autocomplete="off" style="width: 130px !important;"/>
                </div>
                <div class="col-xs-12 row cl" style="text-align: center;">
                    <div class="formControls col-xs-12 col-sm-12">
                        <input type="button" onclick="editaccountprocess()" class="btn btn-primary" value="设置" id="btn_add_ok" style="margin-right: 100px;" />
                    </div>
                </div>

            </form>
        </div>

        <hr />

    </section>
    <script>

    </script>



@endsection