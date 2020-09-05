<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>课堂考勤统计</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCourseRegister" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item> 
      <el-form-item label="">
        <el-date-picker
          @change="getCourseRegister"
          size="small"
          v-model="sel.date"
          type="date"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
    </el-form>

    <div class="classTable" v-if="choose">
      <el-table 
        :data="courseSituationData"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        border
        size="small"
        style="100%">
        <el-table-column label="班级" sortable prop="class_name" width="100"></el-table-column>
        <el-table-column label="学生" sortable prop="student_name" width="90"></el-table-column>
        <el-table-column v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name" sortable :prop="item.schedule_id" min-width="120">
          <template v-slot="scope">
            {{ courseSituationData.filter(v=>v.session == item.schedule_id && v.student == scope.row.student).length > 0 ? courseSituationData.filter(v=>v.session == item.schedule_id && v.student == scope.row.student)[0]['attendance'] : '无' }}
            <br>
            {{ courseSituationData.filter(v=>v.session == item.schedule_id && v.student == scope.row.student).length > 0 ? courseSituationData.filter(v=>v.session == item.schedule_id && v.student == scope.row.student)[0]['discipline'].join('-') : '无' }}
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      sel:{
        department: "",
        class: "",
        date: new Date().setHours(0,0,0,0),
      },
      tableData:[],

      sessionData: [],

      departmentData: [],
      classData: [],

      courseSituationData: [],
    }
  },
  created(){
    this.getSession();
    this.getDepartment();
    this.getClass();
  },
  methods:{
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
        async: true,
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
    getCourseRegister(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuSet/OverView.php",
        data:{
          type: "get_course_situation",
          col: "id,attendance,class,date,discipline,id,session,student",
          selobj:{
            semester: this.$store.state.semester,
            campus: this.$store.state.userCampus,
            department: this.sel.department,
            class: this.sel.class,
            date: this.sel.date,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.courseSituationData = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        },
      })
    },
    getClass(){
      this.sel.class = '';
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
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
  },
  computed:{
    choose(){
      for(let i in this.sel){
        if(this.sel[i] == '' || this.sel[i] == undefined || this.sel[i] == null){
          return false;
        }
      }
      return true;
    },
  }
}
</script>

<style>

</style>