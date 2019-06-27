<template>
  <b-container fluid>
    <!-- 測驗介紹階段 -->
    <template v-if="isIntroStage">
      <h1>{{testName}}</h1>
      <div>
        <span>此測驗總共有{{questions.length}}題，測驗時間為 {{minute}}分{{second}}秒，按下開始按鈕即可開始測驗</span>
      </div>
      <button class="btn btn-success" @click="startTest">開始</button>
    </template>

    <!-- 測驗階段 -->
    <template v-if="isTestStage">
      <div
        ref="tikTok"
        @mousedown="mouseDownTikTok"
        @touchstart="mouseDownTikTok"
        @touchend="mouseUpTikTok"
        class="tik-tok"
      >
        <span>剩餘{{padingDigit(minute)}}分{{padingDigit(second)}}秒</span>
      </div>

      <div class="ql-viewer">
        <test-question
          :questionNumber="questionNumber"
          :question="questions[questionNumber].question"
          :choices="questions[questionNumber].choices"
          :init-answer="answers[questionNumber]"
          v-on:choice-change="choiceAnswer"
          :isReadOnly="isResultStage"
          :correct-answer="questions[questionNumber].answer"
        />
      </div>
      <b-row>
        <b-col>
          <test-question-bar
            :total-page="questions.length"
            :selectPage="questionNumber + 1"
            @change="pageChange"
            :is-answer-list="answers"
            :answer-result-list="resultAnswer"
            :isAnswerState="isResultStage"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <error-explain
            v-if="isResultStage && questions[questionNumber].answer !== answers[questionNumber]"
            :content="questions[questionNumber].wronganswer"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <button
            class="btn btn-primary"
            v-show="notAnswers.length && !isResultStage"
            @click="showNextNotAnswerQuestion"
          >跳至未作答的題目</button>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <button
            class="btn btn-danger btn-lg btn-block"
            v-show="(showSumbit || allQuestionDone) && !isResultStage"
            @click="sumbit"
          >{{allQuestionDone ? '送出答案' : '尚有題目未完成(送出答案)'}}</button>
        </b-col>
      </b-row>
    </template>
    <button v-if="isEditorMode" @click="resetData">重置題目</button>
  </b-container>
</template>

<style scoped>
.btn-lg {
  font-size: 2rem;
}

.tik-tok {
  display: block;
  position: fixed;
  top: 5%;
  right: 5%;
  font-size: 1.5rem;
  color: var(--white);
  background: var(--danger);
  border-radius: 5px;
  max-width: 10%;
  opacity: 0.9;
  text-align: center;
  cursor: pointer;
  z-index: 9999;
}
</style>

<script>
import "../../../sass/quiz.scss";
import TestQuestion from "./test-qeustion.vue";
import TestQuestionBar from "./test-question-bar";
import ErrorExplain from "./error-explain";

import { mapState } from "vuex";

let leftTimer = null;

export default {
  components: {
    TestQuestion,
    TestQuestionBar,
    ErrorExplain
  },

  data() {
    return {
      // 這裡等價於selectID
      questionNumber: 0,

      notAnswerIndex: 0,

      // 計時
      minute: Math.floor(this.testTime / 60),
      second: Math.floor(this.testTime % 60),
      leftTime: this.testTime,

      isTickTokDown: false,
      tikTokOffsetX: null,
      tikTokOffsetY: null,

      showSumbit: false,
      allQuestionDone: false,

      isIntroStage: true,
      isTestStage: false,
      isResultStage: false,
      answers: Array(this.questions.length).fill(null),
      resultAnswer: Array(this.questions.length).fill(true),
      notAnswers: Array(this.questions.length)
        .fill(null)
        .map((_, index) => index + 1)
    };
  },

  props: {
    questions: {
      type: Array
    },
    testTime: {
      default: 180,
      type: Number
    },
    initSelId: {
      type: Number,
      default: 0
    },
    isEditorMode: {
      type: Boolean,
      default: false
    },
    testName: {
      type: String,
      default: "測驗"
    },
  },
  computed: {
    // 個位數自動補0轉換(補2碼)
    padingDigit(val) {
      return val => (val > 9 ? val : "0" + val);
    }
  },

  watch: {
    leftTime(val) {
      // 計算新的分秒
      if (val > 0) {
        this.minute = Math.floor(val / 60);
        this.second = Math.floor(val % 60);
      }
      // 時間到
      else {
        this.second = 0;
        window.clearInterval(leftTimer);
        leftTimer = null;
        alert(
          `由於測驗時間已到，自動送出答案!(未作答題目${this.notAnswers.toString()}，共${
            this.notAnswers.length
          }題)`
        );
        this.checkAnswer();
      }
    },

    questionNumber(val) {
      // 判斷上下問題是否可以選取
      const number = val + 1;
      if (number === this.questions.length) {
        this.showSumbit = true;
      } else {
        this.showSumbit = false;
      }
    },

    initSelId(val) {
      this.questionNumber = val;
    },

    notAnswers(val, oldVal) {
      if (val.length === 0) {
        this.allQuestionDone = true;
      } else {
        this.allQuestionDone = false;
      }
    },

    questions(val) {
      this.answers = Array(this.questions.length).fill(null);
      this.showSumbit = false;
    }
  },

  methods: {
    resetData() {
      // 待拷貝的預設props物件
      const copyDefaultProps = {};
      // props的定義
      const defaultProps = this.$options.props;

      // 找出props內有預設值的內容並拷貝出來
      Object.keys(defaultProps).forEach(key => {
        if (defaultProps[key].hasOwnProperty("default")) {
          copyDefaultProps[key] = defaultProps[key].default;
        }
      });

      // 將預設props資料與當前外部傳入的props資料進行結合，後複製到_props
      const _props = Object.assign(
        {},
        copyDefaultProps,
        this.$options.propsData
      );
      // console.log( this.$options.data.bind(_props)());
      // 清空前一次狀態的Timer
      window.clearInterval(leftTimer);
      // 進行複製
      Object.assign(this.$data, this.$options.data.bind(_props)());
    },

    startTest() {
      const vm = this;
      vm.isIntroStage = false;
      vm.isTestStage = true;
      vm.$nextTick(() => {
        leftTimer = window.setInterval(() => {
          vm.leftTime--;
        }, 1000);
      });
    },

    choiceAnswer(answer) {
      this.$set(this.answers, this.questionNumber, answer)
      this.listNotAnswerNumber();
    },

    pageChange(page) {
      this.questionNumber = page - 1;
    },

    listNotAnswerNumber() {
      const notAnswers = [];
      this.answers.forEach((answer, index) => {
        if (answer === null) {
          notAnswers.push(index + 1);
        }
      });
      this.notAnswers = notAnswers;
    },

    sumbit() {
      // 列出尚未作答題號
      this.notAnswers = (answers => {
        const notAnswers = [];
        answers.forEach((answer, index) => {
          if (answer === null) {
            notAnswers.push(index + 1);
          }
        });
        return notAnswers;
      })(this.answers);

      // 如果有未作答題目
      if (this.notAnswers.length !== 0) {
        const res = confirm(
          `題目${this.notAnswers.toString()}尚未作答，確定要提交答案？`
        );
        // 回到第一個未作答題目
        if (!res) {
          setTimeout(() => {
            this.showSumbit = true;
          }, 0);
          return (this.questionNumber = this.notAnswers[0] - 1);
        }
      }
      this.checkAnswer();
    },

    checkAnswer() {
      clearInterval(leftTimer);
      const total = this.questions.length;
      let correct = 0;
      for (let i = 0; i < total; i++) {
        if (this.questions[i].answer === this.answers[i]) {
          correct++;
        }
        else {
          // 因Vue無法監聽到陣列內數值的變化，故使用$set來主動告知並設定
          this.$set(this.resultAnswer, i, false);
        }
      }
      alert(`${correct}/${total}，正確率${correct / total}`);
      console.log(this.questions, this.answers);
      this.isResultStage = true;
      // this.$nextTick(() => {
      //   this.resetData();
      // });
    },

    showNextNotAnswerQuestion() {
      // this.listNotAnswerNumber();

      if (this.notAnswers.length > 0) {
        if (this.questionNumber === this.notAnswers[this.notAnswerIndex] - 1) {
          this.notAnswerIndex++;
        }
        if (this.notAnswers.length <= this.notAnswerIndex) {
          this.notAnswerIndex = 0;
        }
        this.questionNumber = this.notAnswers[this.notAnswerIndex] - 1;
      }
    },

    /* tik-tok */
    mouseDownTikTok(e) {
      switch (e.type) {
        case "touchstart":
          e.preventDefault();
          const rect = e.target.getBoundingClientRect();
          this.tikTokOffsetX = e.touches[0].clientX - rect.left;
          this.tikTokOffsetY = e.touches[0].clientY - rect.top;
          break;
        case "mousedown":
          this.tikTokOffsetX = e.offsetX;
          this.tikTokOffsetY = e.offsetY;
          break;
        default:
          return console.error("非預期事件");
      }
      this.isTickTokDown = true;
    },

    mouseUpTikTok() {
      this.isTickTokDown = false;
    },

    mouseMoveTikTok(e) {
      if (e.type === "mousemove") {
        e.preventDefault();
      }
      if (this.isTickTokDown) {
        const dom = this.$refs.tikTok;

        const deltaX = e.clientX || e.changedTouches[0].clientX;
        const deltaY = e.clientY || e.changedTouches[0].clientY;

        let resX = deltaX - this.tikTokOffsetX;
        let resY = deltaY - this.tikTokOffsetY;
        if (resX < 0) {
          resX = 0;
        } else if (
          resX + (dom.offsetWidth - this.tikTokOffsetX + 25) >
          document.documentElement.clientWidth
        ) {
          resX =
            document.documentElement.clientWidth -
            (dom.offsetWidth - this.tikTokOffsetX + 25);
        }
        if (resY < 0) {
          resY = 0;
        } else if (
          resY + (dom.offsetWidth - this.tikTokOffsetX) >
          innerHeight
        ) {
          resY = innerHeight - (dom.offsetHeight - this.tikTokOffsetY);
        }
        // console.log(rect, {x:deltaX,y:deltaY}, {x: rect.x + deltaX, y: rect.x + deltaX})
        dom.style.left = resX + "px";
        dom.style.top = resY + "px";
      }
    }
  },

  created() {
    this.listNotAnswerNumber();

    // 如果choices物件尚未解析成JSON字串，則解析
    if (
      this.questions.length > 0 &&
      typeof this.questions[0].choices === "string"
    ) {
      const quesitons = this.questions.map(question => {
        return {
          ...question,
          choices: JSON.parse(question.choices)
        };
      });
      Object.assign(this.questions, quesitons);
    }
  },

  mounted() {
    this.$el.addEventListener("mouseup", this.mouseUpTikTok);
    this.$el.addEventListener("mousemove", this.mouseMoveTikTok);
    this.$el.addEventListener("touchmove", this.mouseMoveTikTok);
  }
};
</script>
