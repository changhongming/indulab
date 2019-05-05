@extends('layouts.layout')

@section('content')

<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
              <div class="card-header">{{ __('Create User') }}</div>
                <div class="card-body">

                    <form method="POST" action="{{ Route('users.store') }}">
                        @csrf

                        {{-- 使用者名稱欄位 --}}
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('User Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 學號欄位 --}}
                        <div class="form-group row">
                            <label for="student_id" class="col-md-4 col-form-label text-md-right">{{ __('學號(帳號)') }}</label>

                            <div class="col-md-6">
                                <input id="student_id" type="text" class="form-control{{ $errors->has('student_id') ? ' is-invalid' : '' }}" name="student_id" value="{{ old('student_id') }}" required>

                                @if ($errors->has('student_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('student_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 學校名稱欄位 --}}
                        <div class="form-group row">
                            <label for="school" class="col-md-4 col-form-label text-md-right">{{ __('School') }}</label>

                            <div class="col-md-6">
                                <input id="school" type="text" class="form-control{{ $errors->has('school') ? ' is-invalid' : '' }}" name="school" value="{{ old('school') }}" required>

                                @if ($errors->has('school'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 電子信箱欄位 --}}
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 密碼欄位 --}}
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>


                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                              <div class="form-check">
                                <input id="is_admin" class="form-check-input" type="checkbox" name="is_admin"  {{ old('is_admin') ? 'checked' : '' }}>
                                <label for="is_admin" class="form-check-label">{{ __('Admin') }}</label>
                            </div>
                        </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Create') }}
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
