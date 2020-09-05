<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>学生成绩查询</h1>
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
          <el-select v-model="sel.department" placeholder="选择部门">
            <el-option label="信息部" value="shanghai"></el-option>
            <el-option label="财经部" value="beijing"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select v-model="sel.class" placeholder="选择考试">
            <el-option label="19软件1班" value="shanghai"></el-option>
            <el-option label="19网络2班" value="beijing"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select v-model="sel.student" placeholder="选择学生">
            <el-option label="李某某" value="shanghai"></el-option>
            <el-option label="黄某某" value="beijing"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary">查询</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div>
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
        department: "",
        class: "",
        student: "",
      },
      
      data:[
        {
          id: 1,
          label: "2017217041--19软件1班--李某某",
          children: [
            {
              id: "1_1",
              label: "语文：80",
            },
            {
              id: "1_2",
              label: "数学：100",
            },
            {
              id: "1_3",
              label: "英语：46",
            },
            {
              id: "1_4",
              label: "物化：97",
            },
            {
              id: "1_5",
              label: "历史：27",
            },
            {
              id: "1_6",
              label: "体育：21",
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