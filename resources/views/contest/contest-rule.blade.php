@extends('layouts.layout')

@section('content')
<div class="starter-template">
	<form class="form-horizontal" role="form" method="post" action="/rule">
		<!--<img src="{{ asset('img/title.jpg') }}" class="img-responsive" alt="InduLab">-->
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-9">
				<h2>InduLab 數學建模系統</h2>
			</div>
		</div>
		{!! csrf_field() !!}
		<div class="form-group">
			<label class="col-sm-2 control-label">
				姓名：
			</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" name="name" placeholder="請輸入姓名" value="{{ old('name') }}">
				@if ($errors->has('name')) <p class="alert alert-danger">請輸入姓名</p> @endif
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">
				學校：
			</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" name="school" placeholder="XX中學" value="{{ old('school') }}">
				@if ($errors->has('school')) <p class="alert alert-danger">請輸入學校名稱</p> @endif
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">
				學號：
			</label>
			<div class="col-sm-5">
				<input type="text" class="form-control" name="id" placeholder="請輸入學號" value="{{ old('id') }}">
				@if ($errors->has('id')) <p class="alert alert-danger">請輸入學號</p> @endif
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">
				進行實驗：
			</label>
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
		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-2">
				<button type="submit" class="btn btn-primary">開始建模</button>
			</div>
		</div>
	</form>
</div>
@stop
