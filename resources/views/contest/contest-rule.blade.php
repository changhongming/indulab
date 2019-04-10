@extends('layouts.layout')

@section('content')
<div class="introduce-bg2 introduce-mid">
  <h2>InduLab 數學建模系統</h2>
  {{--  如果有錯誤訊息則印出錯誤訊息  --}}
  @if (Session::has('error-msg'))
    <p class="alert alert-danger">{{ Session::get('error-msg') }}</p>
  @endif

  <form class="mt-4" role="form" method="post" action="/rule">
    {{--  POST時傳入CSRF的_token  --}}
    {!! csrf_field() !!}

    {{--  如果有請求的uri則發出請求的uri；沒有則直接取得請求的uri，待會用來重新定向用  --}}
    @if (Session::has('req-uri'))
      <input type="hidden" name="req-uri" value={{ Session::get('req-uri') }}>
    @else
      <input type="hidden" name="previous" value={{ Request::getRequesturi() }}>
    @endif

		<div class="form-group row">
			<label for="name" class="col-sm-2 col-form-label">姓名：</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" name="name" id="name" placeholder="請輸入姓名" value="{{ old('name') }}">
				@if ($errors->has('name')) <p class="alert alert-danger">請輸入姓名</p> @endif
			</div>
		</div>
		<div class="form-group row">
			<label for="school" class="col-sm-2 col-form-label">學校：</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" name="school" id="school" placeholder="XX中學" value="{{ old('school') }}">
				@if ($errors->has('school')) <p class="alert alert-danger">請輸入學校名稱</p> @endif
			</div>
		</div>
		<div class="form-group row">
			<label for="id" class="col-sm-2 col-form-label">學號：</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" name="id" id="id" placeholder="請輸入學號" value="{{ old('id') }}">
				@if ($errors->has('id')) <p class="alert alert-danger">請輸入學號</p> @endif
			</div>
		</div>
		<div class="form-group row">
			<label for="experiment" class="col-sm-2 col-form-label">進行實驗：</label>
			<div class="col-sm-5">
				<select class="form-control" name="experiment">
                    @foreach($experiments as $experiment)
                        <option {{ old('experiment') === $experiment? 'selected' : '' }}>
                            {{ $experiment }}
                        </option>
                    @endforeach
                </select>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-10">
				<button type="submit" class="btn btn-primary">開始建模</button>
			</div>
		</div>
	</form>

</div>
@stop
