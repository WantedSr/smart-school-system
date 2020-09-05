<template>
  <div class="subpage">

    <div class="pagehead">
      <h1>月全勤查询</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker
          @change="getNum"
          v-model="sel.month"
          format="yyyy-MM"
          value-format="timestamp"
          type="month"
          size="small"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
    </el-form>

    <div class="classTable" v-if="choose">
      <el-table 
        :data="classData"
        stripe
        size="small"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="100%">
        <el-table-column sortable width="110" label="班级" prop="class_name"></el-table-column>
        <el-table-column sortable width="120" label="总人数" prop="class_num"></el-table-column>
        <el-table-column sortable width="110" label="全勤人数" prop="morenum">
          <template v-slot="scope">
            {{ tableData[scope.row.class] != undefined && tableData[scope.row.class].length > 0 ? tableData[scope.row.class].length : '0' }}
          </template>
        </el-table-column>
        <el-table-column sortable min-width="200" label="名单" prop="person">
          <template v-slot="scope">
            {{ tableData[scope.row.class] != undefined && tableData[scope.row.class].length > 0 ? tableData[scope.row.class].join('-') : '无' }}
          </template>
        </el-table-column>
      </el-table>
    </div>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {

      sel:{
        semester: "",
        department: "",
        month: "",
      },
      
      tableData:[],

      semesterData: [],
      departmentData: [],
      classData: [],

      loading: false,
    }
  },
  created(){
    this.getSemester();
    this.getDepartment();
  },
  methods:{
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
    getDepartment(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
    getClass(v){
      if(this.sel.semester == '' || this.sel.department == '') return false;
      let obj = {};
      if(this.sel.department != 'all' && this.sel.department != ''){
        obj = {
          // 'semester': this.sel.semester,
          'semester': this.$store.state.semester,
          'department': this.sel.department,
          'status': "1",
        }
      }else{
        obj = {
          // 'semester': this.sel.semester,
          'semester': this.$store.state.semester,
          'campus': this.$store.state.userCampus,
          'status': "1",
        }
      }
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          selobj: obj
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

      this.getNum();
    },
    getNum(){

      for(let i in this.sel){
        if(this.sel[i] == '' || this.sel[i] == undefined || this.sel[i] == null){
          return false;
        }
      }

      this.loading = true;

      let obj = {
        type: "sel_attendance_good",
        semester: this.sel.semester,
        month: this.sel.month,
      };
      if(this.sel.department == 'all'){
        obj['campus'] = this.$store.state.userCampus;
      }else{
        obj['department'] = this.sel.department;
      };
       
      requestAjax({
        type: "get",
        url: "/StuSet/OverView.php",
        data: obj,
        success:(res)=>{
          res = JSON.parse(res);
          this.loading = false;
          this.tableData = res;
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
    }
  },
  computed:{
    choose(){
      for(let i in this.sel){
        if(this.sel[i] == '' || this.sel[i] == undefined || this.sel[i] == null){
          return false;
        }
      }
      return true;
    }
  }
}
</script>

<style>
</style>