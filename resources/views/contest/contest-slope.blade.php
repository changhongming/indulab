@extends('layouts.layout')

@section('full-content')


<div id="app">
    <p>
      <router-link to="/simslope">直線運動模擬</router-link>
      <router-link to="/expslope">直線運動題目</router-link>
      <router-link to="/micro">教學說明</router-link>
    </p>
  <router-view>
  </router-view>
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
