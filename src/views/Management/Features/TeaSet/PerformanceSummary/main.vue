<template>
  <div class="subpage">
    
    <div class="pagehead">
      <h1>教师绩效查询</h1>
    </div>

    <div>
      <el-form :inline="true" :model="form" class="demo-form-inline">
        <el-form-item label="">
          <el-select size="small" v-model="form.semester" placeholder="查询学期">
            <el-option v-for="(item,i) in semesterData" :key="'semester-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="getTea" size="small" v-model="form.department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'dep-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-date-picker
            v-model="form.month"
            size="small"
            value-format="timestamp"
            type="month"
            placeholder="选择月份">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="clearTable" size="small" v-model="form.teacher" placeholder="查询老师">
            <el-option v-for="(item,i) in teaData" :key="'student-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" @click="onSubmit" type="primary">查询</el-button>
        </el-form-item>
      </el-form>
    </div>

    <div class="wageTable box">
      <el-table
        border 
        :data="showTable"
        size="mini"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        stripe
        style="width: 100%">
        <el-table-column min-width="30" align="center" label="序号" type="index"></el-table-column>
        <el-table-column
          prop="semester"
          min-width="110"
          sortable
          label="学期">
        </el-table-column>
        <el-table-column
          prop="teacher"
          min-width="100"
          sortable
          label="教师编号">
        </el-table-column>
        <el-table-column
          prop="teacher_name"
          min-width="100"
          sortable
          label="教师">
        </el-table-column>
        <el-table-column
          prop="performance"
          min-width="100"
          sortable
          label="事务">
        </el-table-column>
        <el-table-column
          prop="num"
          min-width="100"
          label="数量">
          <template v-slot="scope">
            {{ scope.row.num + ' 次' }}
          </template>
        </el-table-column>
        <el-table-column
          prop="addTime"
          min-width="100"
          label="导入时间">
          <template v-slot="scope">
            {{ getDate(scope.row.addTime) }}
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        month: "",
        teacher: "",
      },
      tableData:[],
      showTable: [],

      semesterData: [],
      departmentData: [],
      teaData: [],

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  created(){
    this.getSemester();
    this.getDepartment();
    this.getTea();
  },
  methods:{
    setPage(page){
      this.page = page;
      let start = ((this.page-1)*this.total);
      let end;
      if(start + this.total >= this.tableData.length){
        end = this.tableData.length;
      }else{
        end = start + this.total;
      }
      this.showTable = [];
      for(let i = start;i < end ; i++){
        this.showTable.push(this.tableData[i]);
      }
    },
    getSemester(){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterData = res;
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
    getDepartment(campus){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: campus ? campus : this.$store.state.userCampus,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.departmentData = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getTea(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        async: false,
        data:{
          type: "sel_tea",
          col: "id,userid,username",
          selobj: {
            'department':this.form.department,
            'type': '1',
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.teaData = res;
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
    onSubmit(){
      for(let i in this.form){
        if(this.form[i] == ''){
          this.$message({
            type: 'warning',
            message: "请选择完所有选项",
          })
          return false;
        }
      }
      this.loading = true;
      this.tableData = [];
      this.showTable = [];
      requestAjax({
        type: "post",
        url: "/Teamanagement/Performance/Performance.php",
        data:{
          action: "getTea",
          request: JSON.stringify(this.form),
          campus: this.$store.state.userCampus,
        },
        async: true,
        success:res=>{
          this.loading = false;
          res= JSON.parse(res);
          // console.log(res);
          if(res.data.length > 0){
            let data = res.data;
            data.sort((a,b)=>b.addTime - a.addTime);
            this.tableData = data;
            this.setPage(1);
          }
        },
        error:err=>{
          this.loading = false;
          console.error(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
  },
  computed:{
    sum(){
      return this.tableData.length;
    },
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
    },
  },
  components: {
    Limit,
  }
}
</script>


<style scoped>
  .wageTable{
    margin-bottom: 12px;
  }
</style>