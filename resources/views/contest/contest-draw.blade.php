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
	</style>
	<script>
		//從session拿出數據跟單位
        var data_time = [{{ $time }}];
		var data_value = [{{ $value }}];
        var experiment = {!! json_encode($units) !!};
	</script>
<!-- @大括號*2的內容跟script.js裡面的scopt.XXX綁定（ex. script.js裡的scopt.greeting改變，這裡的greeting也會跟著變） -->
<div class="starter-template">
		<div class="form-group">
			<div ng-app="scopeExample">
				<div ng-controller="MyController">
					<div class="row">
						<div class="col-xs-10">
                            <!-- 輸入公式的欄位 -->
							<div style="@{{ text_color }}">
                                f(t) =
							    <input type="text" size="50" ng-model="function">
							    <button class="btn btn-default" style="@{{ draw_button }}" ng-click='sayHello()'>繪圖</button>
                                <button type="submit" class="btn btn-default btn-lg" style="margin-left:25px" ng-click='final()'>
                                    @{{ buttom_state }}
                                </button>
                            </div>
                            <hr>
                            <!-- 顯示誤差率 -->
                            <h3>@{{ deviation }}</h3>
                            <hr>

						</div>
					</div>
					<div class="row">
						<div class="col-xs-10">
                            <!-- 繪圖 -->
							@{{ greeting }}

                            <!-- 顯示建模歷程 -->
							<div id="myDiv" style="float:left; width: 600px; height: 500px;"></div>
							<div style="float:left; padding-top: 105px;">
								<div id="happyDIV" style="width:200px; height:350px; overflow-y:scroll;">
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
