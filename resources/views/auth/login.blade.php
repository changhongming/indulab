@extends('layouts.layout')

@section('content')
<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('登入') }}</div>
                <div class="card-body">

                    {{-- 回傳訊息 --}}
                    @if ($message = Session::get('message'))
                      <div class="row alert alert-{{ Session::has('alert-type') ? Session::get('alert-type') : danger }} alert-dismissible fade show">
                        {{ $message }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- 學號或信箱欄位 --}}
                        <div class="form-group row">
                            <label for="login" class="col-sm-4 col-form-label text-md-right">{{ __('學號或電子信箱') }}</label>

                            <div class="col-md-6">
                                <input id="login" type="text" class="form-control{{ $errors->has('login') ? ' is-invalid' : '' }}" name="login" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('login'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 密碼欄位 --}}
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密碼') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 記住登入狀態欄位 --}}
                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('記住登入狀態') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- 登入按鈕 --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('登入') }}
                                </button>

                                {{-- <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
