<template>
  <b-container fluid>
    <b-row>
      <b-col cols="6">
        <question
          :question="questions[selId ? selId : 0].question"
          :choices="questions[selId ? selId : 0].choices"
          :answer="questions[selId ? selId : 0].answer"
          v-on:on-question-change="questions[selId ? selId : 0].question = $event"
          v-on:answer-value="answerevt"
        ></question>
      </b-col>
      <b-col cols="6">
        <div class="list-group">
          <li
            :class="index === selId ? 'qs-selected' : ''"
            class="list-group-item list-group-item-action"
            v-for="(question, index) in questions"
            v-bind:key="question.id"
            @click="questionClick(index)"
          >
            <questionShow
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
      </b-col>
    </b-row>
    <b-row>
      <button @click="saveData">asdsda</button>
    </b-row>
  </b-container>
</template>

<style>
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
      {
        id: uuid(),
        order: 0,
        question: "",
        answer: null,
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
      },
      {
        id: uuid(),
        order: 2,
        question: "",
        answer: null,
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
      }
    ]
  };

  data.selId = 0;

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

  methods: {
    answerevt(e) {
      this.questions[this.selId].answer = e;
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
        id: uuid(),
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
    console.log(this.$data);
    // this.$data = JSON.parse(getCookie("questionData"));
    // JSON.parse(getCookie("questionData");
  }
};
</script>
