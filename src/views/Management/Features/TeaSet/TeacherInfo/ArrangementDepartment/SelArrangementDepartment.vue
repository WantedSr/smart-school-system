<template>
  <div class="selTeacherInfo">
    <div class="pagehead">
      <h1>教师所在部门</h1>
    </div>
    <div>
      <el-row>
        <el-col :lg="18" :md="18" :sm="16" :xs="12">  
          <el-form :inline="true">  
            <el-form-item label="">
              <el-select size="small" v-model="department" placeholder="选择部门">
                <el-option v-if="depData.length > 0" label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in depData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-button type="success" size="small" @click="onSel">查询</el-button>
              <el-button type="primary" size="small" @click="onUpa">设置</el-button>
              <el-button type="danger" size="small" @click="onDel">删除</el-button>
            </el-form-item>
          </el-form> 
        </el-col>
        <el-col :lg="6" :md="6" :sm="8" :xs="12">
          <el-input
            placeholder="输入关键字搜索"
            size="small"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="teaInfoTable box">
      <el-table
        :data="tableData.filter(data => !search || data.teacher_name.toLowerCase().includes(search.toLowerCase()) || data.department.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        size="mini"
        border
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          type="selection">
        </el-table-column>
        <el-table-column
          prop="username"
          align="center"
          label="教师"
          sortable>
        </el-table-column>
        <el-table-column
          prop="department"
          align="center"
          sortable
          label="所在部门">
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import Limit from "../../../Limit/main";
export default {
  data(){
    return{
      tableData:[],
      n: this.$route.query.n,
      search: "",
      multipleTable: [],
      sum: 0,
      page: 1,
      total: 15,
      loading: false,

      department: "all",
      dialogVisble: false,
    }
  },
  computed:{
    getDate(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        m = String(m).length == 1 ? '0'+m : m;
        let d = date.getDate();
        d = String(d).length == 1 ? '0'+d : d;
        return y+"-"+m+"-"+d;
      }
    }
  },
  props:{
    depData:{
      type: Array,
      require: true,
    }
  },
  created(){
    // this.getData();
    // this.getDataSum();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(){
      this.search = '';
      let start = parseInt(this.page - 1) * this.total;
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/ArrangementDepartment.php",
        data:{
          type: "sel_limit_tea",
          col: "userid,username,department",
          start: start,
          num: this.total,
          department: this.department,
          campus: this.$store.state.userCampus,
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.tableData = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getDataSum(){      
      let selobj = this.department == 'all' ? {'campus':this.$store.state.userCampus,} : {'department': this.department,}
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/ArrangementDepartment.php",
        data:{
          type: "sel_tea",
          col: "COUNT(*)",
          selobj: selobj,
        },
        success:(res)=>{
          res = JSON.parse(res);
          if(res.length <= 0){
            this.sum = 0;return;
          }
          res = res[0]["COUNT(*)"];
          // this.loading = false;
          this.sum = parseInt(res);
        },
        error:(err)=>{
          // this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    onSel(){
      this.getData();
      this.getDataSum();
    },
    onUpa(){
      this.$router.push({
        query:{
          n: this.n,
          type: "upa",
        }
      });
    },
    seeCertificate(id){
      this.src = id;
      this.dialogVisble = true;
    },
    handleClose(done) {
      this.dialogVisble = false;  
    }
  },
  components:{
    Limit,
  }
}
</script>

<style scope>
  .error-image-slot{
    width: 100%;
    /* min-height: 300px; */
    text-align: center;
  }
  .el-dialog--center .el-dialog__body{
    text-align: center;
    /* line-height: 300px; */
  }
</style>