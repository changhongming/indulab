<template>
  <b-container fluid>
    <div class="c-scollbar" ref="ediotorBlock" >
    <div class="row-group">
      <b-row class="mb-2">
        <b-col>
          <label>
            <h1 @click="$refs.qsEditor.quill.focus()">題目</h1>
          </label>
          <vue-editor @text-change="contentChange" ref="qsEditor" :editorToolbar="editorToolbar" :editorOptions="editorSettings" v-model="content"></vue-editor>
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
          <b-row>
            <b-col>
              <h1 :index="choice.id" @click="focusEditor">{{index}} - {{choice.id}}</h1>
            </b-col>
          </b-row>

          <b-row>

            <!-- 選項答案選取 -->
            <b-col cols="auto" class="align-self-center">
              <input
                type="radio"
                @change="onAnswerChange"
                :value="choice.id"
                :checked="initAnswerId === choice.id ? true : false"
              >
            </b-col>

            <!-- 選項編輯區 -->
            <b-col
              cols="10"
              draggable="false"
              @dragend="returnFalse"
              @dragstart="returnFalse"
              @drag="returnFalse"
            >
              <vue-editor
                :ref="`choice-${choice.id}`"
                @text-change="contentChange"
                @mousedown="returnFalse"
                :editorOptions="editorSettings"
                v-model="choice.content"
              ></vue-editor>
            </b-col>

            <!-- 操作工具列開始 -->
            <b-col cols="1" class="toolbar">
              <!-- 新增選項 -->
              <b-row>
                <b-col>
                  <b-button variant="success" @click="addChoice(index)">
                    <i class="fas fa-plus"></i>
                  </b-button>
                </b-col>
              </b-row>

              <!-- 刪除選項 -->
              <b-row>
                <b-col>
                  <b-button
                    :disabled="choiceDeleteDisable"
                    variant="danger"
                    @click="removeChoice($event, index)"
                  >
                    <i class="fas fa-minus"></i>
                  </b-button>
                </b-col>
              </b-row>

              <!-- 與上方交換選項 -->
              <b-row>
                <b-col>
                  <b-button v-show="!index == 0" @click="swapChoice(index, -1)">
                    <i class="fas fa-caret-up"></i>
                  </b-button>
                </b-col>
              </b-row>

              <!-- 與下方交換選項 -->
              <b-row>
                <b-col>
                  <b-button
                    v-show="!(index === (choices.length - 1))"
                    @click="swapChoice(index, 1)"
                  >
                    <i class="fas fa-caret-down"></i>
                  </b-button>
                </b-col>
              </b-row>
            </b-col>
          </b-row>
        </div>
      </b-row>
      </transition-group>
    </div>
    </div>
    <div ref="saveBlock" class="save-block d-flex justify-content-center">
      <b-row>
        <b-col>
          <b-button variant="success" @click="saveQuestion">儲存</b-button>
        </b-col>
        <b-col>
          <b-button variant="danger" @click="recoveryQuestion">取消</b-button>
        </b-col>
      </b-row>
    </div>
  </b-container>
</template>

<style scoped>
.save-block {
  margin-top: 5px;
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

.q-title {
  /* border: 2px solid black; */
  border-left-width: 5px;
  border-left-color: blue;
  border-left-style: solid;
  padding-left: 15px;
}

.row-group {
  border: 4px solid red;
  border-radius: 20px;
  margin: 25px;
  padding: 15px;
}

.c-scollbar {
  position: relative;
  overflow: auto;
}

.dragable:hover .toolbar {
  display: block;
}

.toolbar {
  display: none;
}
</style>


<script>
import uuid from "../../uuid-gen";

import debounce from "lodash.debounce";

import { VueEditor, Quill } from "vue2-editor";
// import { ImageDrop } from 'quill-image-drop-module'
import ImageResize from "quill-image-resize-module";

// Quill.register('modules/imageDrop', ImageDrop)
Quill.register("modules/imageResize", ImageResize);

import questionShow from "./question-show.vue";

let initAnswerId = null;

export default {
  components: {
    VueEditor,
    questionShow
  },

  props: {
    choices: Array,
    question: String,
    initAnswerId: String,
    inputId: {type: Number, default: null},
    questionId: {type: Number, default: null}
  },

  data() {
    return {
      // 目前選取的ID(內部)
      currentId: null,

      // 問題輸入框內容
      content: null,

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
        ["bold", "italic", "underline", "strike", "formula", "link"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["image", "video"]
      ]
    };
  },

  computed: {
    getClientHeight() {
      // console.log(this.$refs.saveBlock)
      return innerHeight - document.querySelector('nav').clientHeight - 5;
    }
  },


  updated() {
    // 確認是否為變更選項操作
    if(this.currentId !== this.inputId) {
      // 在下次更新才將內部狀態更新，用於判斷是否修改
      this.$nextTick(() => {
        this.currentId = this.inputId;
      });
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
    }
  },

  methods: {
    // 選項是否可以繼續刪除(小於兩項不能刪)
    isChoiceDeleteDisable() {
      this.choiceDeleteDisable = this.choices.length <= 2 ? true : false;
    },

    // 讀取目前網頁剩餘的高度
    setEditorHeight() {
      console.log(this.$refs.saveBlock.clientHeight);
      this.$refs.ediotorBlock.style.height = (innerHeight - document.querySelector('nav').clientHeight - this.$refs.saveBlock.clientHeight - 5) + "px";
    },

    // 問題輸入框debounce功能(500ms間隔)
    contentDebounce: debounce(function(){
      this.$emit("on-question-change", this.content);
    }, 50),

    saveQuestion() {
      // 使用Ajax儲存目前問題資料
      axios.post('/question', {
        id: this.questionId,
        answer_id: this.answerId,
        question: this.question,
        choices: JSON.stringify(this.choices),
        /** 目前題目排序尚未實作(由上一層父組件用props傳入order) **/
        order: 0
      })
      .then((res) => {
        console.log(res);
        // 儲存成功
        this.$emit('save-state-change', true);
        this.$emit('save-success');
      })
      .catch((err) => {
        /** 儲存失敗(需要實作跳出儲存失敗視窗) **/
        console.log(err);
      })
    },

    // 取消按鈕事件(復原回原本狀態)
    recoveryQuestion() {
      // 送出復原信號
      this.$emit('recovery');
      // 告訴該組件目前為復原狀態
      this.isRecovery = true;
      this.answerId = initAnswerId;
      // 送出儲存狀態為以儲存(因為復原回資料庫剛拿出來的資料)
      this.$emit('save-state-change', true);
    },

    // question欄位內容改變
    contentChange() {
      this.contentDebounce();
      this.showNotSave();
    },

    showNotSave() {
      if(this.inputId === this.currentId && !this.isRecovery) {
        this.$emit('save-state-change', false);
      }

      // 復位isRecovery數值，使它可以重新判斷是否儲存
      this.isRecovery = false;
    },

    // 選取答案改變
    answerChange(answerId) {
      this.showNotSave();
      this.answerId = answerId;
      this.$emit("answer-value", this.answerId);
    },

    // 刪除選項
    removeChoice(e, id) {
      if(this.answerId === this.choices[id].id) {
        this.answerChange(this.choices[id === 0 ? 1 : 0].id)
      }
      this.choices.splice(id, 1);
    },

    // 新增選項
    addChoice(id) {
      const uid = uuid();

      if (this.choices.length === id + 1) {
        this.choices[this.choices.length - 1].isLowest = false;
        this.choices.push({
          id: uid,
          content: uid,
          isHighest: false,
          isLowest: true
        });
      } else {
        this.choices.splice(id + 1, 0, {
          id: uid,
          content: uid,
          isHighest: false,
          isLowest: false
        });
      }
      this.showNotSave();
    },

    onAnswerChange(e) {
      this.answerChange(e.target.value)
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
      // 記錄拖動元件的索引
      this.dragingIndex = e.target.getAttribute("index");
      e.target.style.opacity = ".5";
    },

    dragOver(e) {
      const sourceIndex = this.dragingIndex;
      const targetIndex = e.target.closest(".dragable").getAttribute("index");

      // 確認動畫移動是否完成(來源與目標都指向自己)
      if(sourceIndex === targetIndex) {
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

        // 插入物件
        this.choices.splice(sourceIndex, 1);
        this.choices.splice(targetIndex, 0, sourceData);

        // 修改變更後的索引
        this.dragingIndex = targetIndex;
        this.prvSourceIndex = sourceIndex;
        this.showNotSave();
      }

      e.preventDefault();
    },

    drop(e) {
      this.dragingIndex = null;
      this.prvSourceIndex = null;
    },

    swapChoice(id, loc) {
      const data = this.choices[id];
      const finalPos = id + loc;
      const finalIndex = this.choices.length - 1;

      this.choices.splice(id, 1);
      this.choices.splice(finalPos, 0, data);
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
      if(this.initAnswerId)
        this.answerId = this.initAnswerId;
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
