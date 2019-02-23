<template>
  <b-container fluid class="col-md-8 col-md-offset-2">
    <b-row>
      <b-card
        :img-src="src"
        img-alt="Image"
        img-top
        tag="article"
        style="max-width: 50rem;"
        class="mb-2"
      >
        <h1><strong>{{title}}</strong></h1>
        <div v-html="getContent"></div>
        <b-row>
          <b-col>
            <b-progress :max="totalPage" show-progress class="w-100 mb-3">
              <b-progress-bar :value="currentPage">{{getShowPageText}}</b-progress-bar>
            </b-progress>
          </b-col>
        </b-row>
        <b-row align-h="between">
          <b-col cols="2">
            <button v-show="isPrvBtnShow" @click="prvPage" class="btn btn-primary">上一頁</button>
          </b-col>
          <b-col cols="2">
            <button v-show="isNextBtnShow" @click="nextPage" class="btn btn-primary">下一頁</button>
          </b-col>
        </b-row>
      </b-card>
    </b-row>
    <b-row>
      <a
        :href="getHref"
        v-show="isFnalPage && this.href"
        @click="nextPage"
        class="btn btn-primary"
      >進行模擬實驗</a>
    </b-row>
  </b-container>
</template>

<style scoped>
p {
  font-size: 1.2rem;
}
</style>

<script>
import * as showdown from "showdown";
const classMap = {
  table: "table table-hover table-bordered table-striped",
  td: "align-middle",
  thead: "thead-dark"
};
const bindings = Object.keys(classMap).map(key => ({
  type: "output",
  regex: new RegExp(`<${key}(.*)>`, "g"),
  replace: `<${key} class="${classMap[key]}" $1>`
}));

const converter = new showdown.Converter({
  tables: true,
  parseImgDimensions: true,
  extensions: [...bindings]
});
console.log(converter.getOption("tables"));
export default {
  data() {
    return {
      currentPage: 1,
      totalPage: 0,
      isNextBtnShow: null,
      isPrvBtnShow: null,
      isFnalPage: null,
      src: "",
      title: "",
      content: ""
    };
  },
  props: {
    items: {
      type: Array,
      default: () => [
        {
          src: "",
          title: "",
          content: ""
        }
      ]
    },
    href: {
      type: String
    }
  },
  computed: {
    getHref() {
      return `#/${this.href}`;
    },
    getShowPageText() {
      return `${this.currentPage}/${this.totalPage}`;
    },
    getContent() {
      return converter.makeHtml(this.content);
    },
    getSrcEmpty() {
      return this.src === "";
    }
  },
  methods: {
    nextPage() {
      if (this.currentPage < this.totalPage) {
        this.currentPage++;
        this.updateCard();
      }
    },
    prvPage() {
      if (this.currentPage > 0) {
        this.currentPage--;
        this.updateCard();
      }
    },
    updateCard() {
      const assertPath = "/image/";
      const index = this.currentPage - 1;
      // 判斷是否為外部URL
      const src =
        this.items[index].src
          .toString()
          .search(
            /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi
          ) == -1
          ? this.items[index].src === "" ? "" : assertPath + this.items[index].src
          : this.items[index].src;
      this.src = src;
      this.title = this.items[index].title;
      this.content = this.items[index].content;

      if (this.currentPage == 1) {
        this.isNextBtnShow = true;
        this.isPrvBtnShow = false;
        this.isFnalPage = false;
      } else if (this.currentPage == this.totalPage) {
        this.isNextBtnShow = false;
        this.isPrvBtnShow = true;
        this.isFnalPage = true;
      } else {
        this.isNextBtnShow = true;
        this.isPrvBtnShow = true;
        this.isFnalPage = false;
      }
    }
  },
  mounted() {
    this.totalPage = this.items.length;
    this.updateCard();
  }
};
</script>
