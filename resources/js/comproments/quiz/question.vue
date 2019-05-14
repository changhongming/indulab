<template>
  <b-container fluid class="c-scollbar" :style="{ height: getClientHeight + 'px'}">
    <div class="row-group">
      <b-row class="mb-2">
        <b-col>
          <label>
            <h1 @click="$refs.qsEditor.quill.focus()">題目</h1>
          </label>
          <vue-editor ref="qsEditor" :editorOptions="editorSettings" v-model="content"></vue-editor>
        </b-col>
      </b-row>
    </div>
    <button @click="returnFalse">strat</button>
    <div class="row-group">
      <b-row v-for="(choice, index) in choices" v-bind:key="choice.id" class="mb-2">
        <!-- 選項表單開始 -->
        <div
          class="dragable"
          :index="index"
          draggable="true"
          @dragstart="dragStart"
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
            <!-- <b-col>
        <div :index="index" draggable="true" @dragstart="dragStart" @drag="drag" @dragover="dragOver" @drop="drop">
          drag
        </div>
            </b-col>-->

            <!-- 選項答案選取 -->
            <b-col cols="auto" class="align-self-center">
              <input
                type="radio"
                @change="onAnswerChange"
                :name="answerUUID"
                :value="choice.id"
                :checked="answer === choice.id ? true : false"
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
    </div>
  </b-container>
</template>

<style scoped>
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

import { VueEditor, Quill } from "vue2-editor";
// import { ImageDrop } from 'quill-image-drop-module'
import ImageResize from "quill-image-resize-module";

// Quill.register('modules/imageDrop', ImageDrop)
Quill.register("modules/imageResize", ImageResize);

import questionShow from "./question-show.vue";

export default {
  components: {
    VueEditor,
    questionShow
  },

  props: {
    choices: Array,
    question: String,
    answer: String
  },

  data() {
    return {
      choiceDeleteDisable: false,

      content: "",

      answerUUID: uuid(),

      dragingIndex: null,
      dragingChangeIndex: null,

      editorSettings: {
        modules: {
          // imageDrop: true,
          imageResize: {}
        }
      },

      editorToolbar: [
        [{ size: ["small", false, "large", "huge"] }],
        [{ header: [1, 2, 3, 4, 5, 6, false] }],
        ["bold", "italic", "underline", "strike"],
        [{ list: "ordered" }, { list: "bullet" }],
        ["image", "url"]
      ]
    };
  },

  computed: {
    getClientHeight() {
      return innerHeight;
    }
  },

  watch: {
    choices: {
      handler: function(choice) {
        // 如選項小於等於2個則不能刪除選項
        this.choiceDeleteDisable = choice.length <= 2 ? true : false;
      },
      deep: false
    },

    content(val) {
      // 推送題目內容變更的消息到外部
      this.$emit("on-question-change", val);
    },

    question(val) {
      this.content = val;
    }
  },

  methods: {
    removeChoice(e, id) {
      this.choices.splice(id, 1);
    },

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
    },

    onAnswerChange(e) {
      this.$emit("answer-value", e.target.value);

      // console.log(e.target.value);
    },

    drag(e) {
      const scrollbar = document.getElementsByClassName("c-scollbar")[0];
      const scroll = scrollbar.scrollTop;
      const step = 20;
      // 拖動過靠近上方邊界
      if (e.clientY < 50) {
        //
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
      console.log(e.target);
      e.dataTransfer.setData("dragIndex", e.target.getAttribute("index"));
      // 記錄拖動元件的索引
      this.dragingIndex = e.target.getAttribute("index");
    },

    dragOver(e) {
      const sourceIndex = this.dragingIndex;
      const targetIndex = e.target.closest(".dragable").getAttribute("index");
      if (
        sourceIndex !== targetIndex &&
        this.dragingChangeIndex !== targetIndex
      ) {
        console.log("source", sourceIndex);
        console.log("target", targetIndex);
        // 暫存來源資料
        const sourceData = this.choices[sourceIndex];
        const finalIndex = this.choices.length - 1;
        // if(targetIndex == 0) {
        //   this.choices[0].isHighest = false;
        //   sourceData.isHighest = true;
        // }
        // else if(targetIndex == finalIndex) {
        //   console.log('final')
        //   this.choices[finalIndex].isLowest = false;
        //   sourceData.isLowest = true;
        // }
        // else if(sourceIndex == 0) {
        //   sourceData.isHighest = false;
        //   this.choices[targetIndex].isHighest = true;
        //   // sourceData.isHighest = true;
        // }
        // else if(sourceIndex == finalIndex) {
        //   sourceData.isLowest = false;
        //   this.choices[targetIndex].isLowest = true;
        // }

        // 插入物件
        this.choices.splice(sourceIndex, 1);
        this.choices.splice(targetIndex, 0, sourceData);

        // 修改變更後的索引
        this.dragingChangeIndex = targetIndex;
        this.dragingIndex = targetIndex;
      }

      e.preventDefault();
    },

    drop(e) {
      console.log("drop", e.target);
      const sourceIndex = e.dataTransfer.getData("dragIndex");
      const targetIndex = e.target.getAttribute("index");
      const sourceData = this.choices[sourceIndex];
      // if(sourceIndex !== targetIndex) {
      //   this.choices.splice(sourceIndex, 1);
      //   this.choices.splice(targetIndex, 0, sourceData);
      // }
      this.dragingIndex = null;
      console.log("drop", sourceIndex);
    },

    swapChoice(id, loc) {
      const data = this.choices[id];
      const finalPos = id + loc;
      const finalIndex = this.choices.length - 1;

      // if(finalPos === 0 && id === 1) {
      //   this.choices[0].isHighest = false;
      //   data.isHighest = true;
      // }
      // else if(finalPos === 1 && id === 0) {
      //   this.choices[1].isHighest = true;
      //   data.isHighest = false;
      // }
      // if(finalPos === finalIndex && id === finalIndex - 1) {
      //   this.choices[finalIndex].isLowest = false;
      //   data.isLowest = true;
      // }
      // if(finalPos === finalIndex - 1 && id === finalIndex) {
      //   data.isLowest = false;
      //   this.choices[finalIndex - 1].isLowest = true;
      // }

      this.choices.splice(id, 1);
      this.choices.splice(finalPos, 0, data);
    },

    returnFalse(e) {
      e.preventDefault();
      e.target.draggable = false;
      return false;
    },

    focusQsEditor() {
      console.log("focus");

      this.$refs.qsEditor.quill.focus();
    },

    focusEditor(e) {
      //console.log(this.$refs[`ch-${e.target.getAttribute('index')}`][0]);
      this.$nextTick(() => {
        this.$refs[`choice-${e.target.getAttribute("index")}`][0].quill.focus();
      });

      this.returnFalse(e);
    }
  },

  mounted() {
    this.content = this.question;
  }
};
</script>
