<template>
  <b-form-group :label="item.id" :label-for="componentID">
    <b-input-group size="md" :append="item.unit">
      <b-form-input
        :id="componentID"
        type="number"
        :min="item.min"
        :max="item.max"
        :step="item.step"
        :state="item.state"
        :value="item.value"
        @change.native="onInputChange"
        @focus.native="onFocus"
        @blur.native="onBlur"
      ></b-form-input>
    </b-input-group>
    <b-form-text v-show="item.isFocus">數值範圍在{{item.min}}至{{item.max}}</b-form-text>
    <b-form-invalid-feedback id="errorFeedback">數值範圍在{{item.min}}至{{item.max}}</b-form-invalid-feedback>
  </b-form-group>
</template>
<script>
let componentID = 0;
export default {
  data() {
    return {
      item: {
        value: this.$props.value || 0,
        step: this.$props.step,
        min: this.$props.min,
        max: this.$props.max,
        id: this.$props.id,
        unit: this.$props.unit,
        state: null,
        isFocus: false
      },
      componentID: null
    };
  },
  watch: {
    // 監聽外部父元件的數值是否改變
    value(val) {
      this.item.value = val;
    },
    "item.value"(val) {
      const vm = this.item;
      if (val <= vm.max && val >= vm.min) {
        // 使用 kebab-case 命名(不區分大小寫)
        // 當內部值改變發出改變的數值，給父元件進行更新(雙向綁定)
        this.$emit("update:on-value-change", vm.value);
      }
    }
  },
  // 父元件丟入參數
  props: {
    value: Number,
    step: Number,
    min: Number,
    max: Number,
    id: String,
    unit: String
  },
  methods: {
    onInputChange(event) {
      const val = parseFloat(event.target.value);
      const vm = this.item;
      vm.value = val;
      vm.state = val <= vm.max && val >= vm.min ? true : false;
    },
    onFocus(event) {
      const vm = this.item;
      if (vm.state || vm.state === null) vm.isFocus = true;
    },
    onBlur(event) {
      this.item.isFocus = false;
    }
  },
  computed: {
    labelTitleToId() {
      return this.item.id.replace(/\(|\)|\ /gi, "-");
    }
  },
  beforeCreate() {
    componentID += 1;
  },
  created() {
    this.componentID = "ri-" + componentID.toString();
  }
};
</script>
