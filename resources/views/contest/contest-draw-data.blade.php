@extends('layouts.layout')

@section('content')
<div class="loading"><div></div></div>
<div class="introduce-bg2 introduce-mid">
	<div class="row">
        <div class="col-12 col-xs-12 col-md-4 col-lg-3">
            <div id="editTable" ></div>
            <div class="gap"></div>
            <button class="btn btn-success" id="drawButton">繪圖 <i class="fas fa-check"></i></button>
            <button class="btn btn-danger" id="clearButton">清除 <i class="fas fa-trash-alt"></i></button>
        </div>
        <div class="col-12 col-xs-12 col-md-8 col-lg-9">
          <div id="plotDiv"></div>
        </div>
	</div>
</div>
@section('script')
  <style>
    /* 設定表格與按鈕的空隙 */
    div.gap {
      margin-top: 10px
    }

    #editTable {
      /* 限制攔寬 */
      height: 300px;
      overflow: hidden auto;
    }
    /* 標頭字體顏色 */
    span.colHeader {
        color: white;
    }
    /* 選取到的欄位頭 */
    th.ht__highlight {
      background-color: #227dc7 !important;
    }
    /* 欄位顏色 */
    .ht_clone_top th {
      background-color: #3490dc;
    }
    /* 選擇的欄位 */
    .ht_master tr > td.foo {
      background-color: #227dc7;
    }
    /* 偶數列 */
    .ht_master tr:nth-of-type(even) > td {
      background-color: #ddd;
    }
  </style>
  <script src="{{mix('/js/drawData-page.js')}}"></script>
@endsection
@stop


