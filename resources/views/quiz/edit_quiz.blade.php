@extends('layouts.layout')

@section('content')

<div class="container introduce-bg2">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
              <div class="card-header">新增題庫</div>
                <div class="card-body">

                    <form method="POST" action="{{ Route('quizs.change') }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" value="{{$quiz->id}}" name="id"/>
                        {{-- 題庫名稱欄位 --}}
                        <div class="form-group row">
                            <label for="test_name" class="col-md-4 col-form-label text-md-right">題庫名稱</label>

                            <div class="col-md-6">
                                <input id="test_name" type="text" class="form-control{{ $errors->has('test_name') ? ' is-invalid' : '' }}" name="test_name" value="{{ $quiz->test_name }}" required autofocus>

                                @if ($errors->has('test_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('test_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 測驗時間 --}}
                        <div class="form-group row">
                            <label for="test_time" class="col-md-4 col-form-label text-md-right">測驗時間</label>

                            <div class="col-md-6">
                                <input id="test_time" type="number" class="form-control{{ $errors->has('test_time') ? ' is-invalid' : '' }}" name="test_time" value="{{ $quiz->test_time }}" required>

                                @if ($errors->has('test_time'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('test_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- 測驗說明欄位 --}}
                        {{-- <div class="form-group row">
                            <label for="school" class="col-md-4 col-form-label text-md-right">說明</label>

                            <div class="col-md-6">
                                <input id="school" type="text" class="form-control{{ $errors->has('school') ? ' is-invalid' : '' }}" name="school" value="{{ old('school') }}">

                                @if ($errors->has('school'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('school') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group row mb-0">
                            <div class="col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ Route('quizs') }}" class="btn btn-danger">
                                    {{ __('Back') }}
                                </a>
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
