<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="/image/favicon.png">
        <title>InduLab</title>

        <link href="{{ mix('css/site.css') }}" rel="stylesheet">

    </head>

    <body>
        <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #008cba;">
            <a class="navbar-brand" href="/">InduLab</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/">開始實驗</a>
                    </li>
                    <li class="nav-item active">
                      <a class="nav-link" href="/slope">斜坡運動</a>
                    </li>
                    <li class="nav-item active">
                      <a class="nav-link" href="/draw-data">繪製圖表</a>
                    </li>
                </ul>
            </div>

        </nav>


        <div class="container">
            @yield('content')
        </div>

        <script src="{{ mix('/js/manifest.js') }}"></script>
        <script src="{{ mix('/js/vendor.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        @yield('script')
    </body>
</html>
