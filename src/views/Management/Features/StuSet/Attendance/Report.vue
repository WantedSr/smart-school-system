<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>学生考勤统计报表</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="setValue" size="small" v-model="sel.where" placeholder="查询范围">
          <el-option label="日" value="day"></el-option>
          <el-option label="月" value="month"></el-option>
          <el-option label="学期" value="semester"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="sel.where == 'semester'" label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'day'">
        <el-date-picker
          size="small"
          v-model="sel.time"
          type="date"
          format="yyyy-MM-dd"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'month'">
        <el-date-picker
          size="small"
          v-model="sel.month"
          format="yyyy-MM"
          value-format="timestamp"
          type="month"
          placeholder="选择月份">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'day'">
        <el-select @change="getStu" size="small" v-model="sel.session" placeholder="节次">
          <el-option v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name" :value="item.schedule_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="altersel">
      <el-table
        :data="classData"
        stripe
        size="small"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          prop="class_name"
          label="班级">
        </el-table-column>
        <el-table-column
          v-if="sel.where == 'day'"
          prop="class_num"
          label="人数">
        </el-table-column>
        <el-table-column
          v-for="(item,i) in attendanceData"
          :key="'attendance-'+i"
          prop="count(attendance)"
          :label="item.option_name">
          <template v-slot="scope">
            <!-- {{ tableData[item.option_id].filter(v=>v.scope.row.class).length }} -->
            {{ tableData[item.option_id] != undefined ? tableData[item.option_id].filter(v=>v.class == scope.row.class).length > 0 ? tableData[item.option_id].filter(v=>v.class == scope.row.class)[0]['count(attendance)'] : 0 : "" }}
          </template>
        </el-table-column>
        <el-table-column
          v-for="(item,i) in disciplineData"
          :key="'discipline-'+i"
          prop="discipline"
          :label="item.option_name">
          <template v-slot="scope">
            {{ tableData[item.option_id] != undefined ? tableData[item.option_id].filter(v=>v.class == scope.row.class).length > 0 ? tableData[item.option_id].filter(v=>v.class == scope.row.class)[0]['count(attendance)'] : 0 : "" }}
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
    return{
      sel:{
        where: "day",
        semester: "",
        time: new Date().setHours(0,0,0,0),
        month: "",
        department: "",
        grade: "",
        class: "",
        session: "",
      },

      semesterData: [],
      departmentData: [],
      sessionData: [],
      classData: [],

      attendanceData: [],
      disciplineData: [],

      // sum: 0,
      // page: 1,
      // total: 15,
      loading: false,
      n: this.$route.query.n,

      tableData:{},

      choose: false,
    }
  },
  methods:{
    onSubmit(){
      this.choose = true;
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
    getClass(){
      let obj = {};
      if(this.sel.department != 'all'){
        obj = {
          'semester': this.sel.semester == '' ? this.$store.state.semester : this.sel.semester,
          'department': this.sel.department,
          'status': "1",
        }
      }else{
        obj = {
          'semester': this.sel.semester == '' ? this.$store.state.semester : this.sel.semester,
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
        async: false,
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
    onSubmit(){
      this.getData();
      this.getClass();
    },
    getSession(){
      this.loading = true;
      requestAjax({
        url: "/base/schedule.php",
        type: 'get',
        data:{
          type: "sel_schedule",
          selobj: {
            'campus':this.$store.state.userCampus,
            'schedule_type': "teach",
            'status': '1',
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          res.sort((a,b)=> a.schedule_order - b.schedule_order);
          this.sessionData = res;
          // console.log(res);
          this.loading = false;
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
    getData(){


      if(this.sel.department == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'day' && this.sel.time == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'day' && this.sel.session == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'month' && this.sel.month == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'semester' && this.sel.semester == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }
      
      let obj = {};
      if(this.sel.where == 'day'){
        if(this.sel.department != 'all'){
          obj = {
            seltype: 'day',
            department: this.sel.department,
            date: this.sel.time,
            session: this.sel.session,
          }
        }else{
          obj = {
            seltype: 'day',
            campus: this.$store.state.userCampus,
            date: this.sel.time,
            session: this.sel.session,
          }
        }
      }else if(this.sel.where == 'month'){
        if(this.sel.department != 'all'){
          obj = {
            seltype: 'month',
            department: this.sel.department,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000000,
          }
        }else{
          obj = {
            seltype: 'month',
            campus: this.$store.state.userCampus,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000000,
          }
        }
      }else if(this.sel.where == 'semester'){
        if(this.sel.department != 'all'){
          obj = {
            seltype: 'semester',
            department: this.sel.department,
            semester: this.sel.semester,
          }
        }else{
          obj = {
            seltype: 'semester',
            campus: this.$store.state.userCampus,
            semester: this.sel.semester,
          }
        }
      }
      obj['col'] = "*";
      obj['campus'] = this.$store.state.userCampus;
      obj['type'] = 'get_course_num';

      this.loading = true;
      requestAjax({
        url: "/StuSet/OverView.php",
        type: 'get',
        data: obj,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          let arr = {};
          for(let i in res){
            arr[i] = res[i];
            if(arr[i].length > 0){
              for(let r=0;r<arr[i].length;r++){
                arr[i][r]['discipline'] = JSON.parse(arr[i][r]['discipline']);
              }
            }
          }
          this.tableData = arr;
          // console.log(res);
          // console.log(arr);
          // console.log(obj);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
    },
    getDataSum(){
      if(this.sel.department == '' || this.sel.class == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'day' && this.sel.time == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'month' && this.sel.month == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'semester' && this.sel.semester == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }
      let obj = {};
      if(this.sel.where == 'day'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'day',
            selobj: {
              class: this.sel.class,
              date: this.sel.time,
            }
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'day',
            selobj: {
              department: this.sel.department,
              date: this.sel.time,
            },
          }
        }else{
          obj = {
            seltype: 'day',
            selobj: {
              campus: this.$store.state.userCampus,
              date: this.sel.time,
            },
          }
        }
      }else if(this.sel.where == 'month'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'month',
            class: this.sel.class,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000,
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'month',
            department: this.sel.department,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000,
          }
        }else{
          obj = {
            seltype: 'month',
            campus: this.$store.state.userCampus,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000,
          }
        }
      }else if(this.sel.where == 'semester'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'semester',
            selobj: {
              class: this.sel.class,
              semester: this.sel.semester,
            },
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'semester',
            selobj: {
              department: this.sel.department,
              semester: this.sel.semester,
            },
          }
        }else{
          obj = {
            seltype: 'semester',
            selobj: {
              campus: this.$store.state.userCampus,
              semester: this.sel.semester,
            },
          }
        }
      }
      obj['col'] = "COUNT(*)";
      obj['start'] = (this.page-1)*this.total;
      obj['num'] = this.total;
      obj['type'] = 'sel_alter';

      this.loading = true;
      requestAjax({
        url: "/teach/AlterSel.php",
        type: 'get',
        data: obj,
        success:(res)=>{
          this.loading = false;
          // console.log(res);
          res = JSON.parse(res)[0]['COUNT(*)'];
          this.sum = parseInt(res);
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
    getOption(){
      this.loading = true;
      requestAjax({
        url: "/StuSet/getOption.php",
        type: 'get',
        data:{
          type: "sel_option",
          col: "*",
          campus: this.$store.state.userCampus,
          model: "课堂登记",
        },
        success:(res)=>{

          this.loading = false;
          res = JSON.parse(res);
          this.attendanceData = res[0];
          this.attendanceData.sort((a,b)=> a.id - b.id);
          this.disciplineData = res[1];
          this.disciplineData.sort((a,b)=> a.id - b.id);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
    },
    setValue(){
      this.sel.time = '';
      this.sel.month = '';
      this.sel.semester = '';
    }
  },
  created(){
    this.getOption();
    this.getSemester();
    this.getDepartment();
    this.getSession();
    // this.sel.class = 'all';
  },
  computed:{
  },
}
</script>

<style>
  .altersel{
    margin-bottom: 60px;
  }
  .do{
    margin-bottom: 20px;
  }
  .pagination{
    text-align: center;
  }
  .pagination .el-pagination{
    display: inline-block;
  }
</style>