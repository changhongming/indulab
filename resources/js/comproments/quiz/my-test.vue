<template>
  <div>
    <div v-show="!isLoaded">
      <div class="loading">
        <div class="lds-ring">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
        </div>
      </div>
    </div>

    <template v-if="questions.length != 0">
      <question-container v-show="isEditorQuestionMode"/>
      <test
        v-show="isPreviewTestMode"
        :init-sel-id="selectId"
        :questions="questions"
        :isEditorMode="true"
        :test-name="testName"
        :test-time="testTime"
      />

      <test-question
        v-show="isPreviewQuestionMode"
        :questionNumber="selectId"
        :question="getQuestion.question"
        :choices="getQuestion.choices"
        :init-answer="getQuestion.answer"
        :correct-answer="getQuestion.answer"
        :isReadOnly="true"
      />
      <error-explain v-show="isPreviewQuestionMode" :content="getQuestion.wronganswer"/>

      <button v-show="isPreviewTestMode || isPreviewQuestionMode" @click="clickBackEditor">返回編輯模式</button>
      <button v-show="isPreviewQuestionMode" @click="clickOpenPreviewtest">開啟全題預覽</button>
      <button v-show="isPreviewTestMode" @click="clickClosePreviewtest">關閉全題預覽</button>
    </template>
  </div>
</template>

<style scoped>
.lds-ring {
  position: absolute;
  top: 50%;
  right: 50%;
  width: 100px;
  height: 100px;
  z-index: 9999;
}

.lds-ring div {
  width: 100px;
  height: 100px;
  margin: 15px;
  border-width: 15px;
}
</style>


<script>
// setIsPreviewQuestionMode: "setIsPreviewQuestionMode",
// setIsEditorQuestionMode: "setIsEditorQuestionMode",
import QuestionContainer from "./question-container";
import Test from "./test";
import TestQuestion from "./test-qeustion";
import ErrorExplain from "./error-explain";
// import GoTop from "./go-top";

import { mapState, mapGetters, mapActions } from "vuex";

export default {
  components: {
    QuestionContainer,
    Test,
    TestQuestion,
    ErrorExplain
    // GoTop
  },

  computed: {
    ...mapState("quiz", {
      selectId: "selectId",
      questions: "questions",
      isLoaded: "isLoaded",
      isLoadedFail: "isLoadedFail",
      isLoading: "isLoading",
      isEditorQuestionMode: "isEditorQuestionMode",
      isPreviewTestMode: "isPreviewTestMode",
      isPreviewQuestionMode: "isPreviewQuestionMode",
      testName: "testName",
      testTime: "testTime"
    }),

    ...mapGetters("quiz", {
      questionNumber: "questionNumber",
      getQuestion: "getQuestion"
    })
  },

  data() {
    return {
      //questions: [],
      alert: true,
      selId: 0
    };
  },

  methods: {
    ...mapActions("quiz", {
      changeSelectId: "changeSelectId",
      getQuestions: "getQuestions",
      addQuestion: "addQuestion",
      setIsPreviewQuestionMode: "setIsPreviewQuestionMode",
      setIsPreviewTestMode: "setIsPreviewTestMode",
      setIsEditorQuestionMode: "setIsEditorQuestionMode",
      setTestId: "setTestId"
    }),

    getData: async function() {
      const res = await axios.get("/question?id=1");
      const questions = [];
      res.data.forEach(row => {
        questions.push({
          id: row.id,
          order: row.order,
          question: row.question,
          answer: row.answer_id,
          choices: JSON.parse(row.choices)
        });
      });
      this.questions = questions;
    },

    clickBackEditor() {
      this.setIsPreviewQuestionMode(false);
      this.setIsPreviewTestMode(false);
      this.setIsEditorQuestionMode(true);
    },

    clickClosePreviewtest() {
      this.setIsPreviewQuestionMode(true);
      this.setIsPreviewTestMode(false);
      this.setIsEditorQuestionMode(false);
    },

    clickOpenPreviewtest() {
      this.setIsPreviewQuestionMode(false);
      this.setIsPreviewTestMode(true);
      this.setIsEditorQuestionMode(false);
    }
  },

  created() {
    // 註冊當使用者修改要離開
    window.document.body.onbeforeunload = () => true;

    this.setTestId(window.location.href.split("?id=")[1].split("#")[0]);
    this.getQuestions();
  },

  destroyed() {
    window.document.body.onbeforeunload = null;
  }
};
</script>
