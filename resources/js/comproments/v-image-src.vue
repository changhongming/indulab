<template>
  <v-image
    :config="{
      ...config,
      image: image
    }"
  />
</template>

<script>
export default {
  props: {
    config: {
      type: Object
    }
  },
  data() {
    return {
      image: null
    };
  },
  created(vm) {
    const image = new window.Image();
    image.src = this.config.src;
    image.onload = () => {
      // set image only when it is loaded
      this.image = image;
      this.$emit('onload', {width: image.width, height: image.height})
    };
  }
};
</script>
