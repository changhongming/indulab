<template>
  <b-form-group :label="id" :label-for="id">
    <b-form-input
      :id="item.id"
      type="number"
      :min="item.min"
      :max="item.max"
      :step="item.step"
      :state="item.state"
      :value="item.value"
      @change.native="onInputChange"
    ></b-form-input>
    <b-form-invalid-feedback id="errorFeedback">
      數值範圍在{{item.min}}至{{item.max}}
    </b-form-invalid-feedback>
  </b-form-group>
</template>
<script>
export default {
  data() {
    return {
      item: {
        value: this.$props.value || 0,
        step: this.$props.step,
        min: this.$props.min,
        max: this.$props.max,
        id: this.$props.id,
        state: null
      }
    };
  },
  watch: {
    // 監聽外部父元件的數值是否改變
    value(val) {
      this.item.value = val;
    },
    'item.value'(val) {
      // 使用 kebab-case 命名(不區分大小寫)
      // 當內部值改變發出改變的數值，給父元件進行更新(雙向綁定)
      this.$emit("update:on-value-change", val);
    }
  },
  // 父元件丟入參數
  props: ["value", "step", "min", "max", "id"],
  methods: {
    onInputChange(event) {
      const val = parseFloat(event.target.value);
      const vm = this.item;
      vm.value = val;
      vm.state = val <= vm.max && val >= vm.min ? true : false;
    },
  }
};
</script>
