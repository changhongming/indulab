<template>
  <ul class="pagination b-pagination pagination-md">
    <li :class="['page-item', {'disabled': currentPage === 1}]">
      <a @click="prvPage" class="page-link" href="#">‹</a>
    </li>
    <li
      v-for="index in totalPage"
      ref="pageList"
      :class="['page-item', {'active': currentPage === index, 'non-answer': !isAnswerList[index - 1], 'wrong-answer': answerResultList[index - 1] === false}]"
      :key="index"
    >
      <span class="tooltiptext">{{isAnswerState ? (answerResultList[index - 1] ? '正確' : '答錯') : (!isAnswerList[index - 1] ? '沒有作答' : '已回答') }}</span>
      <a @click="onClick" :page="index" class="page-link" href="#">{{index}}</a>
    </li>
    <li :class="['page-item', {'disabled': currentPage === totalPage}]">
      <a @click="nextPage" class="page-link" href="#">›</a>
    </li>
  </ul>
</template>

<style scoped>
li {
  position: relative;
  display: inline-block;
}
li .tooltiptext::after {
  content: " ";
  position: absolute;
  bottom: 100%;
  left: 50%;
  margin-left: -5px;
  border-width: 5px;
  border-style: solid;
  border-color: transparent transparent black transparent;
}
li .tooltiptext {
  visibility: hidden;
  width: 120px;
  margin-left: -60px;
  margin-top: 5px;
  left: 50%;
  top: 100%;
  background-color: black;
  color: #fff;
  text-align: center;
  padding: 5px 0;
  border-radius: 6px;
  position: absolute;
  z-index: 1;
}
li:hover  .tooltiptext {
  visibility:visible;
}
.page-item.non-answer .page-link {
  z-index: 1;
  color: var(--white);
  background-color: var(--warning);
  background-color: var(--warning);
}

.page-item.non-answer.active .page-link {
  z-index: 1;
  color: var(--white);
  background-color: var(--primary);
  border-color: var(--primary);
}

.page-item.wrong-answer.active .page-link {
  z-index: 1;
  color: var(--white);
  background-color: var(--primary);
  border-color: var(--primary);
}


.page-item.wrong-answer .page-link{
  z-index: 1;
  color: var(--white);
  background-color: var(--danger);
  border-color: var(--danger);
}
</style>


<script>
const DEFAULT_TOTAL_PAGE = 1;
const DEFAULT_SELECT_PAGE = 1;
export default {
  name: "TestQuestionBar",

  data() {
    return {
      currentPage: this.selectPage,
    };
  },

  props: {
    totalPage: {
      type: Number,
      default: DEFAULT_TOTAL_PAGE
    },
    selectPage: {
      type: Number,
      default: DEFAULT_SELECT_PAGE
    },
    isAnswerList: {
      type: Array,
      default: () => new Array(DEFAULT_TOTAL_PAGE).fill(true)
    },
    answerResultList: {
      type: Array,
      default: () => new Array(DEFAULT_TOTAL_PAGE).fill(true)
    },
    isAnswerState: {
      type: Boolean,
      default: false
    }
  },

  watch: {
    currentPage(val, oldVal) {
      this.$refs.pageList[val - 1].classList.add("active");
      this.$refs.pageList[oldVal - 1].classList.remove("active");
    },

    selectPage(val) {
      this.currentPage = val;
    }
  },

  mounted() {
    console.log(this.pageCount);
  },

  methods: {
    onClick(e) {
      const page = e.target.getAttribute("page");
      this.currentPage = Number(page);
      this.$emit("change", page);
    },
    nextPage() {
      this.currentPage++;
      this.$emit("change", this.currentPage);
    },
    prvPage() {
      this.currentPage--;
      this.$emit("change", this.currentPage);
    }
  }
};
</script>

