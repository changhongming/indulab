<template>
  <ul class="pagination b-pagination pagination-md">
    <li :class="['page-item', {'disabled': currentPage === 1}]">
      <a @click="prvPage" class="page-link" href="#">‹</a>
    </li>
    <li
      v-for="index in totalPage"
      ref="pageList"
      :class="['page-item', {'active': currentPage === index, 'non-answer': !isAnswerList[index - 1] }]"
      :key="index"
      :value="isAnswerList"
    >
      <a @click="onClick" :page="index" class="page-link" href="#">{{index}}</a>
    </li>
    <li :class="['page-item', {'disabled': currentPage === totalPage}]">
      <a @click="nextPage" class="page-link" href="#">›</a>
    </li>
  </ul>
</template>

<style scoped>
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
    }
  },

  watch: {
    currentPage(val, oldVal) {
      this.$refs.pageList[val - 1].classList.add("active");
      this.$refs.pageList[oldVal - 1].classList.remove("active");
      console.log(val);
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
  },

  created() {
    console.log(this.$props);
  }
};
</script>

