<template>
  <div class="qs">
    <b-row>
      <div class="ql-viewer">
        <span>{{serial + 1}}.</span>
        <h1 class="title">題目</h1>
        <div v-html="showText(question)"></div>
        <span class="ql-notsave" v-show="!isSave">未儲存</span>
        <h1 class="title">答案錯誤解釋：</h1>
        <div v-html="showText(wrongAnswer)"></div>
        <h1 class="title">選項：</h1>
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

.ql-notsave {
  background: red;
  color: #f8f8f2;
  padding: 4px;
  margin-top: 4px;
  margin-bottom: 4px;
  border-radius: 4px;
}

.qs-block {
  border: #0000ff 2px solid;
}

.wrong-blcok {
  border: #0000ff 2px solid;
}

.title {
  font-weight: bold;
  border-bottom: 1px solid #eee;
}
</style>


<script>
import "../../../sass/quiz.scss";
import uuid from "../../uuid-gen";
import { mapState, mapGetters, mapActions } from "vuex";

export default {
  props: {
    choices: Array,
    question: String,
    answer: String,
    wrongAnswer: String,
    qid: Number,
    serial: Number,
    removeDisgabled: Boolean,
    initIsSave: Boolean
  },

  computed: {
    ...mapState("quiz", {
      selectId: "selectId",
      questions: "questions",
      isLoaded: "isLoaded",
      isLoadedFail: "isLoadedFail",
      isProcess: "isLoading",
      initQuestion: "initQuestions"
    }),

    ...mapGetters("quiz", {
      questionNumber: "questionNumber",
    })
  },

  watch: {
    initIsSave(val) {
      this.isSave = val;
    }
  },

  methods: {
    ...mapActions("quiz", {
      changeSelectId: "changeSelectId",
      removeQuestion: "removeQuestion"
    }),

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
      function findAttribute(targetDOM, attribute, depth = 3) {
        let cnt = 0;
        let currentDOM = targetDOM;
        while(!currentDOM.hasAttribute(attribute)) {
          currentDOM = currentDOM.parentElement;
          cnt++;
          if(cnt >= depth)
            return null;
        }
        return currentDOM.getAttribute(attribute);
      }

      const id = {
        selId: Number(findAttribute(e.target, "selId")),
        qid: this.qid
      };

      this.removeQuestion(id);
      return false;
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
