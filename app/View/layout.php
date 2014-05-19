<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title><?= $pageTitle.' - '.$siteName ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="/static/css/bootstrap.min.css">
<link rel="stylesheet" href="/static/css/jasny-bootstrap.min.css">
<link rel="stylesheet" href="/static/css/main.css">
</head>
<body>
<a class="sr-only" href="#content">Skip to main content</a>
<header class="navbar navbar-inverse navbar-fixed-top hs-nav" role="banner">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".hs-navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
                <span class="icon-bar"></span> 
            </button>
            <a href="/" class="navbar-brand"><?= $siteName ?></a>
        </div>
        <nav class="collapse navbar-collapse hs-navbar-collapse" role="navigation">
            <ul class="nav navbar-nav">
                <li class="active"><a href="/">首页</a></li>
                <li><a href="/password/xiaomi">小米</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="/signin">登录</a></li> 
                <li><a href="/signup">注册</a></li> 
            </ul>
        </nav>
    </div> 
</header>
<?= $bodyContent ?>
<footer role="contentinfo">
    <div class="container">
        <ul class="footer-links">
            <li>当前呈现版本: <?= $version ?></li>
            <li class="muted">&middot;</li>
            <li><a href="/page/about">关于我们</a></li>
            <li class="muted">&middot;</li>
            <li><a href="/page/donation">捐助我们</a></li>
            <li class="muted">&middot;</li>
            <li><a href="/password/md5s">解密列表</a></li>
        </ul>    
    </div>
</footer>
<script src="/static/js/jquery-2.1.1.min.js"></script>
<script src="/static/js/bootstrap.min.js"></script>
<script src="/static/js/jasny-bootstrap.min.js"></script>
<script src="/static/js/main.js"></script>
</body>
</html>