<template>
  <div class="Stureward box por">
    <el-page-header @back="goBack" content="奖惩情况"></el-page-header>

    <div class="moreScore">
      本学期总分：<el-tag v-if="value != ''">{{score}}</el-tag>
    </div>
    <div class="selSemester">
      <span>请选择学期</span>
      <el-select v-model="value" placeholder="请选择学期">
        <el-option label="2019-2020上学期" value="192001"></el-option>
        <el-option label="2018-2019下学期" value="181902"></el-option>
        <el-option label="2018-2019上学期" value="181901"></el-option>
      </el-select>  
    </div>

    <el-table
      v-if="value != ''"
      ref="filterTable"
      :data="tableData"
      style="width: 100%">
      <el-table-column
        prop="date"
        label="日期"
        sortable
        width="180"
        column-key="date"
        :filters="[{text: '2016-05-01', value: '2016-05-01'}, {text: '2016-05-02', value: '2016-05-02'}, {text: '2016-05-03', value: '2016-05-03'}, {text: '2016-05-04', value: '2016-05-04'}]"
        :filter-method="filterHandler"
      >
      </el-table-column>
      <el-table-column
        prop="type"
        label="性质"        
        :filters="[{ text: '加分', value: '加分' }, { text: '减分', value: '减分' }]" 
        :filter-method="filterTag" 
        filter-placement="bottom-end"
        width="180">
      </el-table-column>
      <el-table-column
        prop="address"
        label="原因"
        :formatter="formatter">
      </el-table-column>
      <el-table-column
        prop="tag"
        label="分数"
        width="100">
        <template slot-scope="scope">
          <el-tag
            :type="scope.row.tag < 0 ? 'danger' : 'success'"
            disable-transitions>{{scope.row.tag}}</el-tag>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  data(){
    return{
      score: 100,
      value: "",
      tableData: [{
        date: '2016-05-02',
        type: '加分',
        address: '好人好事',
        tag: '+1'
      }, {
        date: '2016-05-04',
        type: '减分',
        address: '上课迟到',
        tag: '-5'
      }, {
        date: '2016-05-01',
        type: '加分',
        address: '本月全勤',
        tag: '+2'
      }, {
        date: '2016-05-03',
        type: '加分',
        address: '全国竞赛1等奖',
        tag: '+15'
      }, {
        date: '2016-05-04',
        type: '减分',
        address: '破坏学校公共财产',
        tag: '-20'
      }, {
        date: '2016-05-01',
        type: '加分',
        address: '全国计算机等级一级证书',
        tag: '+10'
      }, {
        date: '2016-05-03',
        type: '加分',
        address: '打架斗殴',
        tag: '-20'
      }]
    }
  },
  methods:{
    resetDateFilter() {
      this.$refs.filterTable.clearFilter('date');
    },
    clearFilter() {
      this.$refs.filterTable.clearFilter();
    },
    formatter(row, column) {
      return row.address;
    },
    filterTag(value, row) {
      return row.tag == value;
    },
    filterHandler(value, row, column) {
      const property = column['property'];
      return row[property] === value;
    },
    goBack() {
      this.$router.go(-1);
    },
  }
}
</script>

<style>
  .Stureward{
    height: 755px;
  }
  .Stureward>.selSemester>span{
    margin-right: 12px;
  }
  .Stureward>.el-page-header{
    margin-bottom: 30px;
  }
  .Stureward>.moreScore{
    margin-bottom: 16px;
  }
  .el-select{
    margin-bottom: 30px;
  }
  .pagination{
    margin: 0 auto;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
  }
</style>