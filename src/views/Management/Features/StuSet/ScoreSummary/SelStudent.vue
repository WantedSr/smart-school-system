<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>学生成绩查询</h1>
    </div>
    <div>
      <el-form :inline="true" :model="form" class="demo-form-inline">
        <el-form-item label="">
          <el-select @change="getClass" size="small" v-model="form.semester" placeholder="查询学期">
            <el-option v-for="(item,i) in semesterData" :key="'semester-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="getClass" size="small" v-model="form.department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'dep-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="getStu" size="small" v-model="form.class" placeholder="查询班级">
            <el-option label="所有班级" v-if="classData.length > 0" value="all"></el-option>
            <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="clearTable" size="small" v-model="form.student" placeholder="查询学生">          
            <el-option label="所有学生" v-if="stuData.length > 0" value="all"></el-option>
            <el-option v-for="(item,i) in stuData" :key="'student-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" @click="onSubmit" type="primary">查询</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="wageTable">
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
          prop="student"
          min-width="100"
          sortable
          label="学生学号">
        </el-table-column>
        <el-table-column
          prop="student_name"
          min-width="100"
          sortable
          label="学生姓名">
        </el-table-column>
        <el-table-column
          prop="course_name"
          min-width="100"
          sortable
          label="科目">
        </el-table-column>
        <el-table-column
          prop="score"
          min-width="100"
          label="分数">
          <template v-slot="scope">
            {{ scope.row.score + ' 分' }}
          </template>
        </el-table-column>
        <el-table-column
          prop="rank"
          min-width="100"
          label="排名">
          <template v-slot="scope">
            {{ scope.row.rank + ' 位' }}
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
        class: "",
        student: "",
      },
      tableData:[],
      showTable: [],

      semesterData: [],
      departmentData: [],
      classData: [],
      stuData: [],

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  created(){
    this.getSemester();
    this.getDepartment();
    this.getClass();
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
    getClass(){
      this.form.class = '';
      this.form.student = '';
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.form.department,
          selobj: {
            'semester': this.form.semester,
            'department': this.form.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
          // console.log(res);
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
    getStu(){
      this.stuData = [];
      this.form.student = '';
      this.loading = true;
      let obj = {
        campus: this.$store.state.userCampus,
        job: "S1",
        department: this.form.department,
      }
      if(this.form.class != 'all'){
        obj['class'] = this.form.class;
      }
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          col: "userid,username,class,school,campus",
          selobj: obj,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.stuData = res;
          // console.log(res);
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
      let obj = {
        semester: this.form.semester,
        department: this.form.department,
      }
      if(this.form.class != 'all'){
        obj['class'] = this.form.class;
      }
      if(this.form.student != 'all'){
        obj['student'] = this.form.student;
      }
      requestAjax({
        type: "post",
        url: "/StuSet/Score.php",
        data:{
          action: "getStu",
          request: JSON.stringify(obj),
        },
        success:res=>{
          res= JSON.parse(res);
          // console.log(res);
          if(res.data.length > 0){
            let data = res.data;
            data.sort((a,b)=>b.addTime - a.addTime);
            this.tableData = data;
            this.setPage(1);
          }
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