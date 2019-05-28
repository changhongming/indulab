<template>
  <div>
    <h1>Q{{questionNumber + 1}}.</h1>
    <div class="question" v-html="question">{{ question }}</div>
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
            >
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
  color: green;
  top: 10px;
  height: 30px;
  width: 30px;
  border: 0;
  border-radius: 50px;
  margin-right: 7px;
  outline: none;
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
      answer: this.initAnswer
    };
  },

  watch: {
    initAnswer(val) {
      this.answer = this.initAnswer;
    }
  },

  props: {
    question: String,
    choices: Array,
    questionNumber: Number,
    initAnswer: String
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
      function sortTarget() {
        switch (e.target.tagName) {
          case "INPUT":
            return e.target.getAttribute("value");
          case "DIV":
            return e.target
              .getElementsByTagName("input")[0]
              .getAttribute("value");
          case "SPAN":
            return e.target.parentNode.firstChild.getAttribute("value");
        }
      }
      this.answer = sortTarget();
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
