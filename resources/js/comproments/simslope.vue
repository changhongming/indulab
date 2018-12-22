<template>
  <div>
    <v-stage ref="stage" :config="configKonva" @click="test">
      <v-layer ref="layer">
        <v-circle
          ref="hexagon"
          :config="{
          x: 30,
          y: 0,
          radius: 20,
          fill: 'red',
        }"
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
    <button @click="offset">-1</button>
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
      is_ani_start: false,
      g: 9.8,
      angle: 45,
      ratio: 200,
      slope_len: 1000,
      ratio: 200,
      offsetX: 40,
      offsetY: 40,
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
      console.log(this.configKonva);
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
      console.log("===========");
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

        vm.$refs.hexagon.getStage().setX(vm.$data.offsetX + d.x);
        // BUG 需要確認補正數值 避免除零 可能X跟Y都需要修正
        var circleOffset = Math.cos(deg2rad(vm.$data.angle)) <= 0.1 ? 1 : Math.cos(deg2rad(vm.$data.angle));
        vm.$refs.hexagon
          .getStage()
          .setY(
            vm.$data.offsetY - 20 / circleOffset + d.y
          );
        console.log("x:",vm.$refs.hexagon.getStage().getX(),",Y:",vm.$refs.hexagon.getStage().getY()," = ", Math.cos(deg2rad(vm.$data.angle)));
      }, vm.$refs.layer.getStage());
      _anim = anim;
      _anim.start();
    },
    offset() {
      console.log(this.$data.configKonva);
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

      vm.$refs.hexagon.getStage().setX(vm.$data.offsetX + d.x);
      vm.$refs.hexagon
        .getStage()
        .setY(vm.$data.offsetY - 20 / Math.cos(deg2rad(vm.$data.angle)) + d.y);
    }, vm.$refs.layer.getStage());
    _anim = anim;
    anim.start();
  }
};
</script>
