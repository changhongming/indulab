@extends('layouts.layout')

@section('content')

<div class="introduce-bg2 introduce-mid">
	<div class="row">
        <div id="col-lg-8 col-md-8 col-sm-8 col-xs-8">
            <div id="app">
                <simslope></simslope>
            </div>
            <div id="surveyElement">
                <survey :survey='survey'/>
            </div>
            <div id="surveyResult"></div>
        </div>
	</div>
</div>
@section('script')
    <style>
        .btn-green {
            background-color: #1ab394;
            color: #fff;
            border-radius: 3px;
          }

          .btn-red {
            background-color: red;
            color: #fff;
            border-radius: 3px;
          }

          .btn-red:hover, .btn-red:focus {
              background-color: #cc0000;
              color: #fff;
          }

          .btn-green:hover, .btn-green:focus {
              background-color: #18a689;
              color: #fff;
          }

          .panel-footer {
              text-align: right;
              background-color: #fff;
          }
    </style>
    <script src="{{ mix('/js/survey-component.js') }}"></script>
@endsection
@stop
