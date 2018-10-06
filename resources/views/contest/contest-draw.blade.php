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
		.row {
			margin-bottom: 30px;
		}

		#deviation {
			font-size: 22px;
		}
	</style>
	<script>
		// x軸與y軸取出的物件位置
		var xIndex = 0;
		var yIndex = 1;

		//從session拿出數據跟單位
		var data = <?php echo htmlspecialchars_decode($data) ?>;
        var data_time = data[xIndex].data;
		var data_value = data[yIndex].data;
	
        var experiment = {!! json_encode($units) !!};
	</script>
<!-- @大括號*2的內容跟script.js裡面的scopt.XXX綁定（ex. script.js裡的scopt.greeting改變，這裡的greeting也會跟著變） -->
<div class="starter-template">
		<div class="form-group">
			<div ng-app="scopeExample">
				<div ng-controller="MyController">
					<div class="row">
						<div class="col-xs-10">
							<h1><b>@{{experiment_title}}</b></h1>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10">
                            <!-- 輸入公式的欄位 -->
							<div style="@{{ text_color }}">
                                f(x) =
							    <input type="text" size="50" placeholder="2x+sqrt(x)+pow(x,2)" ng-model="function">
							    <button class="btn btn-default" style="@{{ draw_button }}" ng-click='sayHello()'>繪圖</button>
                                <button type="submit" class="btn btn-default btn-lg" style="margin-left:25px" ng-click='final()'>
                                    @{{ buttom_state }}
                                </button>
                            </div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-3 col-xs-offset-6">
							<!-- 顯示誤差率 -->
                            <p id=deviation><b>@{{ deviation }}</b></p>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-10">
                            <!-- 繪圖 -->
							@{{ greeting }}

                            <!-- 顯示建模歷程 -->
							<div id="myDiv" style="float:left; width: 600px; height: 500px;"></div>
							<div style="float:left; padding-top: 105px;">
								<div id="happyDIV" style="width:200px; height:350px; overflow-y:auto;">
									<table>
										<tr ng-repeat="error in errors">
											<td>@{{ error.formula }}</td>
											<td>@{{ error.value }}</td>
										</tr>
									</table>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

</div>
@stop
