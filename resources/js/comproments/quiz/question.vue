<template>
  <div>
    <div class="c-scollbar" ref="ediotorBlock">
      <div class="row-group">
        <b-row class="mb-2">
          <b-col>
            <label>
              <h1 @click="$refs.qsEditor.quill.focus()">題目</h1>
            </label>
            <quill-editor
              @change="contentChange"
              ref="qsEditor"
              :editorOptions="editorSettings"
              v-model="content"
            />
          </b-col>
        </b-row>
      </div>
      <div class="row-group">
        <b-row class="mb-2">
          <b-col>
            <label>
              <h1>答錯解釋</h1>
            </label>
            <quill-editor
              @change="wrongAnswerChange"
              ref="qsEditor"
              :editorOptions="editorSettings"
              v-model="wrongAnswerContent"
            />
          </b-col>
        </b-row>
      </div>
      <div class="row-group">
        <transition-group name="flip-list" tag="div">
          <b-row v-for="(choice, index) in choices" v-bind:key="choice.id" class="mb-2">
            <!-- 選項表單開始 -->
            <div
              class="dragable"
              :index="index"
              draggable="true"
              @dragstart="dragStart"
              @dragend="$event.target.style.opacity = null;"
              @drag.prevent="drag"
              @dragover.prevent="dragOver"
              @drop.prevent="drop"
            >
              <!-- 選項標題 -->
              <!-- <b-row>
                <b-col>
                  <h1 :index="choice.id" @click="focusEditor">{{index}} - {{choice.id}}</h1>
                </b-col>
              </b-row>-->

              <b-row>
                <!-- 選項答案選取 -->
                <b-col
                  cols="auto"
                  @click="answerClick"
                  class="choice-radio d-flex align-items-center"
                  :class="answerId === choice.id ? 'checked' : ''"
                >
                  <input
                    type="radio"
                    class="radio"
                    @change="onAnswerChange"
                    :value="choice.id"
                    :checked="initAnswerId === choice.id ? true : false"
                    :disabled="isProcess"
                  >
                </b-col>

                <!-- 選項編輯區 -->
                <b-col
                  class="choice-editor"
                  cols="10"
                  draggable="false"
                  @dragend="returnFalse"
                  @dragstart="returnFalse"
                  @drag="returnFalse"
                >
                  <quill-editor
                    class="choiceeditor"
                    :content="choice.content"
                    @change="choiceContentChange($event, index)"
                    :ref="`choice-${choice.id}`"
                    @mousedown="returnFalse"
                    :editorOptions="editorSettings"
                  />
                  <!-- v-model="choice.content" -->
                </b-col>

                <!-- 操作工具列開始 -->
                <div class="toolbar">
                  <!-- 新增選項 -->
                  <div class="row">
                    <b-button class="tool" variant="success" @click="addChoiceEvent(index)">
                      <i class="fas fa-plus"></i>
                    </b-button>
                  </div>

                  <!-- 刪除選項 -->
                  <div class="row">
                    <b-button
                      class="tool"
                      :disabled="choiceDeleteDisable"
                      variant="danger"
                      @click="removeChoiceEvent($event, index)"
                    >
                      <i class="fas fa-minus"></i>
                    </b-button>
                  </div>

                  <!-- 與上方交換選項 -->
                  <div class="row">
                    <b-button
                      class="tool"
                      v-show="!index == 0"
                      @click="swapChoiceArg(index, index-1)"
                    >
                      <i class="fas fa-caret-up"></i>
                    </b-button>
                  </div>

                  <!-- 與下方交換選項 -->
                  <div class="row tool">
                    <b-button
                      class="tool"
                      v-show="!(index === (choices.length - 1))"
                      @click="swapChoiceArg(index, index+1)"
                    >
                      <i class="fas fa-caret-down"></i>
                    </b-button>
                  </div>
                </div>
              </b-row>
            </div>
          </b-row>
        </transition-group>
      </div>
    </div>
    <div ref="saveBlock" class="save-block d-flex justify-content-center">
      <b-row>
        <b-col>
          <b-button :disabled="isProcess" variant="success" @click="saveQuestionEvent">儲存</b-button>
        </b-col>
        <b-col>
          <b-button :disabled="isProcess" variant="danger" @click="recoveryQuestion">取消</b-button>
        </b-col>
      </b-row>
    </div>
  </div>
</template>

<style scoped>
/* .ql-editor {
  max-height:200px;
} */
.choiceeditor .ql-editor {
  min-height: 100px;
  max-height: 100px;
}

.checked {
  background: #2f7ae0;
}

/* radio button */
.radio {
  max-height: 100%;
  cursor: pointer;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
  display: inline-block;
  position: relative;
  background-color: #f1f1f1;
  color: green;
  top: 10px;
  height: 30px;
  width: 30px;
  border: 0;
  border-radius: 50px;
  margin-right: 7px;
  outline: none;
  margin-left: 10px;
}
.radio:checked::before {
  position: absolute;
  font: 13px/1 "Open Sans", sans-serif;
  left: 11px;
  top: 7px;
  content: "\02143";
  transform: rotate(40deg);
}
.radio:hover {
  background-color: #f7f7f7;
}
.radio:checked {
  background-color: #f1f1f1;
}
</style>


<style scoped>
.choice-radio {
  cursor: pointer;
  border: 1px solid lightgray;
  border-right: 0;
  padding: 0;
  margin-left: 30px;
}
/*
.choice-radio input {
display: flex;
align-items: center;
justify-content: center;
} */

.choice-editor {
  padding: 0;
}

.ans-radio div {
  padding: 0;
}

.save-block {
  border: 2px solid lightgray;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
  /* margin-top: 2px; */
}
.flip-list-item {
  transition: all 0.4s;
  display: inline-block;
}

.flip-list-move {
  transition: transform 0.4s;
}

.flip-list-leave-active {
  position: absolute;
}

/* div.ql-editor {
  min-height: 100px !important;
} */
.row-group {
  border: 2px solid #2f7ae0;
  /* border-radius: 15px; */
  margin: 15px;
  margin-top: 0;
  margin-bottom: 0;
  padding: 15px;
}

.c-scollbar {
  position: relative;
  overflow: auto;
  /* background-color: rgb(3, 169, 244); */
  border-color: #93af95;
  border-style: solid;
  border-width: 2px;
  border-bottom-width: 0;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
}

.dragable:hover .toolbar {
  display: block;
}

.toolbar {
  display: none;
  position: relative;
  top: 15px;
  right: 20px;
}

.toolbar .row .tool {
  min-width: 38px;
  max-width: 38px;
}
</style>


<script>
import uuid from "../../uuid-gen";

import debounce from "lodash.debounce";

// import { VueEditor, Quill } from "vue2-editor";
// import { ImageDrop } from 'quill-image-drop-module'
import ImageResize from "quill-image-resize-module";

import { mapState, mapGetters, mapActions } from "vuex";

import Quill from "quill";
// console.log(Quill)
import "quill/dist/quill.core.css";
import "quill/dist/quill.snow.css";
import "quill/dist/quill.bubble.css";

import { quillEditor } from "vue-quill-editor";

// Quill.register('modules/imageDrop', ImageDrop)
Quill.register("modules/imageResize", ImageResize);

import questionShow from "./question-show.vue";

let initAnswerId = null;

export default {
  components: {
    quillEditor,
    questionShow
  },

  props: {
    choices: { type: Array, default: () => [] },
    question: { type: String, default: "" },
    wrongAnswer: { type: String, default: "" },
    initAnswerId: { type: String, default: "" },
    inputId: { type: Number, default: null },
    questionId: { type: Number, default: null }
  },

  data() {
    return {
      isProcess: false,

      // 目前選取的ID(內部)
      currentId: null,

      // 問題輸入框內容
      content: null,

      wrongAnswerContent: null,

      // 元件初始化完成狀態
      isInit: false,

      // 元件復原(取消)狀態
      isRecovery: true,

      // 答案的UUID
      answerId: null,

      // 當前選項狀態是否可以刪除選項
      choiceDeleteDisable: true,

      // 當前拖曳選項的ID
      dragingIndex: null,

      // 前一次拖曳來源ID
      prvSourceIndex: null,

      // quill套件擴展功能
      editorSettings: {
        modules: {
          // imageDrop: true,
          imageResize: {}
        }
      },

      // quill套件的toolbar選項設定
      editorToolbar: [
        [{ size: ["small", false, "large", "huge"] }],
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        [
          "bold",
          "italic",
          "underline",
          "strike",
          "formula",
          "link",
          "script",
          "color"
        ],
        [{ list: "ordered" }, { list: "bullet" }],
        ["image", "video"]
      ]
    };
  },

  computed: {
    ...mapState("quiz", {
      selectId: "selectId",
      questions: "questions",
      isLoaded: "isLoaded",
      isLoadedFail: "isLoadedFail",
      initQuestion: "initQuestions"
    }),

    ...mapGetters("quiz", {
      questionNumber: "questionNumber",
      getQuestion: "getQuestion"
    }),
    getClientHeight() {
      return innerHeight - document.querySelector("nav").clientHeight - 5;
    }
  },

  updated() {
    // 確認是否為變更選項操作
    if (this.currentId !== this.inputId) {
      // 在下次更新才將內部狀態更新，用於判斷是否修改
      this.$nextTick(() => {
        this.currentId = this.inputId;
      });
    }

    if (this.initAnswerId !== this.answerId) {
      this.answerId = this.initAnswerId;
    }
  },

  watch: {
    choices: {
      handler: function(choice) {
        this.isChoiceDeleteDisable();
      },
      deep: false
    },

    question(val) {
      this.content = val;
    },

    wrongAnswer(val) {
      console.log("qq", val);
      this.wrongAnswerContent = val;
    }
  },

  methods: {
    ...mapActions("quiz", {
      updateQuestionContent: "updateQuestionContent",
      updateQuestionAnswerId: "updateQuestionAnswerId",
      updateWrongAnswerExplain: "updateWrongAnswerExplain",
      swapChoice: "swapChoice",
      removeChoice: "removeChoice",
      updateChoice: "updateChoice",
      recoveyQuestion: "recoveyQuestion",
      saveQuestion: "saveQuestion",
      addChoice: "addChoice"
    }),

    // 選項是否可以繼續刪除(小於兩項不能刪)
    isChoiceDeleteDisable() {
      this.choiceDeleteDisable = this.choices.length <= 2 ? true : false;
    },

    // 讀取目前網頁剩餘的高度
    setEditorHeight() {
      this.$refs.ediotorBlock.style.height =
        innerHeight -
        document.querySelector("nav").clientHeight -
        this.$refs.saveBlock.clientHeight -
        5 +
        "px";
    },

    // 問題輸入框debounce功能(50ms間隔)
    contentDebounce: debounce(function() {
      this.updateQuestionContent(this.content);
    }, 50),

    wrongAnswerDebounce: debounce(function() {
      this.updateWrongAnswerExplain(this.wrongAnswerContent);
    }, 50),

    // 取消按鈕事件(復原回原本狀態)
    recoveryQuestion() {
      // 告訴該組件目前為復原狀態
      this.recoveyQuestion();
      this.isRecovery = true;
      this.answerId = initAnswerId;
    },

    // question欄位內容改變
    contentChange() {
      this.contentDebounce();
    },

    choiceContentChange({ quill, html, text }, index) {
      this.updateChoice({ index: index, content: html });
    },

    wrongAnswerChange() {
      this.wrongAnswerDebounce();
    },

    // 選取答案改變
    answerChange(answerId) {
      this.answerId = answerId;
      this.updateQuestionAnswerId(this.answerId);
      // this.$emit("answer-value", this.answerId);
    },

    saveQuestionEvent() {
      this.saveQuestion();
    },

    // 刪除選項
    removeChoiceEvent(e, id) {
      if (this.answerId === this.choices[id].id) {
        this.answerChange(this.choices[id === 0 ? 1 : 0].id);
      }
      this.removeChoice(id);
    },

    // 新增選項
    addChoiceEvent(index) {
      this.addChoice({ index: index });
    },

    answerClick(e) {
      this.answerChange(e.target.getElementsByTagName("input")[0].value);
    },

    onAnswerChange(e) {
      this.answerChange(e.target.value);
    },

    drag(e) {
      const scrollbar = document.getElementsByClassName("c-scollbar")[0];
      const scroll = scrollbar.scrollTop;
      const step = 20;
      // 拖動過靠近上方邊界
      if (e.clientY < 50) {
        scrollbar.scrollTo({
          top: scroll - step
          //behavior: 'smooth',
        });
      }

      if (e.clientY > $(window).height() - 50) {
        scrollbar.scrollTo({
          top: scroll + step
          //behavior: 'smooth',
        });
      }
    },

    dragStart(e) {
      try {
        // 記錄拖動元件的索引
        this.dragingIndex = e.target.getAttribute("index");
        e.target.style.opacity = ".5";
      } catch (err) {
        return;
      }
    },

    dragOver(e) {
      const sourceIndex = this.dragingIndex;
      const targetIndex = e.target.closest(".dragable").getAttribute("index");

      // 確認動畫移動是否完成(來源與目標都指向自己)
      if (sourceIndex === targetIndex) {
        this.prvSourceIndex = null;
      }

      if (
        // 來源與目標不同
        sourceIndex !== targetIndex &&
        // 來源不能為空(拒絕從其他元件拉進來的來源)
        sourceIndex !== null &&
        // 避免動畫交互觸發事件(前一次來源與目標不相同)
        this.prvSourceIndex !== targetIndex
      ) {
        // 暫存來源資料
        const sourceData = this.choices[sourceIndex];
        const finalIndex = this.choices.length - 1;
        console.log(targetIndex);
        // 插入物件
        this.swapChoice({ sourceIndex, targetIndex });

        // 修改變更後的索引
        this.dragingIndex = targetIndex;
        this.prvSourceIndex = sourceIndex;
      }

      e.preventDefault();
    },

    drop(e) {
      this.dragingIndex = null;
      this.prvSourceIndex = null;
    },

    swapChoiceArg(source, target) {
      this.swapChoice({ sourceIndex: source, targetIndex: target });
    },

    returnFalse(e) {
      e.preventDefault();
      e.target.draggable = false;
      return false;
    },

    focusQsEditor() {
      this.$refs.qsEditor.quill.focus();
    },

    focusEditor(e) {
      this.$nextTick(() => {
        this.$refs[`choice-${e.target.getAttribute("index")}`][0].quill.focus();
      });

      this.returnFalse(e);
    },

    initCreated() {
      this.content = this.question;
      this.wrongAnswerContent = this.wrongAnswer;
      this.isInit = true;
      initAnswerId = this.answerId;
    },

    initMounted() {
      this.isChoiceDeleteDisable();
    }
  },

  created() {
    const vm = this;
    this.initCreated();
  },

  mounted() {
    // 當頁面載入完成
    setTimeout(() => {
      this.setEditorHeight();
    }, 0);

    this.initMounted();
  }
};
</script>
