<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>收入查询</h1>
    </div>
    <el-form :inline="true" ref="form" :model="sel" label-width="80px">
      <el-form-item label="">
        <el-select v-model="sel.department" placeholder="查询部门">
          <el-option label="信息部" value="shanghai"></el-option>
          <el-option label="财经部" value="beijing"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select v-model="sel.teacher" placeholder="请选择教师">
          <el-option label="王某某" value="shanghai"></el-option>
          <el-option label="张某某" value="beijing"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select v-model="sel.type" placeholder="请选择查询方式">
          <el-option label="月收入" value="month"></el-option>
          <el-option label="年收入" value="year"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker
          v-model="sel.date"
          :type="sel.type"
          placeholder="选择时间">
        </el-date-picker>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSel">查询</el-button>
      </el-form-item>
    </el-form>
    <div class="box" v-if="sel.type == 'year' && choose">
      <el-table 
        :data="tableData2" 
        border 
        show-summary  
        style="100%">
        <el-table-column sortable width="130" label="时间" prop="date"></el-table-column>
        <el-table-column sortable label="摘要" prop="summary"></el-table-column>
        <el-table-column sortable label="应发薪资" prop="should_money"></el-table-column>
        <el-table-column sortable label="税金" prop="tax"></el-table-column>
        <el-table-column sortable label="实发薪资" prop="real_money"></el-table-column>
        <el-table-column sortable label="卡号" prop="card"></el-table-column>
      </el-table>
    </div>
    <div class="box" v-if="sel.type == 'month' && choose">
      <el-table 
        :data="tableData1" 
        border  
        style="100%">
        <el-table-column sortable width="110" label="时间" prop="date"></el-table-column>
        <el-table-column sortable label="摘要" prop="summary"></el-table-column>
        <el-table-column sortable label="项目" prop="product"></el-table-column>
        <el-table-column sortable label="金额" prop="money"></el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      sel: {
        department: "",
        teacher: "",
        type: "month",
        date: "",
      },
      choose: false,
      tableData1:[
        {
          date: "2020-04",
          summary: "2020年4月正式教工薪资",
          product: "岗位工资",
          money: "2.50",
        }
      ],
      tableData2:[
        {
          date: "2020",
          summary: "2020年正式教工薪资",
          should_money: "125.5",
          tax: "125",
          real_money: "0.5",
          card: "xxxx-xxxx-xxxx-1234"
        },
      ]
    }
  },
  methods:{
    onSel(){
      this.choose = true;
    }
  }
}
</script>

<style>

</style>