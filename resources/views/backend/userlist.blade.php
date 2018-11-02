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
                            <th width="50">会员情况</th>
                            <th width="50">注册IP</th>
                            <th width="50">最新登陆时间</th>
                            <th width="50">创建时间</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($users as $user)
                            <tr class="text-c">
                                <td>{{$user['user_name']}}</td>
                                <td>@if($user['vip']==0) <em style="color: green;">普通会员</em> @else <em style="color: red;">VIP会员</em>  @endif</td>
                                <td>{{$user['register_ip']}}</td>
                                <td>{{$user['last_login_time']}}</td>
                                <td>{{$user['created_at']}}</td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="ml-12" style="text-align: center;">
                    {{ $users->links() }}
                </div>


            </article>
        </div>

        <hr />

    </section>
    <script>

    </script>



@endsection