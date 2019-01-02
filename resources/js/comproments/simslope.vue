<template>
  <div>
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
        /> -->
        <v-rect
          ref="mrect"
          :config="configRect"
        />
        <v-shape ref="base" :config="baseConfig"/>
      </v-layer>
    </v-stage>
    <input v-model.lazy="g" :disabled="is_ani_start">
    <input v-model.lazy="angle" :disabled="is_ani_start">
    <input v-model.lazy="ratio" :disabled="is_ani_start">
    <input v-model.lazy="slope_len" :disabled="is_ani_start">
    <button @click="start_ani_btn">start</button>
    <button @click="stop_ani_btn">stop</button>
    <button @click="reset_ani_btn">reset</button>
  </div>
</template>
<script>
var _anim = null;
function deg2rad(deg) {
  return (Math.PI * deg) / 180;
}
export default {
  data() {
    return {
      configKonva: {
        width: 2000,
        height: 1000
      },
      configRect: {
               x: 40,
               y: 40,
               width: 100,
               height: 50,
               fill: 'green',
               rotation: 45
           },
      is_ani_start: false,
      g: 9.8,
      angle: 45,
      ratio: 200,
      slope_len: 1000,
      ratio: 200,
      offsetX: 0,
      offsetY: 100,
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
  watch: {
    slope_len: function(val) {
      this.configKonva = {
        width: val * Math.cos(deg2rad(this.angle)) + this.offsetX + 40,
        height: val * Math.sin(deg2rad(this.angle)) + this.offsetY
      };
      console.log(this.configKonva);
    },
    angle: function(val) {
      this.configKonva = {
        width: this.slope_len * Math.cos(deg2rad(val)) + this.offsetX + 40,
        height: this.slope_len * Math.sin(deg2rad(val)) + this.offsetY
      };
      this.configRect = {
                x: this.offsetX + 50 * Math.cos(deg2rad(val)) * Math.tan(deg2rad(val)),
                y: this.offsetY - 50 * Math.sin(deg2rad(val)) / Math.tan(deg2rad(val)),
                rotation: Number(val),
                width: 100,
               height: 50,
               fill: 'green',
            }
      console.log(this.configRect);
    }
  },
  methods: {
    test(vueComponent) {
      const mousePos = this.$refs.stage.getStage().getPointerPosition();
      console.log(vueComponent.target, mousePos);
    },
    start_ani_btn() {
      this.$data.is_ani_start = true;
      _anim.start();
    },
    stop_ani_btn() {
      this.$data.is_ani_start = false;
      _anim.stop();
    },
    reset_ani_btn() {
      this.$data.is_ani_start = true;
      _anim.stop();
      var vm = this;
      const anim = new Konva.Animation(function(frame) {
        const t = frame.time / vm.$data.ratio;

        const d = {
          x:
            0.5 *
            vm.$data.g *
            Math.sin(deg2rad(vm.$data.angle)) *
            t *
            t *
            Math.cos(deg2rad(vm.$data.angle)),
          y:
            0.5 *
            vm.$data.g *
            Math.sin(deg2rad(vm.$data.angle)) *
            t *
            t *
            Math.sin(deg2rad(vm.$data.angle)),
          frame: frame
        };
        vm.$refs.mrect.rotate = deg2rad(vm.$data.angle);
      vm.$refs.mrect.getStage().setX(vm.$data.offsetX +  50 * Math.cos(deg2rad(vm.$data.angle)) * Math.tan(deg2rad(vm.$data.angle)) + d.x);
      vm.$refs.mrect
        .getStage()
        .setY(vm.$data.offsetY - 50 * Math.sin(deg2rad(vm.$data.angle)) / Math.tan(deg2rad(vm.$data.angle)) + d.y);
        console.log(
          "x:",
          vm.$refs.mrect.getStage().getX(),
          ",Y:",
          vm.$refs.mrect.getStage().getY(),
          " = ",
          Math.cos(deg2rad(vm.$data.angle))
        );
      }, vm.$refs.layer.getStage());
      _anim = anim;
      _anim.start();
    }
  },
  mounted() {
    const vm = this;

    // in ms
    const centerX = vm.$refs.stage.getStage().getWidth() / 2;

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
    vm.$data.configKonva = {
      width: vm.slope_len * Math.cos(deg2rad(vm.$data.angle)),
      height: vm.slope_len * Math.sin(deg2rad(vm.$data.angle))
    };

    const anim = new Konva.Animation(function(frame) {
      const t = frame.time / vm.$data.ratio;

      const d = {
        x:
          0.5 *
          vm.$data.g *
          Math.sin(deg2rad(vm.$data.angle)) *
          t *
          t *
          Math.cos(deg2rad(vm.$data.angle)),
        y:
          0.5 *
          vm.$data.g *
          Math.sin(deg2rad(vm.$data.angle)) *
          t *
          t *
          Math.sin(deg2rad(vm.$data.angle)),
        frame: frame
      };
      // 動態新增圓形
      /*
      vm.$refs.layer.getStage().add(new Konva.Circle({
        x: Math.random() * 1000,
        y: Math.random() * 1000,
        radius: Math.random() * 100,
        fill: "#fff",
        stroke: 'red',
        strokeWidth: Math.random() * 10 + 1
      }));
      */
      vm.$refs.mrect.getStage().setX(vm.$data.offsetX +  50 * Math.cos(deg2rad(vm.$data.angle)) * Math.tan(deg2rad(vm.$data.angle)) + d.x);
      vm.$refs.mrect
        .getStage()
        .setY(vm.$data.offsetY - 50 * Math.sin(deg2rad(vm.$data.angle)) / Math.tan(deg2rad(vm.$data.angle)) + d.y);
        console.log(frame);
    }, vm.$refs.layer.getStage());
    _anim = anim;
    anim.start();
  }
};
</script>
