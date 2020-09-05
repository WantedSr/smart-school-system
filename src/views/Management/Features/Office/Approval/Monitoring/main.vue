<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>工作监控</h1>
    </div>
    <div>
      <el-table
        :data="tableData"
        border 
        size="small"
        style="width: 100%">
        <el-table-column
          prop="serial_num"
          label="流水号"
          sortable>
        </el-table-column>
        <el-table-column
          prop="work_title"
          label="事务标题"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="申请类型"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="date"
          label="发起日期"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="now"
          label="当前审批流程"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          sortable>
          <template slot-scope="scope">
            <el-button size="mini" :type="scope.row.state == '审批完成' ? 'success' : 'warning'">{{scope.row.state}}</el-button>
            <el-button @click="onLook" size="mini" type="primary">查看</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      n: this.$route.query.n,
      tableData:[
        {
          serial_num: "20171234",
          work_title: "这是一篇示例文档用于测试的文档",
          type: "教学活动",
          date: "2020-4-18",
          now: "5.我的意见",
          state: "审批中",
        },
        {
          serial_num: "20171234",
          work_title: "这是一篇示例文档用于测试的文档",
          type: "教学活动",
          date: "2020-4-18",
          now: "7.行政部意见",
          state: "审批完成",
        },
      ]
    }
  }
}
</script>

<style>

</style>