<template>
  <div class="MessList por box">
    <el-page-header @back="goBack" content="我的通知"></el-page-header>
    <el-table
      ref="filterTable"
      :data="tableData"
      size="mini"
      style="width: 100%">
      <el-table-column
        prop="date"
        label="发布日期"
        sortable
        width="100"
        class="hidden-sm-and-down"
        column-key="date"
      >
      </el-table-column>
      <el-table-column
        prop="name"
        min-width="250"
        sortable
        label="通知名称">
        <template slot-scope="mess">
          <el-link :underline="false" href="/student/message/xxxxxxx" target="_blank">{{mess.row.address}}</el-link>
        </template>
      </el-table-column>
      <el-table-column
        prop="tag"
        width="110"
        label="类型"
        :filters="label"
        :filter-method="filterTag"
        filter-placement="bottom-end">
        <template slot-scope="scope">
          <el-tag
            :type="scope.row.tag === '作业' ? 'primary' : 'success'"
            disable-transitions>{{scope.row.tag}}</el-tag>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination poa">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page.sync="currentPage"
        :pager-count="5" 
        :page-size="10"
        small
        layout="prev, pager, next, jumper"
        :total="100">
      </el-pagination>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      userData: "",
      currentPage: 1,
      label: [
        { text: '作业', value: '作业' },
        { text: '公告', value: '公告' },
      ],
      tableData: [{
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄',
        tag: '作业'
      }, {
        date: '2016-05-04',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1517 弄',
        tag: '公告'
      }, {
        date: '2016-05-01',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1519 弄',
        tag: '作业'
      }, {
        date: '2016-05-03',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1516 弄',
        tag: '公告'
      },{
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄',
        tag: '作业'
      }, {
        date: '2016-05-04',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1517 弄',
        tag: '公告'
      }, {
        date: '2016-05-01',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1519 弄',
        tag: '作业'
      }, {
        date: '2016-05-03',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1516 弄',
        tag: '公告'
      }],
    }
  },
  methods:{
    handleSizeChange(val) {
      console.log(`每页 ${val} 条`);
    },
    handleCurrentChange(val) {
      console.log(`当前页: ${val}`);
    },
    goBack(){
      this.$router.go(-1);
    },
    filterTag(value, row) {
      return row.tag === value;
    },
  }
}
</script>

<style scoped>
  .MessList{
    min-height: 755px;
    overflow: hidden;
    padding-bottom: 120px;
  }
  .MessList>.el-page-header{
    margin-bottom: 24px;
  }
  .pagination{
    margin: 0 auto;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
  }
  @media screen and (max-width:768px){
    /* .pagination{
      width: 110%;
      position: relative;
      left: -5%;
      margin-bottom: 24px;
    } */
  }
</style>