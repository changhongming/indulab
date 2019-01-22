<template>
  <b-container>
    <!-- 說明dialog -->
    <b-btn v-b-modal.tutorial>使用教學</b-btn>
    <b-modal id="tutorial" title="教學手冊" ok-only centered>Hello From My Modal!</b-modal>

    <form>
      <b-row>
        <b-col>
          <b-form-group label="G Force" label-for="input_g">
            <b-form-input
              id="input_g"
              type="number"
              min="1"
              max="90"
              step="0.1"
              :value="g"
              @change.native="g = $event.target.value"
              :disabled="is_ani_start"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Angle" label-for="input_angle">
            <b-form-input
              id="input_angle"
              type="number"
              min="1"
              max="90"
              :value="angle"
              @change.native="angle = $event.target.value"
              :disabled="is_ani_start"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Time of Ratio" label-for="input_ratioTime">
            <b-form-input
              id="input_ratioTime"
              type="number"
              min="0.1"
              max="20"
              step="0.1"
              v-model.lazy="ratioTime"
              :disabled="is_ani_start"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Slope Length" label-for="input_slopeLen">
            <b-form-input
              id="input_slopeLen"
              type="number"
              min="100"
              max="1500"
              step="1"
              :value="slope_len"
              @change.native="slope_len = $event.target.value"
              :disabled="is_ani_start"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <b-form-group label="Mass" label-for="input_mass">
            <b-form-input id="input_mass" v-model.lazy="mass" :disabled="is_ani_start"></b-form-input>
          </b-form-group>
        </b-col>
        <b-col>
          <!-- <p>time:{{tickTime}} 秒</p>
          <p>accl:{{accel}} 公分/秒^2</p>
          <p>vol:{{vol}} 公分/秒</p>
          <p>disp:{{disp}} 公分</p> -->
        </b-col>
      </b-row>
    </form>
    <b-row>
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
        <table class="table table-striped" v-if="show">
          <thead>
            <tr>
              <th scope="col" v-for="(item, index) in columns" :key="`th-${index}`">{{item.content}}</th>
            </tr>
          </thead>
          <tbody>
            <tr scope="row" v-for="(item, index) in dataLog" :key="`th-${index}`">
              <td v-for="(key, index) in columns" :key="`td-${index}`">{{item[key.key]}}</td>
            </tr>
          </tbody>
        </table>
      </transition>
    </b-row>
    <b-row>
      <b-col>
        <b-check v-model="isShowGridLines">
          <span>{{isShowGridLines ? "顯示" : "隱藏"}}</span>網格線(1cm)
        </b-check>
        <b-check v-model="gridLineStyle">
          <span>{{gridLineStyle ? "一般" : "平行斜坡"}}</span>網格線(1cm)
        </b-check>
        <template v-if="is_ani_start">
          <button class="btn btn-primary" @click="stop_ani_btn" :disabled="!is_ani_start">
            <i class="fas fa-stop"></i> pause
          </button>
        </template>
        <template v-else>
          <button class="btn btn-primary" @click="start_ani_btn" :disabled="is_ani_start">
            <i class="fas fa-play"></i> start
          </button>
        </template>
        <button class="btn btn-danger" @click="reset_ani_btn">
          <i class="fas fa-undo"></i> reset
        </button>
      </b-col>
    </b-row>

    <b-row>
      <b-col>
        <!-- v-bind:class="{ grid1cm : isActive}" -->
        <v-stage ref="stage" :config="configKonva" @click="test">
          <v-layer ref="layer">
            <!-- <v-circle
          ref="hexagon"
          :config="{
          x: 40,
          y: 40 - 20,
          radius: 20,
          fill: 'red',
          }"
            />-->
            <v-rect ref="mrect" :config="configRect"/>
            <v-shape ref="base" :config="baseConfig"/>
          </v-layer>
          <v-layer ref="gridLayer"></v-layer>
        </v-stage>
      </b-col>
    </b-row>
    <b-card draggable="true" bg-variant="primary"
            text-variant="white"
            header="Primary"
            class="text-center">
          <p>time:{{tickTime}} 秒</p>
          <p>accl:{{accel}} 公分/秒^2</p>
          <p>vol:{{vol}} 公分/秒</p>
          <p>disp:{{disp}} 公分</p>
    </b-card>
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
.alert-fixed {
  position: fixed;
  bottom: 5px;
  right: 4px;
  width: 40%;
  z-index: 9999;
  border-radius: 4px;
}

.grid1cm {
  position: relative;
  /* overflow: hidden; */
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
import SimpleVueValidation from "simple-vue-validator";
const Validator = SimpleVueValidation.Validator;
console.log(Validator);
let _anim;
let _gridLinesGroup;
let _gridLinesLayer;
function deg2rad(deg) {
  return (Math.PI * deg) / 180;
}

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
  data() {
    return {
      email: "ss",
      // 歷史紀錄欄位轉換表
      columns: [
        { key: "id", content: "#" },
        { key: "g", content: "重力加速度" },
        { key: "angle", content: "傾斜角度" },
        { key: "slopeLen", content: "斜坡長度" },
        { key: "vol", content: "結束瞬時速度" },
        { key: "disp", content: "結束位移" },
        { key: "time", content: "結束時間" }
      ],
      // 歷史紀錄資料
      dataLog: [],
      // alert message 屬性
      dismissSecs: 5,
      dismissCountDown: 0,
      showDismissibleAlert: false,
      logKeyId: 0,
      show: true,
      mass: 1,
      isShowGridLines: true,
      // 如果為 true 則隔線為平行斜坡，為false 則為水平垂直線
      gridLineStyle: true,
      configKonva: {
        width: 2000,
        height: 1000
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
      is_ani_start: false,
      tickTime: 0,
      g: 9.8,
      vol: 0,
      accel: 0,
      disp: 0,
      angle: 45,
      ratioTime: 1,
      slope_len: 1000,
      offsetX: 0,
      offsetY: 37.8 * 2,
      ratio2cm: 37.8,
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
  validators: {
    email: function(value) {
      return Validator.value(value)
        .required()
        .digit()
        .between(1, 90);
    }
  },
  // 請不要在這邊調用箭頭函式，否則無法有效地指到vue的this
  watch: {
    g: function(val) {
      this.accel = val * Math.sin(deg2rad(this.angle)).toFixed(2);
    },
    slope_len: function(val, oldVal) {
      this.vaildSlopeLen(val, oldVal);
      this.configKonva = {
        width:
          val * Math.cos(deg2rad(this.angle)) +
          this.offsetX +
          this.configRect.height * 2,
        height: val * Math.sin(deg2rad(this.angle)) + this.offsetY
      };
      this.configRect = {
        x:
          this.offsetX +
          50 * Math.cos(deg2rad(this.angle)) * Math.tan(deg2rad(this.angle)),
        y:
          this.offsetY -
          (50 * Math.sin(deg2rad(this.angle))) / Math.tan(deg2rad(this.angle)),
        rotation: Number(this.angle),
        width: 50,
        height: 50,
        fill: "green"
      };
      if (_anim !== undefined) _anim = undefined;
      //console.log(this.configKonva);
    },
    angle: function(val, oldVal) {
      // 計算加速度
      this.accel = (this.g * Math.sin(deg2rad(val))).toFixed(2);
      this.vaildAngle(val, oldVal);
      this.configKonva = {
        width:
          this.slope_len * Math.cos(deg2rad(val)) +
          this.offsetX +
          this.configRect.height * 2,
        height: this.slope_len * Math.sin(deg2rad(val)) + this.offsetY
      };
      this.configRect = {
        x: this.offsetX + 50 * Math.cos(deg2rad(val)) * Math.tan(deg2rad(val)),
        y:
          this.offsetY - (50 * Math.sin(deg2rad(val))) / Math.tan(deg2rad(val)),
        rotation: Number(val),
        width: 50,
        height: 50,
        fill: "green"
      };
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
    }
  },

  methods: {
    test(vueComponent) {
      const mousePos = this.$refs.stage.getStage().getPointerPosition();
      console.log(vueComponent.target, mousePos);
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    hideandshow() {
      this.show = !this.show;
      console.log(this.show);
    },
    submit: function() {
      this.$validate().then(function(success) {
        if (success) {
          alert("Validation succeeded!");
        }
      });
    },
    isShowGridLinesMethod(show) {
      let stage = this.$refs.stage.getStage();
      show ? _gridLinesGroup.show() : _gridLinesGroup.hide();
      stage.draw();
    },
    updateGridLines() {
      const vm = this;
      console.log(_gridLinesLayer);
      if (_gridLinesLayer !== undefined) {
        _gridLinesLayer.destroyChildren();
      }
      let layer = new Konva.Layer();
      let stage = vm.$refs.stage.getStage();
      let group = new Konva.Group();
      let width = 1800; //stage.getWidth();
      let height = 1800; //stage.getHeight();
      //console.log(stage.getWidth(), stage.getHeight())
      const cos = Math.cos(deg2rad(this.angle));
      const sin = Math.sin(deg2rad(this.angle));
      const cellLength = this.ratio2cm;
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
          console.log(x12, x22, y12, y22);
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
    // 驗證角度是否在範圍內
    vaildAngle(angle, oldAngle) {
      if (angle > 90 || angle <= 0) {
        this.angle = oldAngle;
      }
    },
    // 驗證斜坡長度是否在範圍內
    vaildSlopeLen(len, oldLen) {
      if (len >= 1500 || len < 100) {
        this.slope_len = oldLen;
      }
    },
    initAnim() {
      console.log("init anim");
      const vm = this;
      // 滑到底部公式為 其中 L：斜波長度 θ：斜面與平面夾角 g：為重力加速度
      //                __________
      //               /   2 * L
      //              / ———————————
      //             √  g * sin(θ)
      const lastTime = Math.sqrt(
        (2 * vm.slope_len) / vm.ratio2cm / (vm.g * Math.sin(deg2rad(vm.angle)))
      );
      this.accel = (this.g * Math.sin(deg2rad(this.angle))).toFixed(2);
      console.log(this.accel, this.g, Math.sin(deg2rad(this.angle)));
      const anim = new Konva.Animation(function(frame) {
        const t = (frame.time / 1000) * vm.ratioTime;
        const sin = Math.sin(deg2rad(vm.$data.angle));
        const cos = Math.cos(deg2rad(vm.$data.angle));
        //console.log(frame);
        // 滑動位移計算使用
        const d = {
          x: 0.5 * vm.$data.g * sin * t * t * cos,
          y: 0.5 * vm.$data.g * sin * t * t * sin,
          frame: frame
        };

        if ((frame.time / 1000) * vm.ratioTime <= lastTime) {
          vm.disp = Math.sqrt(d.x * d.x + d.y * d.y).toFixed(2);
          vm.tickTime = ((frame.time / 1000) * vm.ratioTime).toFixed(2);
          vm.vol = (vm.accel * vm.tickTime).toFixed(2);
          //console.log(d.frame.time / 1000);
          vm.$refs.mrect.rotate = deg2rad(vm.$data.angle);
          vm.$refs.mrect
            .getStage()
            .setX(
              vm.$data.offsetX +
                50 * cos * Math.tan(deg2rad(vm.$data.angle)) +
                d.x * vm.$data.ratio2cm
            );
          vm.$refs.mrect
            .getStage()
            .setY(
              vm.$data.offsetY -
                (50 * sin) / Math.tan(deg2rad(vm.$data.angle)) +
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
          console.log(lastTime);
          vm.$refs.mrect
            .getStage()
            .setX(
              vm.$data.offsetX +
                50 * cos * Math.tan(deg2rad(vm.$data.angle)) +
                d.x * vm.$data.ratio2cm
            );
          vm.$refs.mrect
            .getStage()
            .setY(
              vm.$data.offsetY -
                (50 * sin) / Math.tan(deg2rad(vm.$data.angle)) +
                d.y * vm.$data.ratio2cm
            );
          // 紀錄最終結果(提供歷史紀錄表格使用)
          const record = {
            id: vm.logKeyId,
            time: vm.tickTime,
            accel: vm.accel,
            angle: vm.angle,
            slopeLen: vm.slope_len,
            disp: vm.disp,
            vol: vm.vol,
            g: vm.g
          };
          vm.dismissCountDown = vm.dismissSecs;
          vm.dataLog.push(record);
          vm.logKeyId++;
          // 斜坡運結束 停下動畫
          _anim.stop();
          _anim = undefined;
          vm.is_ani_start = false;
        }
      }, vm.$refs.layer.getStage());
      _anim = anim;
    },

    start_ani_btn() {
      console.log(_anim, _anim === undefined);
      if (_anim === undefined) this.initAnim();
      this.$data.is_ani_start = true;
      _anim.start();
    },
    stop_ani_btn() {
      if (_anim !== undefined) {
        if ("stop" in _anim) {
          this.$data.is_ani_start = false;
          _anim.stop();
        }
      }
    },
    reset_ani_btn() {
      this.is_ani_start = true;
      if (_anim !== undefined) {
        if ("stop" in _anim) {
          _anim.stop();
          this.initAnim();
          _anim.start();
        }
      } else {
        this.initAnim();
        _anim.start();
      }
    }
  },

  mounted() {
    console.log(window.devicePixelRatio);
    const vm = this;

    this.ratio2cm = 20; //(getDPI() / 2.54).toFixed(2);
    console.log(
      `This Computer DPI is ${getDPI()} , to The CM is ${this.ratio2cm}`
    );
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
    vm.configRect = {
      x:
        vm.offsetX +
        50 *
          Math.cos(deg2rad(vm.$data.angle)) *
          Math.tan(deg2rad(vm.$data.angle)),
      y:
        vm.offsetY -
        (50 * Math.sin(deg2rad(vm.$data.angle))) /
          Math.tan(deg2rad(vm.$data.angle)),
      rotation: vm.angle,
      width: 50,
      height: 50,
      fill: "green"
    };
    this.updateGridLines();
    // 取得使用者電腦的DPI(pixel/inch)，用於計算實際長度
    //getDPI();
    if (this.is_ani_start) {
      this.initAnim();
      _anim.start();
    }
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
