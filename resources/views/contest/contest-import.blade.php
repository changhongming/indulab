@extends('layouts.layout')
@section('content')
<div class="introduce-bg2 introduce-mid">
	<h2>請上傳你的實驗數據檔案</h2>
	注意事項：
	<ul>
		<li>檔名不能含有中文</li>
		<li>副檔名要為csv</li>
		<li>內容只能有實驗的數據資料，若有繪製圖表，請刪除後再上傳</li>
	</ul>

	<form class="mt-4" role="form" id="import-form" method="post" action="/import">
    {!! csrf_field() !!}


    <div class="form-group row">
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

		<div class="form-group col-sm-7" style="padding:0px;">
			<div class="input-group input-file" name="Fichier1">
				<input type="text" class="form-control" placeholder='Choose a file...' />
				<span class="input-group-btn">
					<button class="btn btn-default btn-choose" type="button">選擇檔案</button>
					<button type="submit" class="btn btn-primary">上傳檔案</button>
				</span>
			</div>
		</div>

		<input type="hidden" id="time" name="time">
		<input type="hidden" id="value" name="value">
    <input type="hidden" id="data" name="data">
	</form>
</div>

@section('script')
    <!-- TextDecoder依賴(配合IE沒有支援TextDecoder實作) -->
    <script src="https://unpkg.com/text-encoding@0.6.4/lib/encoding-indexes.js"></script>
    <script src="https://unpkg.com/text-encoding@0.6.4/lib/encoding.js"></script>

    <!-- 驗證字串編碼方式 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jschardet/1.4.1/jschardet.min.js"></script>

    <script>
        //這邊的code參考https://github.com/evanplaice/jquery-csv裡面的Client-Side File Handling
        $(document).ready(function () {
        	bs_input_file();
        	if (isAPIAvailable()) {
        		$('#files').bind('change', handleFileSelect);
        	}
        });

        function bs_input_file() {
        	$(".input-file").before(
        		function () {
        			if (!$(this).prev().hasClass('input-ghost')) {
        				var element = $("<input type='file' class='input-ghost' id='files' name='files[]'' accept='.csv' style='visibility:hidden; height:0'>");
        				element.attr("name", $(this).attr("name"));
        				element.change(function () {
        					element.next(element).find('input').val((element.val()).split('\\').pop());
        				});
        				$(this).find("button.btn-choose").click(function () {
        					element.click();
        				});
        				$(this).find('input').css("cursor", "pointer");
        				$(this).find('input').mousedown(function () {
        					$(this).parents('.input-file').prev().click();
        					return false;
        				});
        				return element;
        			}
        		}
        	);
        }


        $('#import-form').submit(function (event) {
        	const x = document.getElementById('time').value;
        	const y = document.getElementById('value').value;
        	if (x === '' || y === '') {
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
        	reader.onload = function (event) {
            // 將2進制的buffer轉為byte陣列(0-255)
            var bytes = new Uint8Array(event.target.result);
            // 再將每一個byte轉為UTF-16格式的字元
            var binary = String.fromCharCode.apply(null, bytes);
            // 使用jschardet解析字串編碼格式，並創建一個此編碼格式的解碼器(IE瀏覽器內部尚未支援，自行實作)
            var decoder = new TextDecoder(jschardet.detect(binary).encoding);
            // 將byte陣列進行解碼
            var csv = decoder.decode(bytes);

            // 將CSV黨轉為二微陣列物件
            var data = CSVToArray(csv);
        		var data_time = [];
        		var data_value = [];

        		// 存放實驗數據資料
        		var data_arr = [];
        		// 目前預留前三列為欄位名稱(分別為標題與單位和變數代號)
        		row_offset = 3;

        		for (var col = 0; col < data[0].length; col++) {
        			var obj = {};
        			obj['title'] = data[0][col];
        			obj['unit'] = data[1][col];
        			obj['symbol'] = data[2][col];
        			var tmpdata = [];
        			for (var row = row_offset; row < data.length; row++) {
        				tmpdata.push(data[row][col]);
        			}
        			obj['data'] = tmpdata;
        			data_arr.push(obj);
        		}

        		for (var row in data) {
        			for (var item in data[row]) {
        				data_time[row] = data[row][0];
        				data_value[row] = data[row][1];
        			}
        		}
        		document.getElementById("time").value = data_time;
        		document.getElementById("value").value = data_value;

        		// 因為input無法輸入物件進去，故先將其轉為json字串
        		document.getElementById("data").value = JSON.stringify(data_arr);
        	};
        	reader.onerror = function () {
        		alert('Unable to read ' + file.fileName);
          };
          // 讀取格式為Buffer
          reader.readAsArrayBuffer(file);
        }

        function CSVToArray( strData, strDelimiter ){
        // 如果沒給定則使用逗號作為分割
        strDelimiter = (strDelimiter || ",");
        // 創建一個解析CSV的正規表達式
        var objPattern = new RegExp(
            (
                // Delimiters.
                "(\\" + strDelimiter + "|\\r?\\n|\\r|^)" +
                // Quoted fields.
                "(?:\"([^\"]*(?:\"\"[^\"]*)*)\"|" +
                // Standard fields.
                "([^\"\\" + strDelimiter + "\\r\\n]*))"
            ),
            "gi"
            );
        // 初始化CSV陣列
        var arrData = [[]];
        // 存放分割完成的內容
        var arrMatches = null;
        // 解析輸入字串所有匹配的內容
        while (arrMatches = objPattern.exec( strData )){
            // 取出匹配的分隔內容
            var strMatchedDelimiter = arrMatches[ 1 ];
            // 確認不是開頭的分割，且不內容不為內容分割符號，故就為列分割符號
            if (
                strMatchedDelimiter.length &&
                (strMatchedDelimiter != strDelimiter)
                ){
                // 新增下一列
                arrData.push( [] );
            }
            // 取得分割內容(還需確認是否帶有分隔符號，需去除)
            if (arrMatches[ 2 ]){
                // 去除分割符號
                var strMatchedValue = arrMatches[ 2 ].replace(
                    new RegExp( "\"\"", "g" ),
                    "\""
                    );
            } else {
                // 不用去除分割符號，直接為內容
                var strMatchedValue = arrMatches[ 3 ];
            }
            // 將內容資料放入陣列
            arrData[ arrData.length - 1 ].push( strMatchedValue );
        }
        // 判斷最後一列是否為長度為1的陣列，並且值為空字串
        if(arrData[arrData.length - 1].length !== arrData[0].length && arrData[arrData.length - 1][0] === "") {
          arrData.length -= 1;
        }
        return( arrData );
    }

    </script>
@endsection
@stop
