@extends("backend.layout.layout")
@section("content")
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/include/withdraw.js?ver=1.0"); ?>"></script>
    <section class="Hui-article-box">
        <div class="Hui-article">
            <article class="cl pd-20">
                <div class="mt-20">
                    {{csrf_field()}}
                    <table class="table table-border table-bordered table-hover table-bg table-sort">
                        <thead>
                        <tr class="text-c">
                            <th width="">代理名</th>
                            <th width="">总可用佣金金额</th>
                            <th width="">冻结佣金金额</th>
                            <th width="">今天佣金总额({{$date}})</th>
                            <th width="">佣金比例</th>
                            <th width="">推广链接</th>
                            <th width="">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="text-c">
                        <td style="text-align: center;">{{$dailiInfo['daili_name']}}</td>
                        <td style="text-align: center;">{{$dailiInfo['current_commision']}}元</td>
                        <td style="text-align: center;">{{$dailiInfo['frzon_commision']}}元</td>
                        <td style="text-align: center;">{{$todaycommision}}元</td>
                        <td style="text-align: center;">{{$dailiInfo['commission_rate']*100}}%</td>
                        <td style="text-align: center;"><a target="_blank" href="{{$staticArray[5]}}daili/{{$dailiInfo['daili_id']}}">{{$staticArray[5]}}daili/{{$dailiInfo['daili_id']}}</a></td>
                        <td style="text-align: center;">
                            <input type="button" onclick="setaccount();" class="btn btn-primary  radius" value="设置提款信息">
                            <input type="button" onclick="applywithdraw();" class="btn btn-primary  radius" value="申请提款">
                        </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </article>

        </div>

    </section>
    <script>

    </script>



@endsection