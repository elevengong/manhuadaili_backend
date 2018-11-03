@extends("backend.layout.layout")
@section("content")
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/include/account.js?ver=1.8"); ?>"></script>
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
                    <label class="form-label col-xs-3 col-sm-3">银行帐号：</label>
                    <input type="text" class="input-text" value="{{$daili['bank']}}" id="bank" name="bank" autocomplete="off" style="width: 230px !important;"/>&nbsp;&nbsp;&nbsp;&nbsp;
                    开户银行：<input type="text" class="input-text" value="{{$daili['bank_name']}}" id="bank_name" name="bank_name" autocomplete="off" style="width: 130px !important;"/>
                    开户人：<input type="text" class="input-text" value="{{$daili['bank_accountname']}}" id="bank_accountname" name="bank_accountname" autocomplete="off" style="width: 130px !important;"/>
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