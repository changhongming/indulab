<template>
  <b-container fluid>
    <template v-if="isIntroStage">
      <h1>測驗</h1>
      <button @click="startTest">開始</button>
    </template>
    <template v-if="isTestStage">
      <div
        ref="tikTok"
        @mousedown="mouseDownTikTok"
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
        />
      </div>
      <b-row>
        <b-col>
          <test-question-bar
            :total-page="questions.length"
            :selectPage="selectId + 1"
            @change="pageChange"
            :is-answer-list="answers"
          />
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <button class="btn btn-danger btn-lg btn-block" v-show="showSumbit" @click="sumbit">交卷</button>
        </b-col>
      </b-row>
    </template>
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
    min-width: 10%;
    text-align: center;
    cursor: pointer;
    z-index: 9999;
}
</style>

<script>
import "../../../sass/quiz.scss";
import TestQuestion from "./test-qeustion.vue";
import TestQuestionBar from "./test-question-bar";

import { mapState } from "vuex";

let leftTimer = null;

export default {
  components: {
    TestQuestion,
    TestQuestionBar
  },

  data() {
    return {
      // 這裡等價於selectID
      questionNumber: 0,

      // 計時
      minute: Math.floor(this.testTime / 60),
      second: Math.floor(this.testTime % 60),
      leftTime: this.testTime,

      isTickTokDown: false,
      tikTokOffsetX: null,
      tikTokOffsetY: null,

      showSumbit: false,

      isIntroStage: true,
      isTestStage: false,
      isResultStage: false,
      answers: new Array(this.questions.length)
    };
  },

  props: {
    questions: {
      type: Array
    },
    testTime: {
      default: 100,
      type: Number
    },
    initSelId: {
      type: Number,
      default: 0
    }
  },
  computed: {
    ...mapState("quiz", {
      selectId: "selectId"
    }),
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
    }
  },

  methods: {
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
      this.answers[this.questionNumber] = answer;
    },

    pageChange(page) {
      this.questionNumber = page - 1;
    },

    sumbit() {
      const total = this.questions.length;
      let correct = 0;
      for (let i = 0; i < this.questions.length; i++) {
        if (this.questions[i].answer === this.answers[i]) {
          correct++;
        }
      }
      alert(`${correct}/${total}，正確率${correct / total}`);
      console.log(this.questions, this.answers);
    },

    /* tik-tok */
    mouseDownTikTok(e) {
      const dom = this.$refs.tikTok;
      // console.log(e.offsetX, e.offsetY, dom.clientY, dom.clientX);
      console.log(innerHeight)
      this.isTickTokDown = true;
      this.tikTokOffsetX = e.offsetX;
      this.tikTokOffsetY = e.offsetY;
    },

    mouseUpTikTok() {
      this.isTickTokDown = false;
    },

    mouseMoveTikTok(e) {
      e.preventDefault();
      if (this.isTickTokDown) {
        const dom = this.$refs.tikTok;
        // console.log(e)
        const deltaX = e.clientX;
        const deltaY = e.clientY;
        let resX = deltaX - this.tikTokOffsetX;
        let resY = deltaY - this.tikTokOffsetY;
        console.log(resX, resY);
        if(resX < 0) {
          resX = 0;
        }
        else if(resX + (dom.offsetWidth - this.tikTokOffsetX + 25) > document.documentElement.clientWidth) {
          console.log('xBorder');
          resX = document.documentElement.clientWidth - (dom.offsetWidth - this.tikTokOffsetX + 25);
        }
        if(resY < 0) {
          resY = 0;
        }
        else if(resY + (dom.offsetWidth - this.tikTokOffsetX) > innerHeight) {
          console.log('yBorder')
          resY = innerHeight - (dom.offsetHeight - this.tikTokOffsetY);
        }
        // console.log(rect, {x:deltaX,y:deltaY}, {x: rect.x + deltaX, y: rect.x + deltaX})
        dom.style.left = resX  + "px";
        dom.style.top = resY + "px";
      }
    }
  },

  created() {
    console.log("created", this.$data);
  },

  mounted() {
    this.$el.addEventListener('mouseup', this.mouseUpTikTok);
    this.$el.addEventListener('mousemove', this.mouseMoveTikTok);
  }
};
</script>
