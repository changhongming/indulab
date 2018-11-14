(function(angular) {
    'use strict';
    angular.module('scopeExample', [])
      .controller('MyController', ['$scope', '$log', '$http', function($scope, $log, $http) {
        // 預設公式為t
        $scope.function = '';
        // 用來儲存建模過程的誤差
        $scope.errors = [{formula:"公式", value:"誤差率"}];
        // 顯示標題(實驗主題名稱)
        $scope.experiment_title = "實驗主題：" + experiment.experiment;        
        // 讓最右邊的大按鈕顯示“完成建模“
        $scope.buttom_state = '完成建模';
        
        $scope.xyChange = false;
        
        // 圖表註解
        var annotation_x = {
          xref: 'paper',
          yref: 'paper',
          xanchor: 'left',
          yanchor: 'top',
          x: 0.9,
          y: -0.05,
          text: 'X axis label',
          showarrow: false,
          font: {
            family: '微軟正黑體, Arial, Courier New, Times New Roman',
            size: 16,
          }
        };
        var annotation_y = {
          xref: 'paper',
          yref: 'paper',
          xanchor: 'left',
          yanchor: 'bottom',
          x: -0.1,
          y: 1,
          text: 'Y axis label',
          showarrow: false,
          //textangle: -90,
          font: {
            family: '微軟正黑體, Arial, Courier New, Times New Roman',
            size: 16,
          }
        };

        // 設定圖表的參數
        var layout = {
          // 顯示實驗的名稱（從資料庫來的）
          //title: experiment.experiment,
          margin: {
            t: 20
          },
          xaxis: {
            // 左下從0/0顯示
            rangemode: 'tozero',
            autorange: true,
            // x軸單位（從資料庫）
            //title: data[xIndex].title + '(' + data[xIndex].unit + ')'
          },
          yaxis: {
            // 左下從0/0顯示
            rangemode: 'tozero',
            autorange: true,
            // y軸單位（從資料庫）
            //title: data[yIndex].title + '(' + data[yIndex].unit+ ')'
          },
          // 圖表註解:取代xy軸title，因為title無法改變位置
          annotations: [annotation_x, annotation_y],
          // 將plotly的背景改為透明
          paper_bgcolor: 'rgba(0,0,0,0)',
          plot_bgcolor: 'rgba(0,0,0,0)'
        };

        console.log(data);
        // 進行初始化
        $scope.init = function() {
          $scope.renderMenu();
          $scope.setVarSymbol(data[xIndex].symbol);
          $scope.drawExperimentData();
          console.log("init compleate!");
        }

        $scope.setVarSymbol = function(symbol) {
          $("#symbol_val").html("f(" + symbol + ") =");
          console.log($("#symbol_val").html);
        }

        // 更新x、y軸表單欄位跟事件
        $scope.renderMenu = function() {
          var xTab = [];
          var yTab = [];
          $.each(data, function(i,value){
            var obj = {};
            obj["name"] = value.title + " (" + value.unit + ")";
            obj["id"] = i;
            if(i != xIndex) {
              xTab.push(obj);
            }
            if(i != yIndex) {
              yTab.push(obj);
            }
          });
          var xyTab = [];
          var xyhtml = [];
          
          xyTab.push(xTab);
          xyTab.push(yTab);
          $.each(xyTab, function(i,tabValue) {
            xyhtml[i] = '';
            $.each(tabValue, function(j,value) {
              xyhtml[i] += "<button type=\"button\" class=\"dropdown-item\" _id=\""+ value.id + "\">" + value.name + "</button>";
            });
          });

          // 更新下拉式表單
          $("#menu-x").html(xyhtml[0]);
          $("#menu-y").html(xyhtml[1]);
          
          // 建立監聽事件
          $("#menu-x button").click(function() {
            // 保存切換前的位置
            const tmpIndex = xIndex;
            // 更新切換的位置
            xIndex = $(this).attr("_id");
            $scope.xyChange = true;
            // 如果選擇到相同位置，則進行x、y軸交換
            if(xIndex == yIndex) {
              yIndex = tmpIndex;
            }
            // 更新x、y軸表單
            $scope.renderMenu();
            $scope.setVarSymbol(data[xIndex].symbol);
            $scope.drawExperimentData();
          });
          $("#menu-y button").click(function() {
            // 保存切換前的位置
            const tmpIndex = yIndex;
            // 更新切換的位置
            yIndex = $(this).attr("_id");
            $scope.xyChange = true;
            // 如果選擇到相同位置，則進行x、y軸交換
            if(xIndex == yIndex) {
              xIndex = tmpIndex;
            }
            // 更新x、y軸表單
            $scope.renderMenu();
            $scope.setVarSymbol(data[xIndex].symbol);
            $scope.drawExperimentData();
          });
        }

        $scope.drawExperimentData = function() { 
          var trace1 = {
            x: data[xIndex].data,
            y: data[yIndex].data,
            mode: 'markers',
            name: "實驗值"
          };
          annotation_x.text = data[xIndex].title + " " + data[xIndex].symbol + ' (' + data[xIndex].unit + ')';
          annotation_y.text = data[yIndex].title + " " + data[yIndex].symbol + ' (' + data[yIndex].unit + ')';
          layout.annotations = [annotation_x,annotation_y];         
          Plotly.newPlot('myDiv', [trace1], layout, {displaylogo: false});
        }

        //contest-draw.blade.php裡面的繪圖button，有使用ng-click讓它每按一次就觸發一次sayHello
        //sayHello用來將公式以及數據利用plotly繪出
        $scope.sayHello = function() {
          //如果輸入的公式與上一次的公式不同或xy軸有重選才做繪圖
          if ($scope.function != $scope.errors[$scope.errors.length - 1].formula || $scope.xyChange) {

            //先將輸入的公式轉成math.eval可執行的f(x)=XXXX格式，並將裡面的t變數換成x
            //replace的說明可參考http://www.w3school.com.cn/jsref/jsref_replace.asp
            //轉換完之後給math.eval算出結果
            var f = math.eval("f(" + data[xIndex].symbol + ")=" + $scope.function);

            var i;
            //預設顯示的圖，程式執行失敗才顯示
            var trace0 = {
              x: [1, 2, 3],
              y: [4, 3, 2],
              //設定圖表的樣式
              //圖表或標示等相關設定參考https://plot.ly/javascript/裡面的Layout Options或範例code
              mode: 'markers',
              //設定標示的樣式
              marker: {
                color: 'green',
                symbol: 'circle-open',
                size:10
              },
              name: "公式預測值"
            };

            //預設顯示的圖，程式執行失敗才顯示
            var trace1 = {
              x: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
              y: [0, 1, 4, 9, 16, 25, 36, 49, 64, 81, 100],
              mode: 'markers',
              name: "實驗值"
            };
            //將數據放入trace1的x, y
            trace1.x = data[xIndex].data;
            trace1.y = data[yIndex].data;

            //trace0與trace1放入data_plot，之後顯示的Plotly.newPlot函式要用
            var data_plot = [trace1, trace0];
            $scope.greeting = "";
            //從實驗數據取得時間帶入建模公式
            trace0.x = trace1.x;
            //宣告誤差率的分母、分母
            var deviation_denominator = 0;
            var deviation_numerator = 0;
            //將每個實驗的時間數據帶入到建模公式、f()前面math.eval那邊有宣告
            try{
              for (i = 0; i < trace1.x.length; i++) {
                trace0.y[i] = f(trace0.x[i]);
                //計算誤差率的分子與分母
                deviation_numerator = deviation_numerator + Math.abs(trace0.y[i] - trace1.y[i]);
                deviation_denominator = deviation_denominator + Math.abs(trace0.y[i]);
              }
            }
            catch(err){
              console.log(err)
              return;
            }
            //算出誤差率並轉成百分比
            var error = deviation_numerator / deviation_denominator * 100;
            //結果丟到contest.draw.blade.php裡的@{{ deviation }}顯示
            $scope.deviation = "誤差率 = "  + error.toFixed(2) + "%";

            //errors存放建模過程的公式與誤差率
            $scope.errors[$scope.errors.length] = {formula:$scope.function, value:error.toFixed(2)};
            //公式與誤差從errors拿出來（要傳給資料庫用的）
            $scope.error_formula = $scope.errors[$scope.errors.length - 1].formula;
            $scope.error_value = $scope.errors[$scope.errors.length - 1].value;
            //傳送建模的過程給資料庫
            if ($scope.errors.length != 1) {
              //save_data儲存要傳給資料庫的資料
              var save_data = {};
              save_data.formula = $scope.error_formula;
              save_data.error = $scope.error_value;
              save_data.xLabel = data[xIndex].title;
              save_data.xUnit = data[xIndex].unit;
              save_data.yLabel = data[yIndex].title;
              save_data.yUnit = data[yIndex].unit;
              console.log(save_data);
              //點繪圖按鈕傳資料給資料庫
              $http.post('/draw', save_data)
                .then(function success(response)
                  {
                    $log.log(response.data_plot) ;
                  }
                  ,function error(response)
                  {
                    console.log(response.data_plot) ;
                  });
            }

          annotation_x.text = data[xIndex].title + " " + data[xIndex].symbol + ' (' + data[xIndex].unit + ')';
          annotation_y.text = data[yIndex].title + " " + data[yIndex].symbol + ' (' + data[yIndex].unit + ')';
            layout.annotations = [annotation_x,annotation_y];
            //繪圖
            Plotly.newPlot('myDiv', data_plot, layout, {displaylogo: false});
          }
          //設定按鈕按下去經過10毫秒將右邊的歷程自動拉到最下方（確保顯示最新的建模記錄）
          setTimeout(function() {
            var objDiv = document.getElementById("happyDIV");
            objDiv.scrollTop = objDiv.scrollHeight;
          }, 10);
          $scope.xyChange = false;
        };
        
        //contest-draw.blade.php裡面的完成建模button，有使用ng-click讓它每按一次就觸發一次final
        //final用來將最後建模的公式以及誤差傳給資料庫
        $scope.final = function() {
          //按鈕一開始顯示完成建模，按一下後變提交模型，再按一下傳送結果給資料庫
          if($scope.buttom_state == "提交模型") {
            //save_data儲存要傳給資料庫的資料
            var save_data = {};
            save_data.formula = $scope.error_formula;
            save_data.error = $scope.error_value;
            save_data.xLabel = data[xIndex].title;
            save_data.xUnit = data[xIndex].unit;
            save_data.yLabel = data[yIndex].title;
            save_data.yUnit = data[yIndex].unit;
            //設定save_data.final為1，後台才知道這筆資料是最後的結果
            save_data.final = 1;
            //點提交模型按鈕傳資料給資料庫
            $http.post('/draw', save_data)
              .then(function success(response)
                {
                  $log.log(response.data) ;
                  window.location.href = "/rule";
                }
                ,function error(response)
                {
                  console.log(response.data) ;
                });
          }
          //第一次按完成建模，系統還不會馬上提交結果，會先改變按鈕的名稱
          else {
            //將按鈕改成提交模型
            $scope.buttom_state = "提交模型";
            //誤差顯示改成提醒
            $scope.deviation = "請在欄位內填入誤差值最小結果";
            //將繪圖按鈕隱藏
            $scope.draw_button = "display:none";
          }
          //最後讓輸入欄位有紅色邊框
          $scope.text_color = "border-color:red;border-width:3px;border-style:dotted;";

        };
        //剛進來頁面先進行一次繪圖，公式預設是f(t)=t
        //$scope.sayHello();
        $scope.init();
      }]);
  }
)(window.angular);

