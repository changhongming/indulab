@extends('layouts.layout')

@section('content')
<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('修改密碼') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('changepassword') }}">
                        @csrf
                        @method('PUT')

                        {{-- 成功訊息 --}}
                        @if (Session::has('success'))
                        <div class="alert alert-success" role="alert">
                            {{ Session::get('success') }}
                        </div>
                        @endif

                        {{-- 目前密碼欄位 --}}
                        <div class="form-group row">
                            <label for="current_password" class="col-md-4 col-form-label text-md-right">{{ __('當前密碼') }}</label>
                            <div class="col-md-6">
                                <input type="password" id="current_password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}" name = "current_password" required autofocus value="{{ old('current_password') }}"/>

                                @if ($errors->has('current_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('current_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 新密碼欄位 --}}
                        <div class="form-group row">
                            <label for="new_password" class="col-md-4 col-form-label text-md-right">{{ __('新的密碼') }}</label>
                            <div class="col-md-6">
                                <input type="password" id="new_password" class="form-control{{ $errors->has('new_password') ? ' is-invalid' : '' }}" name = "new_password" required value="{{ old('new_password') }}"/>

                                @if ($errors->has('new_password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 確認新密碼欄位 --}}
                        <div class="form-group row">
                            <label for="new_password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('確認新密碼') }}</label>
                            <div class="col-md-6">
                                <input type="password" id="new_password_confirmation" class="form-control{{ $errors->has('new_password_confirmation') ? ' is-invalid' : '' }}" name = "new_password_confirmation" required value="{{ old('new_password_confirmation') }}"/>

                                @if ($errors->has('new_password_confirmation'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('new_password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 修改密碼按鈕 --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('修改密碼') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
