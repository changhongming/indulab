<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/image/favicon.ico">
        <title>InduLab</title>
        <!-- Bootstrap core CSS -->
        <link href="{{ asset('css/site.css') }}" rel="stylesheet">
        <!--<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">-->
		<script src="/js/manifest.js"></script>
		<script src="/js/vendor.js"></script>
		<script src="/js/app.js"></script>
		<!--<script type="text/javascript" src="js/angular.min.js"></script>
        <script type="text/javascript" src="js/script.js"></script>
        <script type="text/javascript" src="js/math.min.js"></script>
        <script type="text/javascript" src="js/plotly-latest.min.js"></script>
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script type="text/javascript" src="js/jquery.csv.js"></script>-->
    </head>
    <body>
    <div class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">InduLab</a>
            </div>
            <div class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <!-- 上方的連結 -->
                    <li class="active"><a href="/">開始實驗</a></li>
                    <li><a href="httP://www.yuntech.edu.tw" target="_top">示範影片</a></li>
                </ul>
            </div><!--/.nav-collapse -->
        </div>
    </div>
    <div class="container">
        @yield('content')
    </div><!-- /.container -->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <!-- Latest compiled and minified JavaScript -->
	<!--<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>-->
    </body>
</html>
