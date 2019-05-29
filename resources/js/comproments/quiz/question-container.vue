<template>
  <b-container fluid>
    <div v-show="isProcess">
      <div>
        <div class="lds-ring">
          <div></div>
          <div></div>
          <div></div>
          <div></div>
          <div class="lds-ring-text">
            <span>儲存中</span>
          </div>
        </div>
      </div>
    </div>

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
    <div v-show="isLoadedFail">
      <span style="color:red;">讀取失敗，請重新整理頁面再試試</span>
    </div>
    <div v-show="!isLoadedFail && isLoaded">
      <div class="alert-msg">
        <b-alert
          :show="dismissCountDown"
          dismissible
          fade
          variant="success"
          @dismiss-count-down="countDownChanged"
        >
          <i class="fas fa-check-circle fa-lg"></i> 儲存成功
        </b-alert>
      </div>

      <b-row v-if="questions.length !== 0">
        <b-col cols="12" md="6">
          <question
            :question="questions[selId].question"
            :choices="questions[selId].choices"
            :initAnswerId="questions[selId].answer"
            :questionId="questions[selId].id"
            :inputId="selId ? selId : -1"
            v-on:recovery="recoveryQuestion"
            v-on:on-question-change="questions[selId].question = $event"
            v-on:answer-value="answerevt"
            v-on:save-state-change="saveStateChange"
            v-on:save-success="questionSaveSuccess"
            v-on:process-state-change="processStateChange"
            ref="questionEditor"
          ></question>
        </b-col>
        <b-col cols="12" md="6">
          <b-container fluid class="c-scollbar" :style="{ height: getClientHeight + 'px'}">
            <div class="list-group" v-if="questions.length !== 0">
              <li
                :class="['list-group-item', 'list-group-item-action', index === selId ? 'qs-selected ' : '']"
                v-for="(question, index) in questions"
                v-bind:key="question.id"
                @click="questionClick(index)"
              >
                <questionShow
                  ref="questionShow"
                  :qid="question.id"
                  :serial="index"
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
    </div>
  </b-container>
</template>

<style scoped>
body {
  background-color: #F1FFFA;
}
.col-border {
  /* border: 4px; */
  border-color: lightgray;
  border-style:solid;
  border-width: 2px;
  border-radius: 15px;
}

.lds-ring .lds-ring-text {
  display: inline-block;
  position: relative;
  animation: none;
  font-size: 30px;
  color: black;
  font: bold;
  border: none;
  top: 100px;
}

.lds-ring .lds-ring-text span {
  animation: lds-ring-text 1.2s infinite;
  animation-direction: alternate;
}
@keyframes lds-ring-text {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.7;
  }
  100% {
    opacity: 0;
  }
}

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

.alert-msg {
  position: fixed;
  top: 5%;
  left: 50%;
  z-index: 999;
  font-size: 26px;
}

.list-group {
  margin-top: 20px;
}

.c-scollbar {
  position: relative;
  overflow: auto;
  border-color: #93AF95;
  border-style:solid;
  border-width: 2px;
  border-radius: 10px;
  background-color: #96E6B3
}

.qs-selected {
  border: 4px solid red !important;
}
</style>


<script>
import cloneDeep from "lodash.clonedeep";
import uuid from "../../uuid-gen";
import questionShow from "./question-show.vue";
import question from "./question.vue";

let initQuestion = null;

const newObj = obj => {
  return JSON.parse(JSON.stringify(obj));
};

// 深拷貝覆蓋資料(並且會刪除多的內容) => 註：由於JS內物件是採用參考(reference)方式，所以需要將物件內各個數值一一覆蓋(因數值是by value)。
const copyData = (obj, overObj) => {
  // overObj = JSON.parse(JSON.stringify(overObj));
  Object.keys(obj).forEach(key => {
    // 刪除原本物件沒有的key
    if (!overObj.hasOwnProperty(key)) {
      // 跳過本次迭代
      return delete obj[key];
    }
    // 如果為型別為Object，則遞迴往該物件下去搜尋(註：因為null的typeof也是回傳Object，所以加入obj[key]來判斷null)
    if (typeof obj[key] === "object" && obj[key]) {
      copyData(obj[key], overObj[key]);
    }
    // 如果不是上述情況，則執行一般覆蓋
    obj[key] = overObj[key];
  });
};

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
    showDismissibleAlert: false,
    isLoaded: false,
    isLoadedFail: false,
    isProcess: null,
    initQuestion: null
  };

  data.selId = -1;

  // 等待後端完成 由後端資料庫取出最大值
  data.order = Math.max(...data.questions.map(x => x.order));
  return data;
}

export default {
  components: {
    question,
    questionShow
  },

  props: {
    quizId: Number
  },

  data() {
    return initState();
  },

  watch: {
    selId(val) {
      if (this.$refs.hasOwnProperty("questionEditor")) {
        const editor = this.$refs.questionEditor.$refs.qsEditor.quill;
        setTimeout(() => {
          editor.setSelection(editor.getLength());
          editor.focus();
        }, 0);
      }
    }
  },

  computed: {
    getClientHeight() {
      return innerHeight - document.querySelector("nav").clientHeight - 5;
    }
  },

  methods: {
    questionSaveSuccess(payload) {
      this.dismissCountDown = this.dismissSecs;
      initQuestion[this.selId].id = payload.id;

      copyData(
        initQuestion[this.selId],
        JSON.parse(JSON.stringify(this.questions[this.selId]))
      );
    },
    countDownChanged(dismissCountDown) {
      this.dismissCountDown = dismissCountDown;
    },
    showAlert() {
      this.dismissCountDown = this.dismissSecs;
    },

    recoveryQuestion() {
      // 深拷貝覆蓋資料
      copyData(
        this.questions[this.selId],
        JSON.parse(JSON.stringify(initQuestion[this.selId]))
      );
    },

    answerevt(e) {
      this.questions[this.selId].answer = e;
    },

    processStateChange(state) {
      this.isProcess = state;
    },

    saveStateChange(state) {
      this.$refs.questionShow[this.selId].isSave = state;
    },

    questionClick(id) {
      this.selId = id;
    },

    addQuestion() {
      const vm = this;
      const defaultAnswerId = uuid();
      const question = {
        id: null,
        order: ++this.order,
        question: "",
        answer: defaultAnswerId,
        choices: [
          {
            id: defaultAnswerId,
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
      initQuestion.push(newObj(question));

      // 選擇到新建問題
      this.selId = this.questions.length - 1;

      // 標記尚未儲存
      this.$nextTick(() => {
        this.$refs.questionShow[this.selId].isSave = false;
      });
    },

    saveData() {
      document.cookie = "questionData=" + JSON.stringify(this.$data);
    },

    destroy(e, id) {
      // 如果問題已建立，但尚未保存置資料庫(資料庫還沒有索引)
      if(id.qid === null) {
          this.$nextTick(() => {
            initQuestion.splice(id.selId, 1);
            this.questions.splice(id.selId, 1);
            this.selId = 0;
          });
      }
      // 如果資料庫已建立索引
      else {
      axios
        .delete(`/question/${id.qid}`)
        .then(res => {
          console.log(res, id);
          // 等待刷新完成在重新指向刷新後的位置
          this.$nextTick(() => {
            initQuestion.splice(id.selId, 1);
            this.questions.splice(id.selId, 1);
            this.selId = 0;
          });
        })
        .catch(err => {
          console.log(err);
        });
      }
    },

    uuid() {
      return uuid();
    }
  },

  created() {
    const vm = this;
    axios
      .get(`/question?id=${vm.quizId}`)
      .then(res => {
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
        vm.selId = 0;
        vm.questions = questions;
        initQuestion = JSON.parse(JSON.stringify(questions));
      })
      .catch(err => {
        vm.isLoadedFail = true;
        console.log(err);
      })
      .finally(() => {
        vm.isLoaded = true;
      });
  }
};
</script>
