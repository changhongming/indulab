<template>
  <b-container fluid ref="container">
    <!-- 說明dialog -->
    <button
      type="button"
      class="btn btn-secondary"
      data-toggle="modal"
      data-target=".tutor-bd-modal-lg"
    >使用教學</button>
    <div
      class="modal fade tutor-bd-modal-lg"
      tabindex="-1"
      role="dialog"
      aria-labelledby="myLargeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <pictureContent :items="tutorData"/>
        </div>
      </div>
    </div>

    <div v-draggable="dragRealTime" class="dragable-item">
      <div ref="realTimeCard">
        <template v-if="showInfoCard">
          <b-card
            @click="hideInfoCard"
            draggable="true"
            bg-variant="info"
            text-variant="white"
            header-bg-variant="info"
            class="text-left"
          >
            <h6 slot="header">
              <i class="fas fa-compress-arrows-alt"></i> 瞬間數據
            </h6>
            <table>
              <tr>
                <td>時間</td>
                <td>{{tickTime}}</td>
                <td>秒</td>
              </tr>
              <tr>
                <td>加速度</td>
                <td>{{accel}}</td>
                <td>公尺/秒^2</td>
              </tr>
              <tr>
                <td>瞬時速度</td>
                <td>{{vol}}</td>
                <td>公尺/秒</td>
              </tr>
              <tr>
                <td>位移</td>
                <td>{{disp}}</td>
                <td>公尺</td>
              </tr>
            </table>
          </b-card>
        </template>
        <template v-else>
          <h4>
            <b-badge @click="hideInfoCard" href="#" variant="info">
              <i class="fas fa-list"></i> 即時數據
            </b-badge>
          </h4>
        </template>
      </div>
    </div>
    <b-row>
      <b-col xs="6">
        <!-- 參數輸入框 -->
        <b-row>
          <b-col>
            <rangeInput
              :value="inputSlopeLength"
              :max="10"
              :min="0.1"
              :step="0.01"
              :disable="isSimBegin"
              id="斜坡長度(方塊長寬皆為0.1公尺)"
              unit="m"
              v-bind:on-value-change.sync="inputSlopeLength"
            ></rangeInput>
          </b-col>
          <b-col>
            <rangeInput
              ref="massInput"
              :value="mass"
              :max="20"
              :min="1"
              :step="0.1"
              :disable="isSimBegin"
              id="質量"
              unit="kg"
              v-bind:on-value-change.sync="mass"
            ></rangeInput>
          </b-col>
          <b-col>
            <rangeInput
              :value="angle"
              :max="90"
              :min="1"
              :step="1"
              :disable="isSimBegin"
              id="角度"
              unit="°"
              v-bind:on-value-change.sync="angle"
            ></rangeInput>
          </b-col>
        </b-row>
        <b-row>
          <b-col>
            <rangeInput
              :value="inputSlopeBreakPoint"
              :max="getBreakPointMax"
              :min="0"
              :step="0.01"
              :disable="isSimBegin"
              id="斷點設定"
              unit="m"
              type="range"
              v-bind:on-value-change.sync="inputSlopeBreakPoint"
              v-on:setup-value="drawBreakPoint"
              v-on:on-clear-btn-click="clearBreakPoint"
            />
          </b-col>
        </b-row>
        <!-- 輔助線選單 -->
        <b-row>
          <b-col>
            <b-check v-model="isShowGridLines">
              <span>{{isShowGridLines ? "顯示" : "隱藏"}}</span>網格線(10cm)
            </b-check>
            <b-check v-model="gridLineStyle">
              <span>{{gridLineStyle ? "一般" : "平行斜坡"}}</span>網格線(10cm)
            </b-check>
            <template v-if="is_ani_start">
              <button class="btn btn-outline-danger" @click="pauseAniBtn">
                <i class="fas fa-stop"></i> pause
              </button>
            </template>
            <template v-else>
              <button class="btn btn-success" @click="startAniBtn" :disabled="is_ani_start">
                <i class="fas fa-play"></i> start
              </button>
            </template>
            <button class="btn btn-outline-danger" @click="resetAniBtn">
              <i class="fas fa-undo"></i> reset
            </button>
            <button class="btn btn-success" @click="downloadInfoData">
              <i class="fas fa-download" aria-hidden="true"></i> download
            </button>
          </b-col>
        </b-row>
        <!-- 模擬動畫區 -->
        <b-row>
          <b-col>
            <v-stage ref="stage" :config="configKonva">
              <v-layer ref="layer">
                <v-rect ref="mrect" :config="configRect"/>
                <v-shape ref="base" :config="baseConfig"/>
                <v-text ref="angleText" :config="configAngleText"/>
                <v-arc :config="configAngleArc"/>
                <v-image-src
                  ref="ultraImage"
                  v-on:onload="onUltraImgLoad"
                  :config="configUltraImg"
                />
                <v-text ref="infoText" :config="configInfoText"/>
              </v-layer>
              <v-layer ref="gridLayer"></v-layer>
              <v-layer ref="breakPoinLyaer"></v-layer>
            </v-stage>
          </b-col>
        </b-row>
      </b-col>

      <!-- 右方區域 -->
      <b-col xs="6">
        <!-- 模擬歷程顯示 -->
        <b-row>
          <b-col>
            <template v-if="show">
              <button @click="hideandshow" class="btn btn-outline-secondary">
                <i class="fas fa-chevron-up"></i> 歷史紀錄
              </button>
            </template>
            <template v-else>
              <label @click="hideandshow" class="btn btn-outline-secondary">
                <i class="fas fa-chevron-down"></i> 歷史紀錄
              </label>
            </template>
            <transition name="fade">
              <div v-if="show">
                <logTable :per-page="10" :items="dataLog" :fields="fields"></logTable>
              </div>
            </transition>
          </b-col>
        </b-row>
        <!-- 繪圖區 -->
        <!-- <b-row>
          <b-col>
            <div class="chart-container">
            <scatter-chart :data="chartData" :option="chartOption"></scatter-chart>
            </div>
          </b-col>
        </b-row>-->
        <b-row></b-row>
      </b-col>
    </b-row>

    <!-- 顯示動畫完成訊息 -->
    <b-alert
      :show="dismissCountDown"
      class="alert-fixed"
      dismissible
      fade
      variant="success"
      @dismissed="dismissCountDown=0"
      @dismiss-count-down="countDownChanged(dismissCountDown)"
    >模擬動畫結束</b-alert>
  </b-container>
</template>
<style scoped>
.chart-container {
  width: 300px;
  height: 200px;
}

.alert-fixed {
  position: fixed;
  bottom: 5px;
  right: 4px;
  width: 40%;
  z-index: 9999;
  border-radius: 4px;
}

.dragable-item {
  /* position: fixed; */
  z-index: 999;
}

.info-table-head {
  text-align: right;
}

.grid1cm {
  position: relative;
}

.grid1cm::before {
  content: "";
  position: absolute;
  width: 150%;
  height: 150%;
  top: 50%;
  left: 50%;
  z-index: 1;
  background-size: 1cm 1cm;
  background-image: linear-gradient(to right, grey 1px, transparent 1px),
    linear-gradient(to bottom, grey 1px, transparent 1px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>

<script>
"use strict";
import { Draggable } from "draggable-vue-directive";
import rangeInput from "./range-input.vue";
import logTable from "./log-table.vue";
import pictureContent from "./picture-content.vue";
import vImageSrc from "./v-image-src";
import tutorData from "../json/simSlopeTurtor.json.js";
import sort from "fast-sort";
import uuidGen from "../uuid-gen.js";

let _data;
let _anim;
let _gridLinesGroup;
let _gridLinesLayer;
let _breakPointGroup = null;
let _breakPointGone = null;
const _breakPointGoneBp = { bp: 0 };
let animTime = 0;
function deg2rad(deg) {
  return (Math.PI * deg) / 180;
}

// 在客戶端產生下載連結並下載檔案
const saveData = (function() {
  const a = document.createElement("a");
  document.body.appendChild(a);
  a.style = "display: none";
  return function(data, fileName, type = "text/csv;charset=big5") {
    const json = data;
    const blob = new Blob([json], { type }); //type: "octet/stream"
    const url = window.URL.createObjectURL(blob);
    a.href = url;
    a.download = fileName;
    a.click();
    window.URL.revokeObjectURL(url);
  };
})();
function getDPI() {
  const div = document.createElement("div");
  div.style.height = "1in";
  div.style.width = "1in";
  div.style.top = "-100%";
  div.style.left = "-100%";
  div.style.position = "absolute";

  document.body.appendChild(div);

  const result = div.offsetHeight;

  document.body.removeChild(div);
  //console.log(result);
  return result;
}

export default {
  // 引入外部vue檔案
  components: {
    rangeInput,
    logTable,
    pictureContent,
    vImageSrc
  },
  directives: {
    Draggable
  },
  data() {
    return {
      tutorData,
      chartOption: {
        responsive: true,
        maintainAspectRatio: false
      },
      chartData: {
        datasets: [
          {
            label: "Data One",
            backgroundColor: "#f87979",
            data: [1, 39, 10, 40, 39, 80, 40]
          }
        ]
      },
      // 歷史紀錄欄位轉換表
      columns: [
        { key: "id", content: "#" },
        { key: "g", content: "重力加速度" },
        { key: "angle", content: "傾斜角度" },
        { key: "mass", content: "質量" },
        { key: "vol", content: "結束瞬時速度" },
        { key: "disp", content: "結束位移" },
        { key: "time", content: "結束時間" }
      ],
      fields: [
        { key: "id", label: "#", sortable: true },
        // { key: "g", label: "重力加速度", sortable: true },
        { key: "angle", label: "傾斜角度", sortable: true },
        { key: "mass", label: "質量", sortable: true },
        { key: "vol", label: "結束瞬時速度", sortable: true },
        { key: "disp", label: "位移", sortable: true },
        { key: "time", label: "使用時間", sortable: true }
      ],
      streamLog: [],
      log: [],
      // 歷史紀錄資料
      dataLog: [],
      logKeyId: 1,
      // alert message 屬性
      dismissSecs: 5,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      dragRealTime: {
        resetInitialPos: true,
        initialPosition: { left: 200, top: 100 },
        onPositionChange: this.onPosChanged,
        //boundingElement: this.$refs.container,
        boundingRectMargin: { top: 0, right: 0, left: 0, bottom: 0 }
      },
      showInfoCard: true,
      isShowInfoDrag: false,
      show: true,
      mass: 1,
      isShowGridLines: true,
      // 如果為 true 則隔線為平行斜坡，為false 則為水平垂直線
      gridLineStyle: true,
      configKonva: {
        width: 2000,
        height: 1000
      },
      ultraWidth: null,
      ultraHeight: null,
      configUltraImg: {
        src: "image/hc-sr04-nobg.png",
        rotation: 60,
        x: 0,
        y: 0
      },
      configRect: {
        x: 50,
        y: 50,
        width: 50,
        height: 50,
        radius: 50,
        fill: "green",
        rotation: 45
      },
      configInfoText: {
        x: 0,
        y: 0,
        text: "",
        fontSize: 30,
        fill: "red"
      },
      configAngleText: {
        x: 0,
        y: 0,
        text: "angle",
        fontSize: 30,
        fontFamily: "Consolas",
        fill: "green"
      },
      configAngleArc: {
        x: 50,
        y: 50,
        innerRadius: 48,
        outerRadius: 50,
        rotation: 180,
        fill: "green"
      },
      isSimBegin: false,
      is_ani_start: false,
      tickTime: 0,
      // 輸入框的中斷點數值
      inputSlopeBreakPoint: 0,
      // 中斷點佇列
      slopeBreakPoint: [],
      inputSlopeLength: 1,
      cubeLength: 40,
      g: 9.8,
      vol: 0,
      accel: 0,
      disp: 0,
      angle: 45,
      ratioTime: 1,
      slope_len: 1000,
      offsetX: 250,
      offsetY: 37.8 * 2 + 150,
      ratio2cm: 1000,
      inputCubeLength: 0.1,
      baseConfig: {
        sceneFunc: function(context, shape) {
          context.beginPath();
          context.moveTo(10, 10);
          context.lineTo(10, 180);
          context.quadraticCurveTo(150, 100, 260, 170);
          context.closePath();

          // special Konva.js method
          context.fillStrokeShape(shape);
        },
        fill: "#00D2FF"
      }
    };
  },
  computed: {
    // 取得中斷點最大值
    getBreakPointMax: function() {
      return this.inputSlopeLength - this.inputCubeLength;
    }
  },
  // 請不要在這邊調用箭頭函式，否則無法有效地指到vue的this
  watch: {
    g: function(val) {
      this.accel = (val * Math.sin(deg2rad(this.angle))).toFixed(2);
    },
    inputSlopeLength: function(val, oldVal) {
      this.ratio2cm = (1 / val) * 1000;
      this.cubeLength = this.inputCubeLength * this.ratio2cm;

      // 更新超音波元件位置
      this.ultraImageRender();

      this.updateBreakPointsLengthLocation(oldVal);
      this.updateGridLines();
    },
    cubeLength: function(val) {
      this.configRect.x =
        this.offsetX +
        val *
          Math.cos(deg2rad(Number(this.angle))) *
          Math.tan(deg2rad(Number(this.angle)));
      this.configRect.y =
        this.offsetY -
        (val * Math.sin(deg2rad(Number(this.angle)))) /
          Math.tan(deg2rad(Number(this.angle)));
      this.configRect.rotation = Number(this.angle);
      this.configRect.width = val;
      this.configRect.height = val;
    },
    // ratio2cm: function(val) {
    //   this.updateGridLines();
    // },
    slope_len: function(val, oldVal) {
      //this.vaildSlopeLen(val, oldVal);
      this.configKonva = {
        width:
          val * Math.cos(deg2rad(this.angle)) +
          this.offsetX +
          this.configRect.height * 2,
        height: val * Math.sin(deg2rad(this.angle)) + this.offsetY
      };
      // 設定斜坡方塊屬性
      this.configRect.x =
        this.offsetX +
        this.cubeLength *
          Math.cos(deg2rad(Number(this.angle))) *
          Math.tan(deg2rad(Number(this.angle)));
      this.configRect.y =
        this.offsetY -
        (this.cubeLength * Math.sin(deg2rad(Number(this.angle)))) /
          Math.tan(deg2rad(Number(this.angle)));
      this.configRect.rotation = Number(this.angle);
      this.configRect.width = this.cubeLength;
      this.configRect.height = this.cubeLength;
      if (_anim !== undefined) _anim = undefined;
      //console.log(this.configKonva);
    },
    angle: function(val, oldVal) {
      const vm = this;
      // 計算加速度
      this.accel = (this.g * Math.sin(deg2rad(val))).toFixed(2);
      this.updateBreakPointsAngleLocation(val);
      this.configKonva = {
        width:
          this.slope_len * Math.cos(deg2rad(val)) +
          this.offsetX +
          this.configRect.height * 2,
        height: this.slope_len * Math.sin(deg2rad(val)) + this.offsetY
      };

      const rectX =
        this.offsetX + this.cubeLength * Math.sin(deg2rad(Number(val)));
      const rectY =
        this.offsetY - this.cubeLength * Math.cos(deg2rad(Number(val)));

      // 設定斜坡方塊屬性
      this.configRect.x = rectX;

      this.configRect.y = rectY;

      this.configRect.rotation = Number(val);
      // this.width = this.cubeLength;
      // this.height = this.cubeLength;
      // 更新超音波元件位置
      this.ultraImageRender();
      // 更新角度顯示
      this.updateAngleDraw(val);

      // 動態改變網格線偽元素的繪製角度(與斜坡平行與垂直)

      //document.styleSheets[0].addRule('.grid1cm::before', `transform-origin:20% 0%`)
      //document.styleSheets[0].addRule('.grid1cm::before', `transform-origin:20% 0%`)  width: 100% !important; high:100% !important ; transform-origin:50% 50%
      // document.styleSheets[0].addRule(
      //   ".grid1cm::before",
      //   `transform: rotate(${val}deg);`
      // );

      // 如果動畫還在則清除
      if (_anim !== undefined) _anim = undefined;
    },
    isShowGridLines: function(val) {
      this.isShowGridLinesMethod(val);
    },
    gridLineStyle: function(val) {
      this.updateGridLines();
    },
    configKonva: {
      handler(val, oldVal) {
        this.updateGridLines();
      }
    },
    mass(val) {
      this.onMassChange(val);
    }
  },
  // 請不要在這邊調用箭頭函式，否則無法有效地指到vue的this
  methods: {
    json2csv: function() {
      let csv = "距離,時間,速度,加速度\n公尺,秒,公尺/秒,公尺/秒^2\nd,t,v,a\n";
      const goal = this.inputSlopeLength - this.inputCubeLength;
      let d = 0;
      this.angle;
      let a = (this.g * Math.sin(deg2rad(this.angle))).toFixed(2);

      for (let t = 0; d < goal; t += 0.01) {
        d = (0.5 * a * t ** 2).toFixed(2);
        csv += `${d},${t.toFixed(2)},${(a * t).toFixed(2)},${a}\n`;
      }
      return csv;
    },
    onMassChange(val) {
      const max = this.$refs.massInput.max;
      const min = this.$refs.massInput.min;
      const maxPer = 50;
      const minPer = 20;
      let ligh = Math.round(
        maxPer -
          minPer -
          ((val - min) / (max - min)) * (maxPer - minPer) +
          minPer
      );
      let stage = this.$refs.stage.getStage();
      this.$refs.mrect.getStage().setFill(`hsl(100,100%,${ligh}%)`);
      stage.draw();
      return `hsl(100,100%,${ligh}%)`;
    },
    onPosChanged: function(positionDiff, absolutePosition, event) {
      this.isShowInfoDrag = true;
    },
    test() {
      const mousePos = this.$refs.stage.getStage().getPointerPosition();
    },
    hideInfoCard() {
      if (!this.isShowInfoDrag) this.showInfoCard = !this.showInfoCard;
      this.isShowInfoDrag = false;
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    hideandshow() {
      this.show = !this.show;
    },
    downloadInfoData() {
      saveData(this.json2csv(), `${Date.now()}_slopeData.csv`);
    },
    isShowGridLinesMethod(show) {
      let stage = this.$refs.stage.getStage();
      show ? _gridLinesGroup.show() : _gridLinesGroup.hide();
      stage.draw();
    },
    initSlopeBox() {
      const slopeBox = this.$refs.mrect;
      const stage = this.$refs.stage.getStage();
      const angle = this.angle;
      slopeBox
        .getStage()
        .setX(this.offsetX + this.cubeLength * Math.sin(deg2rad(angle)));
      slopeBox
        .getStage()
        .setY(this.offsetY - this.cubeLength * Math.cos(deg2rad(angle)));
      stage.draw();
    },
    updateBreakPointsLengthLocation(oldVal) {
      const vm = this;
      const k = (vm.ratio2cm * oldVal) / 1000;
      if (vm.slopeBreakPoint.length > 0) {
        vm.slopeBreakPoint.forEach(element => {
          element.circle.setX(
            (element.circle.getX() - vm.offsetX) * k + vm.offsetX
          );
          element.circle.setY(
            (element.circle.getY() - vm.offsetY) * k + vm.offsetY
          );
        });
      }
      vm.$refs.stage.getStage().draw();
    },
    updateBreakPointsAngleLocation(angle) {
      const vm = this;
      const kSin = Math.sin(deg2rad(angle)) * vm.ratio2cm;
      const kCos = Math.cos(deg2rad(angle)) * vm.ratio2cm;
      if (vm.slopeBreakPoint.length > 0) {
        vm.slopeBreakPoint.forEach(element => {
          element.circle.setX(
            (element.bp + vm.inputCubeLength) * kCos + vm.offsetX
          );
          element.circle.setY(
            (element.bp + vm.inputCubeLength) * kSin + vm.offsetY
          );
        });
      }
      vm.$refs.stage.getStage().draw();
    },
    updateGridLines() {
      const vm = this;
      if (_gridLinesLayer !== undefined) {
        _gridLinesLayer.destroy();
      }
      let layer = new Konva.Layer();
      let stage = vm.$refs.stage.getStage();
      let group = new Konva.Group();
      let width = 1800; //stage.getWidth();
      let height = 1800; //stage.getHeight();
      //console.log(stage.getWidth(), stage.getHeight())
      const cos = Math.cos(deg2rad(this.angle));
      const sin = Math.sin(deg2rad(this.angle));
      const cellLength = this.ratio2cm / 10;
      // 如果使用一般網隔線
      if (vm.gridLineStyle) {
        for (let i = 0; i < width / cellLength; i++) {
          group.add(
            new Konva.Line({
              points: [
                0,
                Math.round(i * cellLength),
                width,
                Math.round(i * cellLength)
              ],
              stroke: "#ddd",
              strokeWidth: 1
            })
          );
        }
        for (let i = 0; i < height / cellLength; i++) {
          group.add(
            new Konva.Line({
              points: [
                Math.round(i * cellLength),
                0,
                Math.round(i * cellLength),
                height
              ],
              stroke: "#ddd",
              strokeWidth: 1
            })
          );
        }
      } else {
        let xOffset;
        let yOfsset;
        if (this.angle < 45) {
          xOffset = 0;
          yOfsset = -900;
        } else {
          xOffset = 900;
          yOfsset = -900;
        }

        for (let i = 0; i < width / cellLength; i++) {
          // 座標旋轉運算
          let x11 = 0;
          let x21 = width;
          let y11 = Math.round(i * cellLength);
          let y21 = Math.round(i * cellLength);
          let x12 = x11 * cos - y11 * sin + xOffset; //+ width / 2
          let x22 = x21 * cos - y21 * sin + xOffset; //+ width / 2
          let y12 = x11 * sin + y11 * cos + yOfsset; //- width / 2
          let y22 = x21 * sin + y21 * cos + yOfsset; //- width / 2
          group.add(
            new Konva.Line({
              points: [x12, y12, x22, y22],
              stroke: "#ddd",
              strokeWidth: 1
            })
          );
        }
        for (let i = 0; i < height / cellLength; i++) {
          // 座標旋轉運算
          let x11 = Math.round(i * cellLength);
          let x21 = Math.round(i * cellLength);
          let y11 = 0;
          let y21 = height;
          let x12 = x11 * cos - y11 * sin + xOffset;
          let x22 = x21 * cos - y21 * sin + xOffset;
          let y12 = x11 * sin + y11 * cos + yOfsset;
          let y22 = x21 * sin + y21 * cos + yOfsset;
          group.add(
            new Konva.Line({
              points: [x12, y12, x22, y22],
              stroke: "#ddd",
              strokeWidth: 1
            })
          );
        }
      }

      layer.add(group);
      if (!this.isShowGridLines) group.hide();
      stage.add(layer);
      stage.draw();
      _gridLinesLayer = layer;
      _gridLinesGroup = group;
    },
    updateAngleDraw(angle) {
      const vm = this;
      let val = angle;
      /*** 處理角度值顯示 ***/
      // 印出新的角度值，並放到適當的位置
      let xLen = vm.$data.slope_len * Math.cos(deg2rad(val));
      let yLen = vm.$data.slope_len * Math.sin(deg2rad(val));

      // 三角形斜邊斜率 tan(θ)
      const slopeRate = Math.sin(deg2rad(val)) / Math.cos(deg2rad(val));
      // 用斜率公式計算不超過三角型高度的值 => y = tan(θ) * x
      const yPointHeight =
        slopeRate *
        (xLen - this.configAngleArc.outerRadius * Math.cos(deg2rad(val / 2)));
      // 計算可用高度
      const yHeight =
        yLen -
        yPointHeight -
        this.configAngleArc.outerRadius * Math.sin(deg2rad(val / 2));
      // 字體大小對應實際像素高度大約1.666 => 倒數為0.6
      let fontSize = yHeight / 0.6;

      // 如果字體大小超過30則保持30
      this.configAngleText.fontSize = fontSize < 30 ? fontSize : 30;
      fontSize = this.configAngleText.fontSize;

      let angleText = val + "°";

      // 取出字體寬度
      let fontWidth = fontSize * angleText.length * 0.65;

      // 計算文字位置
      this.configAngleText.x =
        vm.$data.offsetX +
        xLen -
        this.configAngleArc.outerRadius * Math.cos(deg2rad(val / 2)) -
        fontWidth;

      // getStage().textHeight 目前是取回字體大小，而非像素高度，所以乘以0.6修正為像素大小。
      this.configAngleText.y =
        vm.$data.offsetY +
        yLen -
        this.configAngleArc.outerRadius * Math.sin(deg2rad(val / 2)) -
        fontSize * 0.6;
      //console.log(this.$refs.angleText.getStage().textWidth, fontSize)
      // 顯示角度字串內容
      this.configAngleText.text = val + "°";

      /*** 處理角度圓弧標記 ***/
      // 保持圓心在三角形右下的頂點
      this.configAngleArc.x = vm.$data.offsetX + xLen;
      this.configAngleArc.y = vm.$data.offsetY + yLen;
      // 與三角形夾角相同畫弧
      this.configAngleArc.angle = val;
    },
    initAnim() {
      console.log("init anim");

      const vm = this;
      vm.isSimBegin = false;
      _data = [];
      vm.log = [];
      // 滑到底部公式為 其中 L：斜波長度 θ：斜面與平面夾角 g：為重力加速度
      //                __________
      //               /   2 * L
      //              / ———————————
      //             √  g * sin(θ)
      const lastTime = Math.sqrt(
        (2 * (vm.slope_len - vm.$data.cubeLength)) /
          vm.ratio2cm /
          (vm.g * Math.sin(deg2rad(vm.angle)))
      );
      vm.disp = 0;
      this.accel = (this.g * Math.sin(deg2rad(this.angle))).toFixed(2);
      const anim = new Konva.Animation(function(frame) {
        const t = (animTime / 1000) * vm.ratioTime;
        const sin = Math.sin(deg2rad(vm.$data.angle));
        const cos = Math.cos(deg2rad(vm.$data.angle));
        animTime += frame.timeDiff;
        // 滑動位移計算使用
        let d = {
          x: 0.5 * vm.$data.g * sin * t * t * cos,
          y: 0.5 * vm.$data.g * sin * t * t * sin,
          frame: frame
        };
        // 判斷是否結束，並且判斷目前留下來的斷點是否還有小於結束時間的，如果有則進入下一個斷點
        if (
          t <= lastTime ||
          (vm.slopeBreakPoint.length > 0 &&
            vm.slopeBreakPoint[0].bp < vm.inputSlopeLength - vm.inputCubeLength)
        ) {
          // 判斷是否有斷點及達到斷點
          if (
            vm.slopeBreakPoint.length !== 0 &&
            vm.slopeBreakPoint[0].bp <= vm.disp
          ) {
            // 取出中斷位移目標並換算
            d = {
              x: vm.slopeBreakPoint[0].bp * cos,
              y: vm.slopeBreakPoint[0].bp * sin,
              frame
            };
            // 取出當的中斷點，等待下次動畫在開始時進行清除
            _breakPointGone = _breakPointGroup.find(
              `#${vm.slopeBreakPoint[0].id}`
            );
            _breakPointGoneBp.bp = vm.slopeBreakPoint[0].bp;
            // 暫停動畫
            _anim.stop();
            vm.is_ani_start = false;
            vm.disp = vm.slopeBreakPoint[0].bp;
            vm.tickTime = Math.sqrt(
              (2 * vm.disp) / (vm.g * Math.sin(deg2rad(vm.angle)))
            ).toFixed(2);
            vm.vol = (vm.accel * vm.tickTime).toFixed(2);
            animTime = Number(vm.tickTime * 1000);
            // 更新操作履歷
            vm.pushSlopeLog();
            // 清除當前的中斷點
            vm.slopeBreakPoint.shift();
          } else {
            // 更新碼表數值
            vm.disp = Math.sqrt(d.x * d.x + d.y * d.y).toFixed(2);
            vm.tickTime = Math.sqrt(
              (2 * vm.disp) / (vm.g * Math.sin(deg2rad(vm.angle)))
            ).toFixed(2);
            vm.vol = (vm.accel * vm.tickTime).toFixed(2);
          }
          vm.$refs.mrect.rotate = deg2rad(vm.$data.angle);
          vm.$refs.mrect
            .getStage()
            .setX(
              vm.$data.offsetX +
                vm.$data.cubeLength * sin +
                d.x * vm.$data.ratio2cm
            );
          vm.$refs.mrect
            .getStage()
            .setY(
              vm.$data.offsetY -
                vm.$data.cubeLength * cos +
                d.y * vm.$data.ratio2cm
            );
        } else {
          vm.tickTime = lastTime.toFixed(2);
          vm.vol = (vm.accel * lastTime).toFixed(2);
          const d = {
            x: 0.5 * vm.$data.g * sin * lastTime * lastTime * cos,
            y: 0.5 * vm.$data.g * sin * lastTime * lastTime * sin,
            frame: frame
          };
          vm.disp = Math.sqrt(d.x * d.x + d.y * d.y).toFixed(2);
          //console.log(lastTime);
          vm.$refs.mrect
            .getStage()
            .setX(
              vm.$data.offsetX +
                vm.$data.cubeLength * sin +
                d.x * vm.$data.ratio2cm
            );
          vm.$refs.mrect
            .getStage()
            .setY(
              vm.$data.offsetY -
                vm.$data.cubeLength * cos +
                d.y * vm.$data.ratio2cm
            );
          vm.pushSlopeLog();
          vm.dismissCountDown = vm.dismissSecs;
          vm.slopeBreakPoint = [];
          if (_breakPointGroup !== null && "destroy" in _breakPointGroup) {
            _breakPointGroup.destroy();
            _breakPointGroup = null;
          }
          vm.$refs.breakPoinLyaer.getStage().draw();
          // 斜坡運結束 停下動畫
          _anim.stop();
          animTime = 0;
          _anim = undefined;
          vm.is_ani_start = false;
          vm.isSimBegin = false;
        }
      }, vm.$refs.layer.getStage());
      _anim = anim;
    },

    startAniBtn() {
      // 判斷是否有完成斷點，如果有則清除
      if (_breakPointGone !== null && "destroy" in _breakPointGone) {
        // 將剛剛斷點的紅點移除
        _breakPointGone.destroy();
        _breakPointGone = null;
        // 刷新斷點圖層
        this.$refs.breakPoinLyaer.getStage().draw();
      }
      if (_anim === undefined) this.initAnim();
      this.$data.is_ani_start = true;
      this.$data.isSimBegin = true;
      _anim.start();
      axios
        .post("/api/slope", {
          angle: this.angle,
          mass: this.mass,
          length: this.inputSlopeLength
        })
        .then(function(response) {
          console.log(response);
        })
        .catch(function(error) {
          console.log(error);
        });
    },
    pauseAniBtn() {
      if (_anim !== undefined) {
        if ("stop" in _anim) {
          this.$data.is_ani_start = false;
          _anim.stop();
        }
      }
    },
    resetAniBtn() {
      //this.is_ani_start = true;

      if (_anim !== undefined) {
        if ("stop" in _anim) {
          this.is_ani_start = false;

          _anim.stop();
          this.initAnim();
          // 初始化座標點
          this.initSlopeBox();
          //_anim.start();
        }
      }
      // 停在動畫結束
      else {
        this.initAnim();
        this.initSlopeBox();
        //_anim.start();
      }
      this.clearBreakPoint();
      this.tickTime = 0;
      this.vol = 0;
      this.disp = 0;
      animTime = 0;
    },
    clearBreakPoint() {
      if (_breakPointGroup !== null && "destroy" in _breakPointGroup) {
        _breakPointGroup.destroy();
        _breakPointGroup = null;
      }
      this.slopeBreakPoint.length = 0;
      this.$refs.breakPoinLyaer.getStage().draw();
    },
    drawBreakPoint() {
      const vm = this;
      const sin = Math.sin(deg2rad(vm.$data.angle));
      const cos = Math.cos(deg2rad(vm.$data.angle));

      // 滑動位移計算使用
      const d = {
        x:
          (vm.inputSlopeBreakPoint + vm.inputCubeLength) * cos * vm.ratio2cm +
          vm.offsetX,
        y:
          (vm.inputSlopeBreakPoint + vm.inputCubeLength) * sin * vm.ratio2cm +
          vm.offsetY
      };
      // 取得中斷點圖層
      const layer = vm.$refs.breakPoinLyaer;
      // 如果中斷點群組尚未建構，則建構
      if (_breakPointGroup === null) {
        _breakPointGroup = new Konva.Group();
      }
      // 新增中斷點圓圈標示
      let circle = new Konva.Circle({
        fill: "#ff0000ee",
        radius: 5,
        x: d.x,
        y: d.y
      });

      const tooltip = new Konva.Text({
        text: "",
        fontFamily: "Calibri",
        fontSize: 16,
        //padding: 5,
        visible: false,
        fill: "black",
        opacity: 0.75,
        textFill: "red"
      });
      // 設定中斷點圓圈id
      circle.id(`bp-${uuidGen()}`);
      // 中斷點mouseup事件綁定
      circle.on("mouseup", function(e) {
        // 找到要刪除中斷點的id
        const removeId = vm.slopeBreakPoint.findIndex(element => {
          return element.id === e.target.attrs.id;
        });
        // 從中斷點陣列中移除
        vm.slopeBreakPoint.splice(removeId, 1);
        // 隱藏提示
        tooltip.hide();
        // 清除中斷點圓圈
        e.target.destroy();
        // 刷新圖層
        vm.$refs.stage.getStage().draw();
      });

      circle.on("mouseover", function(e) {
        const id = vm.slopeBreakPoint.findIndex(element => {
          return element.id === e.target.attrs.id;
        });
        const cxt = e.target.attrs;
        tooltip.position({
          x: cxt.x + 10,
          y: cxt.y - 10
        });
        const text =
          id === -1 ? _breakPointGoneBp.bp : vm.slopeBreakPoint[id].bp;
        tooltip.text(text);
        // 斷點提示顯示移動到最上層
        tooltip.moveToTop();
        // 顯示斷點提示
        tooltip.show();
        // 刷新圖層
        vm.$refs.stage.getStage().draw();
      });
      circle.on("mouseout", function() {
        // 隱藏斷點提示
        tooltip.hide();
        vm.$refs.stage.getStage().draw();
      });
      // 將中斷點放入陣列
      vm.slopeBreakPoint.push({
        bp: vm.inputSlopeBreakPoint,
        id: circle.id(),
        circle
      });

      // 排序位移索引(小到大 => 近到遠)
      sort(vm.slopeBreakPoint).asc(e => e.bp);
      // 將中斷點圓圈加入群組
      _breakPointGroup.add(circle);
      // 將中斷點圓圈加入群組
      _breakPointGroup.add(tooltip);
      // 將中斷群組加入中斷圖層
      layer.getStage().add(_breakPointGroup);
      // 刷新中斷圖層
      layer.getStage().draw();
    },

    pushSlopeLog() {
      const vm = this;
      // 紀錄最終結果(提供歷史紀錄表格使用)
      const record = {
        id: vm.logKeyId,
        time: vm.tickTime,
        accel: vm.accel,
        angle: vm.angle,
        slopeLen: vm.slope_len,
        disp: vm.disp,
        vol: vm.vol,
        g: vm.g,
        mass: vm.mass
      };
      vm.logKeyId++;
      vm.dataLog.push(record);
    },
    onUltraImgLoad(size) {
      this.ultraWidth = size.width;
      this.ultraHeight = size.height;
      this.ultraImageRender();
      console.log(size);
    },

    ultraImageRender() {
      const vm = this;
      // 取得超音波圖片物件
      const ultraImage = vm.$refs.ultraImage.$children[0].getStage();

      // 設定圖片縮放比例
      ultraImage.width(vm.ultraWidth * (1 / vm.inputSlopeLength));
      ultraImage.height(vm.ultraHeight * (1 / vm.inputSlopeLength));

      // 取得大邊來確保不會吃掉滑動方塊
      const bigSide =
        ultraImage.width() > ultraImage.height()
          ? ultraImage.width()
          : ultraImage.height();

      const rect = vm.$refs.mrect.getStage();
      // 確保滑動方塊的數值已經更新，才計算超音波位置
      vm.$nextTick(() => {
        const offsetX =
          rect.getX() -
          bigSide * Math.cos(deg2rad(vm.angle)) +
          26 * (1 / vm.inputSlopeLength);
        const offsetY =
          rect.getY() -
          bigSide * Math.sin(deg2rad(vm.angle)) -
          30 * (1 / vm.inputSlopeLength);
        vm.configUltraImg.rotation = vm.angle;
        ultraImage.setX(offsetX);
        ultraImage.setY(offsetY);

        ultraImage.draw();
      });
    }
  },

  mounted() {

    // 綁定訊息監聽事件
    (function setInfoEventListener() {
      const ultraImage = this.$refs.ultraImage.$children[0].getStage();
      ultraImage.on("mouseover", () => {
        this.configInfoText.text = "超音波感測器";
      });
      ultraImage.on("mouseleave", () => {
        this.configInfoText.text = "";
      });
      const mrect = this.$refs.mrect.getStage();
      mrect.on("mouseover", () => {
        this.configInfoText.text = "滑車";
      });
      mrect.on("mouseleave", () => {
        this.configInfoText.text = "";
      });
      const triangle = this.$refs.base.getStage();
      triangle.on("mouseover", () => {
        this.configInfoText.text = "滑軌";
      });
      triangle.on("mouseleave", () => {
        this.configInfoText.text = "";
      });
    }).call(this);

    this.ratio2cm = (1 / this.inputSlopeLength) * 1000;
    this.dragRealTime.boundingElement = this.$refs.container;
    //console.log(window.devicePixelRatio);
    const vm = this;
    //this.ratio2cm = (getDPI() / 2.54).toFixed(2); //20;
    // console.log(
    //   `This Computer DPI is ${getDPI()} , to The CM is ${this.ratio2cm}`
    // );
    this.baseConfig = {
      sceneFunc: function(context, shape) {
        context.beginPath();
        // 位移單位座標
        context.moveTo(vm.$data.offsetX, vm.$data.offsetY + 0);
        context.lineTo(
          vm.$data.offsetX,
          vm.$data.offsetY +
            0 +
            vm.$data.slope_len * Math.sin(deg2rad(vm.$data.angle))
        );
        context.lineTo(
          vm.$data.offsetX +
            vm.$data.slope_len * Math.cos(deg2rad(vm.$data.angle)),
          vm.$data.offsetY +
            0 +
            vm.$data.slope_len * Math.sin(deg2rad(vm.$data.angle))
        );
        context.closePath();

        // special Konva.js method
        context.fillStrokeShape(shape);
      },
      fill: "#00D2FF"
    };
    vm.configKonva = {
      width:
        vm.slope_len * Math.cos(deg2rad(this.angle)) +
        this.offsetX +
        this.configRect.height * 2,
      height: vm.slope_len * Math.sin(deg2rad(this.angle)) + this.offsetY
    };
    this.cubeLength = vm.inputCubeLength * vm.ratio2cm;
    vm.configRect = {
      x:
        vm.offsetX +
        vm.$data.cubeLength *
          Math.cos(deg2rad(vm.$data.angle)) *
          Math.tan(deg2rad(vm.$data.angle)),
      y:
        vm.offsetY -
        (vm.$data.cubeLength * Math.sin(deg2rad(vm.$data.angle))) /
          Math.tan(deg2rad(vm.$data.angle)),
      rotation: vm.angle,
      width: vm.$data.cubeLength,
      height: vm.$data.cubeLength,
      fill: vm.onMassChange(vm.$refs.massInput.value)
    };

    // 更新角度值
    this.updateAngleDraw(vm.angle);

    this.updateGridLines();

    // 更新超音波元件位置
    this.ultraImageRender();

    // 取得使用者電腦的DPI(pixel/inch)，用於計算實際長度
    //getDPI();
    if (this.is_ani_start) {
      this.initAnim();
      _anim.start();
    }
    this.dragRealTime.resetInitialPos = false;
  },

  // 當此離開此元件路由
  beforeRouteLeave(to, from, next) {
    // 確認動畫事件是否有建構
    if (_anim !== undefined) {
      if ("stop" in _anim) {
        // 停下當前動畫
        _anim.stop();
        _anim = undefined;
        this.is_ani_start = false;
      }
    }

    // 轉交所有權給下一個路由事件
    next();
  }
};
</script>
