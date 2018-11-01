@extends("backend.layout.layout")
@section("content")
    <section class="Hui-article-box">
        <div class="Hui-article">
            <input type="hidden" id="hid_tid" value="0" />
            <article class="cl pd-20">
                <div class="mt-20">
                    <table class="table table-border table-bordered table-hover table-bg table-sort">
                        <thead>
                        <tr class="text-c">
                            <th width="40">用户名</th>
                            <th width="50">金额</th>
                            <th width="50">订单号</th>
                            <th width="50">购买选项</th>
                            <th width="50">交易单号</th>
                            <th width="50">付款类型</th>
                            <th width="50">付款名字</th>
                            <th width="50">IP</th>
                            <th width="50">status</th>
                            <th width="50">是否已经结算给代理</th>
                            <th width="50">处理时间</th>
                            <th width="50">创建时间</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($orders as $data)
                            <tr class="text-c">
                                <td>{{$data['user_name']}}</td>
                                <td>{{$data['order_money']}}</td>
                                <td>{{$data['order_no']}}</td>
                                <td>{{$newType[$data['order_type']]}}</td>
                                <td>{{$data['transfer_no']}}</td>
                                <td>@if($data['pay_type']==1) 支付宝 @else 微信 @endif</td>
                                <td>{{$data['pay_name']}}</td>
                                <td>{{$data['ip']}}</td>
                                <td>@if($data['status']==0) <em style="color: green;">等待付款</em> @else <em style="color: red;">已付款</em>  @endif</td>
                                <td>@if($data['pay_daili']==0) 未结算 @else 已结算 @endif</td>
                                <td>{{$data['deal_time']}}</td>
                                <td>{{$data['created_at']}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="ml-12" style="text-align: center;">
                    {{ $orders->links() }}
                </div>


            </article>
        </div>

        <hr />

    </section>
    <script>

    </script>



@endsection