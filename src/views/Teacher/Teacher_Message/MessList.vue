<template>
  <div class="MessList box por">
    <el-page-header @back="goBack" content="我的通知"></el-page-header>
    <el-table
      ref="filterTable"
      :data="tableData"
      style="width: 100%">
      <el-table-column
        prop="date"
        label="发布日期"
        sortable
        width="100"
        column-key="date"
      >
      </el-table-column>
      <el-table-column
        prop="name"
        min-width="250"
        label="通知名称">
        <template slot-scope="mess">
          <el-link :underline="false" href="/teacher/message/xxxxxxx" target="_blank">{{mess.row.address}}</el-link>
        </template>
      </el-table-column>
      <el-table-column
        prop="tag"
        label="类型"
        width="100"
        :filters="label"
        :filter-method="filterTag"
        filter-placement="bottom-end">
        <template slot-scope="scope">
          <el-tag
            :type="scope.row.tag === '部' ? 'primary' : 'success'"
            disable-transitions>{{scope.row.tag}}</el-tag>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination poa">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage4"
        :page-sizes="[100, 200, 300, 400]"
        :page-size="100"
        layout="total, sizes, prev, pager, next, jumper"
        :total="400">
      </el-pagination>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      userData: "",
      currentPage4: 4,
      label: [
        { text: '部', value: '部' },
        { text: '校', value: '校' },
      ],
      tableData: [{
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄',
        tag: '部'
      }, {
        date: '2016-05-04',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1517 弄',
        tag: '部'
      }, {
        date: '2016-05-01',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1519 弄',
        tag: '校'
      }, {
        date: '2016-05-03',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1516 弄',
        tag: '部'
      },{
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄',
        tag: '校'
      }, {
        date: '2016-05-04',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1517 弄',
        tag: '校'
      }, {
        date: '2016-05-01',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1519 弄',
        tag: '校'
      }, {
        date: '2016-05-03',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1516 弄',
        tag: '部'
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

<style>
  .MessList{
    min-height: 755px;
  }
  .MessList>.el-page-header{
    margin-bottom: 10px;
  }
  .pagination{
    margin: 0 auto;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
  }
</style>