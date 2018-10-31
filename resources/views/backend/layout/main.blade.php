<!--_meta 作为公共模版分离出去-->

<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />

    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="Bookmark" href="favicon.ico" >
    <link rel="Shortcut Icon" href="favicon.ico" />

    <![endif]-->
    <link href="<?php echo asset( "/resources/views/backend/js/bootstrap/css/bootstrap.min.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/h-ui/css/H-ui.min.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/h-ui.admin/css/H-ui.admin.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/Hui-iconfont/1.0.8/iconfont.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/h-ui.admin/skin/green/skin.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/static/h-ui.admin/css/style.css") ?>" rel="stylesheet" type="text/css" />
    <link href="<?php echo asset( "/resources/views/backend/css/main.css") ?>" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/jquery.min.1.9.1.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/static/h-ui/js/H-ui.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/static/h-ui.admin/js/H-ui.admin.page.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/layer/layer.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/baseCheck.js");?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/main.js?ver=1.0");?>"></script>
    <!--[if IE 6]>
    <!--/meta 作为公共模版分离出去-->

    <title>代理系统后台</title>
</head>
<body>
<!--_header 作为公共模版分离出去-->
<header class="navbar-wrapper">
    <div class="navbar navbar-fixed-top">
        <div class="container-fluid cl">
            <a class="logo navbar-logo f-l mr-10 hidden-xs" href="#">代理系统后台</a>
            <span class="logo navbar-slogan f-l mr-10 hidden-xs">v3.0</span>
            <a aria-hidden="false" class="nav-toggle Hui-iconfont visible-xs" href="javascript:;">&#xe667;</a>

            <nav id="Hui-userbar" class="nav navbar-nav navbar-userbar hidden-xs">
                <ul class="cl">
                    <li>代理系统后台</li>
                    <li class="dropDown dropDown_hover">
                        <a id="admin" href="#" class="dropDown_A">
                            {{$daili_name}}<i class="Hui-iconfont">&#xe6d5;</i>
                        </a>
                        <ul class="dropDown-menu menu radius box-shadow">
                            <li><a href="javascript:uppwd('{{csrf_token()}}');">修改密码</a></li>
                            <li><a href="javascript:logout('{{csrf_token()}}');">退出</a></li>
                        </ul>
                    </li>
                </ul>
            </nav>

        </div>
    </div>
</header>
<!--/_header 作为公共模版分离出去-->

<!--_menu 作为公共模版分离出去-->
<aside class="Hui-aside">

    <div class="menu_dropdown bk_2">
        <dl id="menu-article">
            <dt>
                <i class="Hui-iconfont">&#xe616;</i>后台管理
                <i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
            </dt>

            <dd style="display: none">
                <ul>
                    <li><a data-href="{{url('/backend/withdrawlist')}}" href="javascript:void(0)" onclick="clicklink(this)" title="提款订单">提款订单列表</a></li>
                </ul>
            </dd>
            <dt>
                <i class="Hui-iconfont">&#xe616;</i> 来路流量分析
                <i class="Hui-iconfont menu_dropdown-arrow">&#xe6d5;</i>
            </dt>

            <dd style="display: none">
                <ul>
                    <li><a data-href="{{url('/backend/traffic')}}" href="javascript:void(0)" onclick="clicklink(this)" title="流量分析">流量分析</a></li>
                </ul>
            </dd>




        </dl>
    </div>
</aside>
<div class="dislpayArrow hidden-xs"><a class="pngfix" href="javascript:void(0);" onClick="displaynavbar(this)"></a></div>
<!--/_menu 作为公共模版分离出去-->


<iframe id="frame" scrolling="yes" name="mainFrame" frameborder="0" src="" style="width:100%;height:92%;position:absolute;"></iframe>


<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript">

    function clicklink(obj) {
        var href = $(obj).attr('data-href');
        $('iframe').attr('src',href);
    }

    window.onload=function(){
        $('#frame').attr('src','{{url('/backend/showindex')}}');
    }
</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>