<template>
  <b-form-group
    :label="id"
    :label-for="componentID"
    :invalid-feedback="invalidFeedback"
    :state="state"
  >
    <template v-if="type === 'range'">
      <b-input-group>
        <b-form-input
          :id="componentID"
          :type="type"
          :min="min"
          :max="max"
          :step="step"
          :state="state"
          :disabled="disable"
          v-model="rangeValue"
          @focus.native="onFocus"
          @blur.native="onBlur"
          class="form-control"
        />
        <b-input-group-append>
          <b-form-input
            style="max-width:80px;"
            type="number"
            :min="min"
            :max="max"
            :step="step"
            :state="state"
            :disabled="disable"
            :value="rangeValue"
            @input.native="onRangeInputChange"
            @focus.native="onFocus"
            @blur.native="onBlur"
          />
          <b-input-group-text>{{unit}}</b-input-group-text>
          <b-button :disabled="disable" @click="onClickRangeInputSetup" variant="success">設定</b-button>
          <b-button :disabled="disable" @click="onClickRangeInputClear" variant="outline-danger">刪除</b-button>
        </b-input-group-append>
      </b-input-group>
    </template>
    <template v-else>
      <b-input-group size="md" :append="unit">
        <b-form-input
          :id="componentID"
          :type="type"
          :min="min"
          :max="max"
          :step="step"
          :state="state"
          :disabled="disable"
          :value="value"
          @change.native="onInputChange"
          @focus.native="onFocus"
          @blur.native="onBlur"
        ></b-form-input>
      </b-input-group>
    </template>
    <b-form-text v-show="isFocus">數值範圍在{{min}}至{{max}}</b-form-text>
  </b-form-group>
</template>
<script>
import debounce from "lodash.debounce";
let componentID = 0;
export default {
  data() {
    return {
      state: null,
      isFocus: false,
      componentID: null,
      rangeValue: this.value
    };
  },
  // 父元件丟入參數
  props: {
    value: Number,
    step: Number,
    min: Number,
    max: Number,
    id: String,
    unit: String,
    disable: Boolean,
    type: { type: String, default: "number" }
  },
  methods: {
    // 設定當100ms不改變
    onInputChange: debounce(function(event) {
      const val = parseFloat(event.target.value);
      const vm = this;
      if (val <= this.max && val >= this.min) {
        // 使用 kebab-case 命名(不區分大小寫)
        // 當內部值改變發出改變的數值，給父元件進行更新(雙向綁定)
        this.$emit("update:on-value-change", val);
      }
      this.state = val <= vm.max && val >= vm.min ? true : false;
      this.isFocus = this.state;
    }, 100),
    onRangeInputChange: function(event) {
      const val = parseFloat(event.target.value);
      const vm = this;
      if (val <= this.max && val >= this.min) {
        this.rangeValue = event.target.value;
      }
      this.state = val <= vm.max && val >= vm.min ? true : false;
      this.isFocus = this.state;
    },
    onFocus(event) {
      const vm = this;
      if (vm.state || vm.state === null) vm.isFocus = true;
    },
    onBlur(event) {
      this.isFocus = false;
    },
    onClickRangeInputSetup() {
      // 使用 kebab-case 命名(不區分大小寫)
      // 當內部值改變發出改變的數值，給父元件進行更新(雙向綁定)
      this.$emit("update:on-value-change", parseFloat(this.rangeValue));
      this.$emit("setup-value", parseFloat(this.rangeValue));
    },
    onClickRangeInputClear() {
      this.$emit("on-clear-btn-click");
    }
  },
  computed: {
    labelTitleToId() {
      return this.item.id.replace(/\(|\)|\ /gi, "-");
    },
    invalidFeedback() {
      return `數值範圍在${this.min}至${this.max}`;
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
