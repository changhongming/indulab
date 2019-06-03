<template>
  <div>
    <h1>Q{{ questionNumber + 1 }}.</h1>
    <div class="question" v-html="question"></div>
    <b-row>
      <b-col md="6" v-for="choice in choices" v-bind:key="choice.id">
        <div class="choice">
          <div class="choice-content" :class="choice.id === answer ? 'checked' : ''" @click="onSelectAnswerChange">
            <input
              :id="choice.id"
              class="radio"
              type="radio"
              name="choices"
              :value="choice.id"
              :checked="choice.id === answer ? true : false"
              :disabled="isReadOnly"
            />
            <label :for="choice.id">{{removePtag(choice.content)}}</label>
            <!-- <span v-html="choice.content">{{ choice.content }}</span> -->
          </div>
        </div>
      </b-col>
    </b-row>
  </div>
</template>

<style scoped>
.question {
  border-radius: 20px;
  background: #252525;
  color: #f0f0f0;
  padding: 15px;
}

.choice {
  cursor: pointer;
  border-radius: 20px;
  background: #005299;
  color: #f1f1f1;
  margin: 10px;
}
.choice:hover {
  background: #0032ff;
  color: #f7f7f7;
}

.choice-content {
  position: relative;
  display: block;
  padding-left: 30px;
  cursor: inherit;
}

.choice-content > label {
  cursor: pointer;
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
  top: 10px;
  height: 30px;
  width: 30px;
  border: 0;
  border-radius: 50px;
  margin-right: 7px;
  outline: none;
}

.radio.error::before {
  position: absolute;
  font: 13px/1 "Open Sans", sans-serif;
  left: 10px;
  top: 8px;
  content: "\02715";
  color: red;
  /* transform: rotate(40deg); */
}

.choice > .choice-content.checked.error {
  /* border-radius: 20px; */
  background: red;
}

.radio:checked::before {
  position: absolute;
  font: 13px/1 "Open Sans", sans-serif;
  left: 10px;
  top: 8px;
  content: "\02713";
  color: green;
  /* transform: rotate(40deg); */
}
.radio:hover {
  background-color: #f7f7f7;
}
.radio:checked {
  background-color: #f1f1f1;
}
.choice > .choice-content.checked {
  border-radius: 20px;
  background: green;
}

.choice > .choice-content.checked:hover {
  background: #14df01;
}

/* 深層Vue loader編譯，使用在v-html嵌套CSS */
.question >>> img:hover {
  width: 100%;
}
</style>


<script>
export default {
  data() {
    return {
      answer: this.initAnswer,
      qsn: 1,
    };
  },

  watch: {
    questionNumber(val) {
      console.log('qs', val);
      this.qsn = val;
      this.$forceUpdate();
    },

    initAnswer(val) {
      this.answer = this.initAnswer;
    }
  },

  props: {
    question: String,
    choices: Array,
    questionNumber: Number,
    initAnswer: String,
    isReadOnly: {
      type: Boolean,
      default: false,
    }
  },

  computed: {
    removePtag(content) {
      return content =>
        content.indexOf("<p>") != -1
          ? content.substring(3, content.length - 4)
          : content;
    }
  },

  methods: {
    onSelectAnswerChange(e) {
      if(this.isReadOnly)
        return;

      const target = (function sortTarget(e) {
        switch (e.target.tagName) {
          case "INPUT":
            return e.target;
          case "DIV":
            return e.target
              .getElementsByTagName("input")[0];
          case "SPAN":
            return e.target.parentNode.firstChild;
        }
      })(e);

      this.answer = target.getAttribute("value");
      this.$emit("choice-change", this.answer);
    }
  },

  beforeMount() {
    console.log("test");
    if (this.initAnswer) {
      this.answer = this.initAnswer;
    } else {
      this.answer = null;
    }
  },

  mounted() {
    console.log(this.choices, this.answer);
  }
};
</script>
