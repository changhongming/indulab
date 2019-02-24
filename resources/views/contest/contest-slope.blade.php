@extends('layouts.layout')

@section('full-content')


<div id="app">
    <!-- <p>
      <router-link to="/simslope">直線運動模擬</router-link>
      <router-link to="/expslope">直線運動題目</router-link>
      <router-link to="/slopetutorial">教學說明</router-link>
    </p> -->
  <router-view>
  </router-view>
</div>
@section('script')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.10.1/dist/katex.min.css" integrity="sha384-dbVIfZGuN1Yq7/1Ocstc1lUEm+AT+/rCkibIcC/OmWo5f0EA48Vf8CytHzGrSwbQ" crossorigin="anonymous">

<!-- The loading of KaTeX is deferred to speed up page rendering -->
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.10.1/dist/katex.min.js" integrity="sha384-2BKqo+exmr9su6dir+qCw08N2ZKRucY4PrGQPPWU1A7FtlCGjmEGFqXCv5nyM5Ij" crossorigin="anonymous"></script>

<!-- To automatically render math in text elements, include the auto-render extension: -->
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.10.1/dist/contrib/auto-render.min.js" integrity="sha384-kWPLUVMOks5AQFrykwIup5lo0m3iMkkHrD0uJ4H5cjeGihAutqP0yW0J6dpFiVkI" crossorigin="anonymous"
    onload="renderMathInElement(document.body);"></script>
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
