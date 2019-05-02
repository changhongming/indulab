<!DOCTYPE html>
<html lang="zh-Hant-TW">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link rel="icon" href="/image/favicon.png">
        <title>InduLab</title>

        <link href="{{ mix('css/site.css') }}" rel="stylesheet">
        {{-- 新增fontawsome css --}}
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/solid.css" integrity="sha384-+0VIRx+yz1WBcCTXBkVQYIBVNEFH1eP6Zknm16roZCyeNg2maWEpk/l/KsyFKs7G" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/fontawesome.css" integrity="sha384-jLuaxTTBR42U2qJ/pm4JRouHkEDHkVqH0T1nyQXn1mZ7Snycpf6Rl25VBNthU4z0" crossorigin="anonymous">
    </head>

    <body>
        <nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #008cba;">
            <a class="navbar-brand" href="/">InduLab</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="nav navbar-nav mr-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="/import">開始實驗</a>
                    </li>
                    <li class="nav-item active">
                      <a class="nav-link" href="/slope">斜坡運動</a>
                    </li>
                    <li class="nav-item active">
                      <a class="nav-link" href="/draw-data">繪製圖表</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    @guest
                      @if (Session::has('name'))
                      <a id="navbarDropdown" class="nav-link dropdown-toggle active" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                          {{Session::get('name')}},您好! <span class="caret"></span>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                      <li class="nav-item active">
                          <a class="dropdown-item" href="{{ route('logout') }}"
                          onclick="event.preventDefault();
                                        document.getElementById('logout-form').submit();">
                          {{ __('登出') }}
                          </a>
                       <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                           @csrf
                       </form>
                      </div>
                      </li>
                      @else
                      <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('登入') }}</a>
                      </li>
                      @endif
                    @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle active" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">

                            {{-- 如果為管理員，出現額外設定欄位 --}}
                            @if (!!Auth::user()->is_admin)
                            <a class="dropdown-item" href="{{ route('groupregister') }}">
                                <i class="fas fa-users-cog"></i>  {{ __('團體註冊') }}
                            </a>
                            <div class="dropdown-divider"></div>
                            @endif

                            {{-- 通用使用者欄位 --}}
                            <a class="dropdown-item" href="{{ route('changepassword') }}">
                              <i class="fas fa-key"></i>{{ __('修改密碼')}}
                            </a>

                            <div class="dropdown-divider"></div>

                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                               <i class="fas fa-sign-out-alt"></i>  {{ __('登出') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
                </ul>
            </div>
        </nav>


        <div class="container">
            @yield('content')
        </div>
        @yield('full-content')
        <script src="{{ mix('/js/manifest.js') }}"></script>
        <script src="{{ mix('/js/vendor.js') }}"></script>
        <script src="{{ mix('/js/app.js') }}"></script>
        @yield('script')
    </body>
</html>
