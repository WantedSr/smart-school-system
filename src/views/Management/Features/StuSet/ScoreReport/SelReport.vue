<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>成绩导入管理</h1>
    </div>
    <div>
      <el-form :inline="true" :model="sel" class="demo-form-inline">
        <el-form-item label="">
          <el-select v-model="sel.semester" placeholder="查询学期">
            <el-option label="2019-2020下学期" value="192002"></el-option>
            <el-option label="2019-2020上学期" value="192001"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </div>
    <div>
      <el-table
        :data="tableData"
        stripe
        border
        style="width: 100%;">
        <el-table-column
          type="index"
          label="序号"
          align="center"
          width="50">
        </el-table-column>
        <el-table-column
          prop="date"
          sortable
          align="center"
          label="导入日期">
        </el-table-column>
        <el-table-column
          prop="summary"
          sortable
          align="center"
          label="摘要">
        </el-table-column>
        <el-table-column
          prop="type"
          sortable
          align="center"
          label="类型">
        </el-table-column>
        <el-table-column
          prop="teacher"
          sortable
          width="130"
          align="center"
          label="教师">
        </el-table-column>
        <el-table-column
          prop="person_num"
          sortable
          align="center"
          width="90"
          label="人数">
        </el-table-column>
        <el-table-column
          prop="do"
          sortable
          align="center"
          width="100"
          label="操作">
          <template>
            <el-button type="danger" size="small">撤回</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      sel:{
        semester: "",
      },
      tableData:[
        {
          date: "2020-04-17",
          summary: "2019-2020下19软件1班期末成绩",
          type: "期末成绩-成绩汇总",
          teacher: "张某某",
          person_num: "50"
        },
      ]
    }
  }
}
</script>

<style>

</style>