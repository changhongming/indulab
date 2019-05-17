<template>
  <b-container fluid>

    <b-alert
      :show="dismissCountDown"
      dismissible
      fade
      variant="success"
      @dismiss-count-down="countDownChanged"
      class="alert-msg"
    >
      <i class="fas fa-check-circle fa-lg"></i> 儲存成功
    </b-alert>

    <b-row>
      <b-col cols="6">
        <question v-if="questions.length !== 0"
          :question="questions[selId].question"
          :choices="questions[selId].choices"
          :answer="questions[selId].answer"
          :questionId="questions[selId].id"
          :inputId="selId ? selId : -1"
          v-on:on-question-change="questions[selId ? selId : 0].question = $event"
          v-on:answer-value="answerevt"
          v-on:save-state-change="saveStateChange"
          v-on:save-success="questionSaveSuccess"
        ></question>
      </b-col>
      <b-col cols="6">
  <b-container fluid class="c-scollbar" :style="{ height: getClientHeight + 'px'}">
        <div class="list-group" v-if="questions.length !== 0">
          <li
            :class="index === selId ? 'qs-selected' : ''"
            class="list-group-item list-group-item-action"
            v-for="(question, index) in questions"
            v-bind:key="question.id"
            @click="questionClick(index)"
          >
            <questionShow
              ref="questionShow"
              :qid="index"
              :answer="question.answer"
              :question="question.question"
              :choices="question.choices"
              v-on:destroy="destroy"
            ></questionShow>
          </li>
        </div>
        <div>
          <b-button @click="addQuestion">+</b-button>
        </div>
  </b-container>
      </b-col>
    </b-row>
    <!-- <b-row>
      <button @click="saveData">asdsda</button>
    </b-row> -->
  </b-container>
</template>

<style>
.alert-msg {
  position: fixed;
  top: 20px;
  left: 50%;
  z-index: 999;
  font-size: 30px;
}

.c-scollbar {
  position: relative;
  overflow: auto;
}

.qs-selected {
  border: 4px solid red;
}
</style>


<script>
import uuid from "../../uuid-gen";
import questionShow from "./question-show.vue";
import question from "./question.vue";

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i].trim();
    if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
  }
  return "";
}

function initState() {
  const data = {
    questions: [
      //{
      //   id: uuid(),
      //   order: 0,
      //   question: "",
      //   answer: null,
      //   choices: [
      //     {
      //       id: uuid(),
      //       content: ""
      //     },
      //     {
      //       id: uuid(),
      //       content: ""
      //     }
      //   ]
      // },
      // {
      //   id: uuid(),
      //   order: 2,
      //   question: "",
      //   answer: null,
      //   choices: [
      //     {
      //       id: uuid(),
      //       content: ""
      //     },
      //     {
      //       id: uuid(),
      //       content: ""
      //     }
      //   ]
      // }
    ],
            dismissSecs: 5,
        dismissCountDown: 0,
        showDismissibleAlert: false
  };



  data.selId = -1;

  // 等待後端完成 由後端資料庫取出最大值
  data.order = Math.max(...data.questions.map(x => x.order));

  console.log(data);

  const cookieQs = JSON.parse(getCookie("questionData"));
  console.log(cookieQs);
  return data;
}

export default {
  components: {
    question,
    questionShow
  },

  data() {
    return initState();
  },

  computed: {
    getClientHeight() {
      return innerHeight - document.querySelector('nav').clientHeight - document.querySelector('#app').querySelector('p').clientHeight - 100;
    }
  },

  methods: {
    questionSaveSuccess() {
      this.dismissCountDown = this.dismissSecs;
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs
    },


    answerevt(e) {
      this.questions[this.selId].answer = e;
    },

    saveStateChange(state) {
      console.log(state);
      this.$refs.questionShow[this.selId].isSave = state;
    },

    questionClick(id) {
      console.log("qc", id);
      //this.selQuestion = this.questions[id].question;
      //this.selChoices = this.questions[id].choices;
      this.selId = id;
    },

    addQuestion() {
      const vm = this;
      const question = {
        order: ++this.order,
        question: "",
        choices: [
          {
            id: uuid(),
            content: ""
          },
          {
            id: uuid(),
            content: ""
          }
        ]
      };

      // 新建一個問題
      this.questions.push(question);

      // 選擇到新建問題
      this.selId = this.questions.length - 1;
    },

    saveData() {
      console.log(this);
      //console.log(JSON.stringify(this.$data));
      document.cookie = "questionData=" + JSON.stringify(this.$data);
      console.log(JSON.parse(getCookie("questionData")));
      // $.cookie("questionData", JSON.stringify(JSON.parse(JSON.stringify(this.$data))));
    },

    destroy(e, id) {
      // const qs = this.questions;
      // let index = null;
      // for(let i = 0; i < qs.length; i++) {
      //   if(qs[i].id === id) {
      //     index = i;
      //     break;
      //   }
      // }
      // console.log(index);


      this.$nextTick(() => {
        this.selId = 0;
      })

      this.questions.splice(id, 1);
      // e.stopPropagation();
    },

    uuid() {
      return uuid();
    }
  },

  created() {
    const vm = this;
    console.log(this.$data);
      axios.get('/question')
    .then((res) => {
    console.log(res);
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
    const data = {};
    data.questions = questions;
    vm.selId = 0;
    vm.questions = questions;
  })
  .catch((err) => {
    console.log(err)
  });
    // this.$data = JSON.parse(getCookie("questionData"));
    // JSON.parse(getCookie("questionData");
  }
};
</script>
