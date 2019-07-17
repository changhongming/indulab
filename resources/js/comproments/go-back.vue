<template>
  <div class="goback-container bg-primary text-white" @click="click">
    <div class="symbol">
      <i class="fas fa-arrow-alt-circle-left"></i>
    </div>
    <div class="message">{{message}}</div>
  </div>
</template>

<style scoped lang="scss">
.goback-container {
  position: absolute;
  border-top-right-radius: 20px;
  border-bottom-right-radius: 20px;
  top: 0;
  cursor: pointer;
  opacity: 0.4;

  .message {
    float: right;
    display: none;
    &:hover {
      display: inline-block;
      opacity: 1;
    }
  }

  .symbol {
    margin-right: 2px;
    float: right;
    &:hover + .message {
      display: inline-block;
      opacity: 1;
    }
  }

  &:hover {
    opacity: 1;
  }
}
</style>

<script>
export default {
  props: {
    message: {
      type: String,
      default: "返回"
    },
    href: {
      type: String,
      default: window.location.href
    }
  },
  methods: {
    click() {
      window.location.href = this.href;
    }
  }
};
</script>
