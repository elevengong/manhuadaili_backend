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
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/bootstrap/js/bootstrap.min.js"); ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/static/h-ui/js/H-ui.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/static/h-ui.admin/js/H-ui.admin.page.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/layer/layer.js") ?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/baseCheck.js");?>"></script>
    <script type="text/javascript" src="<?php echo asset( "/resources/views/backend/js/main.js");?>"></script>
    <!--[if IE 6]>
    <!--/meta 作为公共模版分离出去-->

    <title>漫画代理系统后台</title>
</head>
<body>

@yield('content')

<!--请在下方写此页面业务相关的脚本-->
<script type="text/javascript">

</script>
<!--/请在上方写此页面业务相关的脚本-->

</body>
</html>