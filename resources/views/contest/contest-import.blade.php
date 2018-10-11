@extends('layouts.layout')
@section('content')
<div class="starter-template">
	<form id="import-form" class="form-horizontal" role="form" method="post" action="/import">
		<!--<img src="{{ asset('img/title.jpg') }}" class="img-responsive" alt="InduLab">-->
		<div class="form-group">
			<div class="col-sm-offset-1 col-sm-9">
				<h2>請上傳你的實驗數據檔案</h2>
				注意事項：
				<ul>
					<li>檔名不能含有中文</li>
					<li>副檔名要為csv</li>
					<li>內容只能有實驗的數據資料，若有繪製圖表，請刪除後再上傳</li>
				</ul>
				<br>
				{!! csrf_field() !!}
				<div id="inputs" class="clearfix">
					<input type="file" id="files" name="files[]" accept=".csv" multiple />
				</div>

				<input type="hidden" id="time" name="time">
				<input type="hidden" id="value" name="value">
				<input type="hidden" id="data" name="data">
				<div class="form-group">
					<div class="col-md-offset-0 col-md-2 ">
						<br> 
						<button type="submit" class="clearfix">上傳檔案</button>
					</div>
				</div>


			</div>
		</div>
		
	</form>
	
	<script>
		//這邊的code參考https://github.com/evanplaice/jquery-csv裡面的Client-Side File Handling
        $(document).ready(function() {
			if(isAPIAvailable()) {
				$('#files').bind('change', handleFileSelect);
			}
		});
		

		$('#import-form').submit(function(event) {
			const x = document.getElementById('time').value;
		 	const y = document.getElementById('value').value;
			if(x === '' || y === '') {
				alert('沒有上傳資料，請先上傳再繼續');
				// 取消submit
				event.preventDefault();
			}
		});

		function isAPIAvailable() {
			// Check for the various File API support.
			if (window.File && window.FileReader && window.FileList && window.Blob) {
				// Great success! All the File APIs are supported.
				return true;
			} else {
				// source: File API availability - http://caniuse.com/#feat=fileapi
				// source: <output> availability - http://html5doctor.com/the-output-element/
				document.writeln('瀏覽器不支援，請使用較新版本的瀏覽器');
				return false;
			}
		}

		function handleFileSelect(evt) {
			var files = evt.target.files; // FileList object
			var file = files[0];
					
			// read the file contents
			printTable(file);
		}

		function printTable(file) {
			var reader = new FileReader();
			reader.readAsText(file,'big5');
			reader.onload = function(event){
				var csv = event.target.result;
				var data = $.csv.toArrays(csv);
				var data_time = [];
				var data_value = [];
				
				// 存放實驗數據資料
				var data_arr = [];
				// 目前預留前三列為欄位名稱(分別為標題與單位和變數代號)
				row_offset = 3;

				for(var col = 0; col < data[0].length; col++){
					var obj = {};
					obj['title'] = data[0][col];
					obj['unit'] = data[1][col];
					obj['symbol'] = data[2][col];
					var tmpdata = [];
					for(var row = row_offset; row < data.length; row++){
						tmpdata.push(data[row][col]);
					}
					obj['data'] = tmpdata;
					data_arr.push(obj);	
				}
				
				for(var row in data) {
					for(var item in data[row]) {
						data_time[row] = data[row][0];
						data_value[row] = data[row][1];
					}
				}
				document.getElementById("time").value = data_time;
				document.getElementById("value").value = data_value;
				
				// 因為input無法輸入物件進去，故先將其轉為json字串
				document.getElementById("data").value = JSON.stringify(data_arr);
			};
			reader.onerror = function(){ alert('Unable to read ' + file.fileName); };
		}
	</script>
</div>

@stop
