<template>
  <div>
    <div v-html="content"></div>
    <div>abc</div>
  </div>
</template>

<script>
import "../../../sass/quiz.scss";
export default {
  data() {
    return {}
  },

  props: {
    content: String,
  },
  created() {
    console.log(this.$data)
  }
}
</script>
