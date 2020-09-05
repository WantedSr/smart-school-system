<template>
  <div class="certificatelist box">
    <el-page-header @back="goBack" content="奖惩情况"></el-page-header>
    <el-table
      :data="tableData"
      stripe
      style="width: 100%">
      <el-table-column
        prop="username"
        label="姓名"
        width="100">
        <template>
          {{username}}
        </template>
      </el-table-column>
      <el-table-column
        prop="name"
        label="证书名称">
      </el-table-column>
      <el-table-column
        prop="type"
        label="类型"
        width="80">
      </el-table-column>
      <el-table-column
        prop="level"
        label="级别"
        width="80">
      </el-table-column>
      <el-table-column
        prop="department"
        label="颁布部门">
      </el-table-column>
      <el-table-column
        prop="id"
        label="证书编号">
      </el-table-column>
      <el-table-column
        prop="do"
        label="证书照片"
        width="100">
        <template>
          <el-link @click="openImg" :underline="false">查看</el-link>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  data(){
    return {
      username: "李某某",
      tableData: [{
        date: '2016-05-02',
        name: '	全国英语等级考试一级考试',
        type: '通用类	',
        level: "初级",
        department: "教育部考试中心",
        id: "12345678",
      }, {
        date: '2016-05-04',
        name: '全国计算机信息高新技术考试（国家职业资格三级）',
        type: '	技能类',
        level: "高级",
        department: "人力资源和社会保障部职业技能鉴定中心",
        id: "12345678",
      }, {
        date: '2016-05-01',
        name: '全国计算机等级考试(一级Microsoft Office)',
        type: '通用类	',
        level: "初级",
        department: "教育部考试中心",
        id: "12345678",
      },]
    }
  },
  methods:{
    openImg(){
      let jpg = require("assets/images/证书模板.jpeg");
      this.$alert("<img style='width:100%' src='"+jpg+"' alt='no_img'>", '证书照片', {
        dangerouslyUseHTMLString: true
      });
    }
  },
}
</script>

<style>
  .certificatelist{
    height: 755px;
  }
  .certificatelist>.el-page-header{
    margin-bottom: 30px;
  }
  .el-message-box{
    width: 50%;
  }
</style>