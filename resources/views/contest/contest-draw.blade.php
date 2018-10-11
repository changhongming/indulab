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
						<div class="col-sm-12">
							<h2><b>@{{experiment_title}}</b></h2>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
                            <!-- 輸入公式的欄位 -->
							<div style="@{{ text_color }}">
                                <form class="form-inline">
									<label for="fun"><font size="3">@{{var_symbol}}</font></label>
									<input type="text" size="50" class="form-control mb-2 mr-sm-2" id="fun" placeholder="2x+sqrt(x)+pow(x,2)" ng-model="function">
									<button class="btn btn-primary mb-2" style="@{{ draw_button }}" ng-click='sayHello()'>繪圖</button>
									<button type="submit" class="btn btn-success btn-lg mb-2" style="margin-left:25px" ng-click='final()'>
										@{{ buttom_state }}
									</button>
								</form>
                            </div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-offset-6">
							<!-- 顯示誤差率 -->
                            <p id=deviation><b>@{{ deviation }}</b></p>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-12">
                            <!-- 繪圖 -->
							@{{ greeting }}

                            <!-- 顯示建模歷程 -->
							<div id="myDiv" style="float:left; width: 700px; height: 400px;"></div>
							<div style="float:left; padding-top: 20px;">
								<div id="happyDIV" style="height:350px; overflow-y:auto;">
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
					<div class="row">
						<div class="col-sm-6">
							<div class="dropdown">
    							<button class="btn btn-primary dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">X
    							<span class="caret"></span></button>
    							<ul id="menu-x" class="dropdown-menu" role="menu" aria-labelledby="menu1">
    							</ul>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="dropdown">
    							<button class="btn btn-primary dropdown-toggle" id="menu1" type="button" data-toggle="dropdown">Y
    							<span class="caret"></span></button>
    							<ul id="menu-y" class="dropdown-menu" role="menu" aria-labelledby="menu1">
    							</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

</div>
@stop
