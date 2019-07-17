<template>
  <b-container fluid>
    <!-- 搜尋輸入介面 -->
    <b-row>
      <b-col md="6" class="my-1">
        <b-form-group label-cols-sm="3" label="搜尋" class="mb-0">
          <b-input-group>
            <b-form-input v-model="filter" placeholder="輸入搜尋"/>
            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''">清除</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>
      </b-col>

      <!-- 欄位排序介面 -->
      <b-col md="6" class="my-1">
        <b-form-group label-cols-sm="3" label="排序" class="mb-0">
          <b-input-group>
            <b-form-select v-model="sortBy" :options="sortOptions">
            </b-form-select>
            <b-form-select :disabled="!sortBy" v-model="sortDesc" slot="append">
              <option :value="false">小到大</option>
              <option :value="true">大到小</option>
            </b-form-select>
          </b-input-group>
        </b-form-group>
      </b-col>
    </b-row>

    <!-- 表格介面 -->
    <b-table
      striped
      hover
      show-empty
      stacked="md"
      :items="items"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      empty-text="資料為空"
      empty-filtered-text="沒有符合的資料"
      @filtered="onFiltered"
    ></b-table>

    <!-- 換頁介面 -->
    <b-row>
      <b-col md="6" class="my-1">
        <b-pagination
          :total-rows="totalRows"
          :per-page="perPage"
          v-model="currentPage"
          class="my-0"
        />
      </b-col>
    </b-row>
  </b-container>
</template>

<script>
export default {
  data() {
    return {
      currentPage: 1,
      totalRows: 0,
      sortBy: null,
      sortDesc: false,
      sortDirection: "asc",
      filter: null,
      modalInfo: { title: "", content: "" }
    };
  },
  props: {
    items: { type: Array, default: ()=> [ ] },
    fields: { type:Array, default: ()=> [ { key:null } ] },
    perPage: { type: Number, default: 5 }
  },
  computed: {
    sortOptions() {
      // 創建一個排序的選單內容
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return { text: f.label, value: f.key };
        });
    }
  },
  methods: {
    onFiltered(filteredItems) {
      // 觸發搜尋功能將結果總列數重新計算並回到第1頁
      this.totalRows = filteredItems.length;
      this.currentPage = 1;
    }
  },
  mounted() {
    // 排序表單選到第一個欄位
    this.sortBy = this.fields[0].key;
  }
};
</script>
