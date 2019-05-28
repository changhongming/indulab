<template>
  <b-container fluid>
    <!-- <div></div> -->
    <template v-if="isIntroStage">
      <h1>測驗</h1>
      <button @click="startTest">開始</button>
    </template>
    <template v-if="isTestStage">
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
        <b-col md="6">
          <b-pagination
            :total-rows="totalPage"
            :per-page="1"
            :page="questionNumber"
            v-model="currentPage"
            @change="pageChange"
            class="answered"
          />
        </b-col>
      </b-row>
      <div>
        <button class="btn btn-success" :disabled="disablePrvBtn" @click="questionNumber--">上一題</button>
        <button class="btn btn-success" :disabled="disableNextBtn" @click="questionNumber++">下一題</button>
        <button class="btn btn-primary" v-show="showSumbit" @click="sumbit">交卷</button>
        <span>剩餘{{padingDigit(minute)}}分{{padingDigit(second)}}秒</span>
      </div>
    </template>
  </b-container>
</template>

<style scoped>

</style>



<script>
import "../../../sass/quiz.scss";
import TestQuestion from "./test-qeustion.vue";

let leftTimer = null;

export default {
  components: {
    TestQuestion
  },

  data() {
    return {
      // questionNumber: 0,

      // 計時
      minute: Math.floor(this.testTime / 60),
      second: Math.floor(this.testTime % 60),
      leftTime: this.testTime,

      totalPage: this.questions.length,
      currentPage: 1,
      questionNumber: 0,

      disableNextBtn: false,
      disablePrvBtn: true,
      showSumbit: false,

      isIntroStage: true,
      isTestStage: false,
      isResultStage: false,
      answers: new Array(this.questions.length)
    };
  },

  props: {
    questions: Array,
    testTime: {
      default: 100,
      type: Number
    }
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
      }
    },

    questionNumber(val) {
      // 判斷上下問題是否可以選取
      const number = val + 1;
      if (number === this.questions.length) {
        console.log("no next");
        this.disableNextBtn = true;
        this.showSumbit = true;
      } else if (number === 1) {
        console.log("first page");
        this.disablePrvBtn = true;
        this.showSumbit = false;
      } else {
        this.disableNextBtn = false;
        this.disablePrvBtn = false;
        this.showSumbit = false;
      }
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
      for(let i = 0; i < this.questions.length; i++) {
        if(this.questions[i].answer === this.answers[i]) {
          correct++;
        }
      }
      alert(`${correct}/${total}，正確率${correct/total}`)
      console.log(this.questions ,this.answers)
    }
  },

  created() {
    console.log("created", this.$data);
  },

  mounted() {
    console.log("mounted", this.$data);
    console.log(this.answers);
  }
};
</script>
