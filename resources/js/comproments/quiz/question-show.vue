<template>
  <div class="qs">
    <b-row>
      <div class="ql-viewer">
        <span>{{serial + 1}}.</span>
        <div v-html="showText(question)">{{ showText(question) }}</div>
        <span class="ql-notsave" v-show="!isSave">未儲存</span>
      </div>
    </b-row>
    <b-row v-for="(choice) in choices" v-bind:key="choice.id">
      <div>
        <label>
          <input
            type="radio"
            :name="choicesUUID"
            :value="choice.id"
            disabled="true"
            :checked="choice.id === answer ? true : false"
          >
          <!-- <span>{{index}}.</span> -->
          <span v-html="showText(choice.content)">{{ showText(choice.content) }}</span>
        </label>
        <div class="remove">
          <b-button :disabled="removeDisgabled" :selId="serial" variant="danger" @click="destroy">
            <i class="fas fa-trash-alt"></i>
          </b-button>
        </div>
      </div>
    </b-row>
  </div>
</template>

<style scoped>
.qs:hover .remove {
  display: block;
}

.remove {
  display: none;
  position: absolute;
  top: 15px;
  right: 0;
}

.ql-viewer {
  box-sizing: border-box;
  /* font-size: 13px; */
  height: 100%;
  margin: 0px;
  width: 100%;
  white-space: pre-wrap;
  word-wrap: break-word;
}

.ql-viewer p,
.ql-viewer ol,
.ql-viewer ul,
.ql-viewer pre,
.ql-viewer blockquote,
.ql-viewer h1,
.ql-viewer h2,
.ql-viewer h3,
.ql-viewer h4,
.ql-viewer h5,
.ql-viewer h6 {
  margin: 0;
  padding: 0;
}

.ql-viewer blockquote {
  border-left: 4px solid #ccc;
  margin-bottom: 5px;
  margin-top: 5px;
  padding-left: 16px;
}

.ql-viewer .ql-size-small {
  font-size: 0.75em;
}
.ql-viewer .ql-size-large {
  font-size: 1.5em;
}
.ql-viewer .ql-size-huge {
  font-size: 2.5em;
}

pre {
  white-space: pre-wrap;
  margin-bottom: 5px;
  margin-top: 5px;
  padding: 5px 10px;
}

pre.ql-syntax {
  border-radius: 3px;
  background-color: #23241f;
  color: #f8f8f2;
  overflow: visible;
}

.ql-align-center {
  text-align: center;
}
.ql-align-justify {
  text-align: justify;
}
.ql-align-right {
  text-align: right;
}

.ql-notsave {
  background: red;
  color: #f8f8f2;
  padding: 4px;
  border-radius: 4px;
}
</style>


<script>
import uuid from "../../uuid-gen";
// import 'quill/dist/quill.core.css';
export default {
  props: {
    choices: Array,
    question: String,
    answer: String,
    qid: Number,
    serial: Number,
    removeDisgabled: Boolean
  },

  methods: {
    showText(text) {
      return text == "" ? "沒有輸入內容" : text;
    },

    removePtag(text) {
      if (text.indexOf("<p>") != -1) {
        return text.substring(3, text.length - 4);
      }
      return text;
    },

    destroy(e) {
      console.log(this.qid);
      const id = {
        selId: Number(e.target.getAttribute("selId")),
        qid: this.qid
      };
      this.$emit("destroy", e, id);
      e.stopPropagation();
    }
  },

  data() {
    return {
      choicesUUID: uuid(),
      isSave: true
    };
  }
};
</script>
