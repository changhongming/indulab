@extends('layouts.layout')
@section('content')
	<style>
		table, th , td  {
			border: 1px solid grey;
			border-collapse: collapse;
			padding: 5px;
		}
		table tr:nth-child(odd) {
			background-color: #f1f1f1;
		}
		table tr:nth-child(even) {
			background-color: #ffffff;
		}
		#deviation {
			margin-top: 10px;
			font-size: 22px;
		}
	</style>

<!-- @大括號*2的內容跟script.js裡面的scopt.XXX綁定（ex. script.js裡的scopt.greeting改變，這裡的greeting也會跟著變） -->
<div class="introduce-bg2 introduce-mid">
	<div ng-app="scopeExample">
		<div ng-controller="MyController">
			<h2><b>@{{experiment_title}}</b></h2>
			<div class="row">
				<div class="col-sm-12">
					<!-- 輸入公式的欄位 -->
					<div style="@{{ text_color }}">
						<form class="ml-3">
							<div class="form-group row autosized" style="margin: 0;">
                                <label for="fun" class="col-sm-auto label text-justify">
                                    <span class="symbol_val"></span>
                                </label>
								<div class="col-sm-5 mb-2">
									<input type="text" class="form-control" id="fun" placeholder="@{{ fucntion_placehold }}" autocomplete="off" ng-model="function" ng-change="functionChange()">
                                </div>
								<button class="btn btn-primary mb-2" style="@{{ draw_button }}" ng-click='sayHello()'>繪圖</button>
								<button type="submit" class="btn btn-success btn-lg mb-2" style="margin-left:25px" ng-click='final()'>
									@{{ buttom_state }}
								</button>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="row autosized ml-3">
                <span class="col-sm-auto label invisible symbol_val">f(t) =</span>
                <div class="col-sm-5">
                    <div math-jax-bind="pretty_function"></div>
                </div>
				<div class="col-sm-4 offset-xl-2">
					<!-- 顯示誤差率 -->
					<p id=deviation class="align-middle"><b>@{{ deviation }}</b></p>
                </div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<!-- 繪圖 -->
					@{{ greeting }}

					<!-- 顯示建模歷程 -->
					<div id="myDiv" style="float:left; width:700px; height:400px; background-color:#f8fafc;"></div>
					<div style="padding-top: 20px;">
						<div id="happyDIV" style="height:350px; overflow-y:auto;">
							<table>
								<tr ng-repeat="error in errors">
									<td>@{{ error.formula }}</td>
									<td>@{{ error.value }}</td>
									<td>@{{ error.xinfo }}</td>
									<td>@{{ error.yinfo }}</td>
								</tr>
							</table>
						</div>

					</div>
				</div>
			</div>

			<div id="dropdown-xy" class="row">
				<div class="col-6">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" id="menu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							縱軸
						</button>
						<div id="menu-y" class="dropdown-menu" aria-labelledby="menu2"></div>
					</div>
				</div>
                <div class="col-6">
					<div class="dropdown">
						<button class="btn btn-primary dropdown-toggle" type="button" id="menu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							橫軸
						</button>
						<div id="menu-x" class="dropdown-menu" aria-labelledby="menu1"></div>
					</div>
                </div>
			</div>

			<br>
		</div>
	</div>

</div>
@section('script')
    <!-- TODO mathjax 如果需要離線打包需要在研究所需的設定，當前先採用cdn方式 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.3/MathJax.js?config=TeX-AMS-MML_HTMLorMML.js"></script>

    <script src="{{ mix('/js/modelling-page.js') }}"></script>
    <script>
        // x軸與y軸取出的物件位置
        var xIndex = 0;
        var yIndex = 1;

        //從session拿出數據跟單位
        var data = <?php echo htmlspecialchars_decode($data) ?>;

        var experiment = {!! json_encode($units) !!};
    </script>
@endsection
@stop
