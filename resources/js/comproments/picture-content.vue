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
        <h1>
          <strong>{{title}}</strong>
        </h1>
        <div v-html="getContent"></div>
        <b-row>
          <b-col>
            <b-progress :max="totalPage" show-progress class="w-100 mb-3">
              <b-progress-bar :value="currentPage">{{getShowPageText}}</b-progress-bar>
            </b-progress>
          </b-col>
        </b-row>
        <b-row align-h="center">
          <b-pagination
            size="md"
            :total-rows="totalPage"
            v-model="currentPage"
            :per-page="1"
            :limit="8"
          />
        </b-row>
        <b-row align-h="center">
          <b-alert
            variant="danger"
            fade
            :show="!isAllRead && href && isFnalPage"
            @dismissed="showDismissibleAlert=false"
          >請看完每一頁後繼續</b-alert>
        </b-row>
        <b-row align-h="center">
          <button
            v-show="isFnalPage && href"
            :disabled="!isAllRead"
            @click="done"
            class="btn btn-danger"
          >進行模擬實驗</button>
        </b-row>
        <!-- <b-row align-h="between">
          <b-col cols="2">
            <button v-show="isPrvBtnShow" @click="prvPage" class="btn btn-primary">上一頁</button>
          </b-col>
          <b-col cols="2">
            <button v-show="isNextBtnShow" @click="nextPage" class="btn btn-primary">下一頁</button>
          </b-col>
        </b-row>-->
      </b-card>
    </b-row>
  </b-container>
</template>

<style scoped>
p {
  font-size: 1.2rem;
}
</style>

<script>
// 解析markdown格示並轉換為html
import * as showdown from "showdown";
// showdown 公式解析套件(Latex Katex)
import showdownKatex from "showdown-katex";
// showdown 的 markdown格示文件class註冊(對tag)
const classMap = {
  table: "table table-hover table-bordered table-striped",
  td: "align-middle",
  thead: "thead-dark",
  blockquote: "blockquote1"
};

// 綁定classMap class到特定tag上
const bindings = Object.keys(classMap).map(key => ({
  type: "output",
  regex: new RegExp(`<${key}(.*)>`, "g"),
  replace: `<${key} class="${classMap[key]}" $1>`
}));

// 建構一個markdown轉換html物件
const converter = new showdown.Converter({
  // 開啟md識別表格轉換html功能 ||| |--|--| |||
  tables: true,
  // 開啟md識別引用轉換html功能 >
  splitAdjacentBlockquotes: true,
  // 開啟md識別圖片轉換html功能 ![]()
  parseImgDimensions: true,
  // 開啟md識別程式碼轉換html功能 ``` ```
  ghCodeBlocks: true,
  // 掛載延伸功能
  extensions: [
    ...bindings,
    // 解析公式轉換功能Katex Latex
    showdownKatex({
      // maybe you want katex to throwOnError
      throwOnError: true,
      // disable displayMode
      displayMode: false,
      // change errorColor to blue
      errorColor: "#1500ff"
    })
  ]
});
export default {
  data() {
    return {
      currentPage: 1,
      hasReadPage: {},
      totalPage: 0,
      isNextBtnShow: null,
      isPrvBtnShow: null,
      isFnalPage: null,
      isAllRead: false,
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
  watch: {
    currentPage: function(val) {
      if (!this.hasReadPage[val]) this.hasReadPage[val] = true;
      this.updateCard();
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
    done() {
      if (confirm("按下「確定」離開此頁面進行模擬實驗")) {
        const a = document.createElement("a");
        document.body.appendChild(a);
        a.style = "display: none";
        a.href = this.getHref;
        a.click();
        document.body.removeChild(a);
      }
    },
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
          ) === -1
          ? this.items[index].src === ""
            ? ""
            : assertPath + this.items[index].src
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
        console.log(Object.keys(this.hasReadPage).length);
        this.isFnalPage = true;
        if (Object.keys(this.hasReadPage).length === this.totalPage)
          this.isAllRead = true;
      } else {
        this.isNextBtnShow = true;
        this.isPrvBtnShow = true;
        this.isFnalPage = false;
      }
    }
  },
  mounted() {
    this.totalPage = this.items.length;
    this.hasReadPage[this.currentPage] = true;
    this.updateCard();
  }
};
</script>
