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
                            <th width="50">ID</th>
                            <th width="50">IP</th>
                            <th width="150">来路</th>
                            <th width="50">地区</th>
                            <th width="70">创建时间</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($statistics as $data)
                            <tr class="text-c">
                                <td>{{$data['id']}}</td>
                                <td>{{$data['ip']}}</td>
                                <td>{{$data['coming_url']}}</td>
                                <td>{{$data['area']}}</td>
                                <td>{{$data['created_at']}}</td>

                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>

                <div class="ml-12" style="text-align: center;">
                    {{ $statistics->links() }}
                </div>


            </article>
        </div>

        <hr />

    </section>
    <script>

    </script>



@endsection