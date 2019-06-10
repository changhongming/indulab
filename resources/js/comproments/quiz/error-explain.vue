<template>
  <div>
    <b-card border-variant="danger" header-border-variant="danger">
      <template slot="header">
        <div class="text-danger text-center">錯誤解釋</div>
      </template>
      <div class="ql-viewer" v-html="content"></div>
    </b-card>
  </div>
</template>

<script>
import "../../../sass/quiz.scss";
export default {
  data() {
    return {};
  },

  props: {
    content: String
  },
};
</script>
