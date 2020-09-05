<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>教师绩效汇总</h1>
    </div>
    <div>
      <el-form :inline="true" :model="sel" class="demo-form-inline">
        <el-form-item label="">
          <el-select v-model="sel.semester" placeholder="选择学期">
            <el-option label="2019-2020下学期" value="192002"></el-option>
            <el-option label="2019-2020上学期" value="192001"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-date-picker
            v-model="sel.date"
            type="month"
            placeholder="选择日期">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="">
          <el-select v-model="sel.teacher" placeholder="选择教师">
            <el-option label="张某某" value="1"></el-option>
            <el-option label="王某某" value="2"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary">查询</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="box">
      <el-tree
        class="filter-tree"
        :data="data"
        :props="defaultProps"
        :filter-node-method="filterNode"
        ref="tree">
      </el-tree>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      sel:{
        semester: "",
        date: "",
        teacher: "",
      },
      data:[
        {
          id: 1,
          label: "信息部--张某某",
          children: [
            {
              id: "1_1",
              label: "课时：14",
            },
            {
              id: "1_2",
              label: "升旗：2",
            },
            {
              id: "1_3",
              label: "活动主持：1",
            },
            {
              id: "1_4",
              label: "比赛：1",
            },
            {
              id: "1_5",
              label: "晚修：4",
            },
            {
              id: "1_6",
              label: "行政：2",
            },
          ],
        },
      ],
    }
  }
}
</script>

<style>

</style>