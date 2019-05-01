@extends('layouts.layout')

@section('content')
<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('團體註冊帳號') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('groupregister') }}">
                        @csrf

                        {{-- 錯誤訊息 --}}
                        @if ($errors->has('message'))
                        <div class="alert alert-danger" role="alert">
                            {{ $errors->first('message') }}
                        </div>
                        @endif

                        {{-- 學校名稱欄位 --}}
                        <div class="form-group row">
                            <label for="school" class="col-md-4 col-form-label text-md-right">{{ __('學校名稱') }}</label>
                            <div class="col-md-6">
                                <input type="text" id="school" placeholder="XX學校" class="form-control{{ $errors->has('school') ? ' is-invalid' : '' }}" name = "school" required autofocus value="{{ old('school') }}"/>

                                @if ($errors->has('school'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 使用者(學生)團體資料欄位 --}}
                        <div class="form-group row">
                            <label for="groupdata" class="col-md-4 col-form-label text-md-right">{{ __('使用者資料') }}</label>
                            <div class="col-md-6">
                                <textarea id="groupdata" rows="20" cols="50"
                                  placeholder="學生學號1,學生名稱1&#x0a;學生學號2,學生名稱2&#x0a;學生學號3,學生名稱3&#x0a;..."
                                  class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                  name = "groupdata" required>{{ old('groupdata') }}</textarea>

                                @if ($errors->has('groupdata'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('groupdata') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 團體註冊按鈕 --}}
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('團體註冊') }}
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
