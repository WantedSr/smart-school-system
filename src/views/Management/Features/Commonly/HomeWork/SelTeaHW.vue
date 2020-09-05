<template>
  <div class="subpage">
    
    <div class="pagehead">
      <h1>学生查询</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getCourse" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getTeacher" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="sel.teacher" placeholder="选择教师">
          <el-option v-if="teacherData.length > 0" label="所有教师" value="all"></el-option>
          <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="sel.course" placeholder="课程">
          <el-option v-if="classCourse.length > 0" label="所有课程" value="all"></el-option>
          <el-option v-for="(item,i) in classCourse" :key="'c-'+i+'-'" :label="item.course_name" :value="item.course"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="sellayout">
      <div>
        <el-table
          :data="tableData"
          style="width: 100%"
          stripe
          ref="multipleTable"
          tooltip-effect="dark"
          @selection-change="handleSelectionChange"
          size="small"
          v-loading="loading"
          element-loading-text="拼命加载中"
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(0, 0, 0, 0.8)"
          >
          <el-table-column
            prop="department"
            label="部门"
            sortable
            min-width="70">
          </el-table-column>
          <el-table-column
            prop="teacher_name"
            label="教师"
            sortable
            min-width="90">
          </el-table-column>
          <el-table-column
            prop="class_name"
            label="班级"
            sortable
            min-width="90">
          </el-table-column>
          <el-table-column
            prop="course_name"
            label="学科"
            sortable
            min-width="100">
          </el-table-column>
          <el-table-column
            prop="title"
            label="作业"
            sortable
            min-width="80">
          </el-table-column>
          <el-table-column
            label="时间"
            prop="addTime"
            min-width="90"
            sortable>
            <template v-slot="scope">
              {{ getDate(scope.row.addTime) }}
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>

    <div class="see">
      <el-dialog title="收货地址" :visible.sync="dialogTableVisible">
        <el-table :data="stuData">
          <el-table-column prop="student_name" label="姓名" min-width="100"></el-table-column>
          <el-table-column prop="state" label="提交状态" min-width="100">
            <template v-slot="scope">
              {{ scope.row.state == '1' ? '已交' : scope.row.state == '3' ? '补交' : '未登记' }}
            </template>
          </el-table-column>
        </el-table>
      </el-dialog>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>

  </div>
</template>

<script>
import Limit from "../../Limit/main";
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        teacher: "",
        course: "",
      },

      tableData:[],

      departmentData: [],
      courseData: [],
      semesterData: [],
      homeworkData: [],
      classCourse: [],

      teacherData: [],

      stuData: [],
      dialogTableVisible: false,

      multipleTable: [],
      
      sum: 0,
      page: 1,
      total: 50,
      loading: false,
      n: this.$route.query.n,

    }
  },
  components:{
    Limit,
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
  created(){
    this.getDepartment();
    this.getSemester();
    this.getTeacher();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.onSubmit();
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onSubmit(){
      if(this.sel.semester == '' || this.sel.department == '' || this.sel.teacher == '' || this.sel.course == ''){
        this.$message.info("请选择完所有选项")
        return false;
      }
      this.getData();
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
    getData(){

      let obj = {
        semester: this.sel.semester,
        campus: this.$store.state.userCampus,
      };

      if(this.sel.teacher != 'all'){
        if(this.sel.course != 'all'){
          obj['created_user'] = this.sel.teacher;
          obj['course'] = this.sel.course;
        }else{
          obj['created_user'] = this.sel.teacher
        }
      }else if(this.sel.department != 'all'){
        if(this.sel.course != 'all'){
          obj['department'] = this.sel.department;
          obj['course'] = this.sel.course;
        }else{
          obj['department'] = this.sel.department;
        }
      }else if(this.sel.course != 'all'){
        obj['course'] = this.sel.course;
      }

      this.loading = true;
      requestAjax({
        url: "/Homework/homework.php",
        type: 'get',
        data: {
          col: "*",
          type: "sel_limit_homework_teacher",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: obj,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          this.tableData = res[0];
          this.sum = parseInt(res[1]);
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
    onSee(id,cls){
      this.dialogTableVisible = true;
      requestAjax({
        type: 'get',
        url: "/Homework/homework.php",
        data:{
          type: "sel_homework_register",
          selobj:{
            homework: id,
            class: cls,
            campus: this.$store.state.userCampus,
            // state: '1',
            // state: '3',
          },
        },
        success:(res)=>{
          res = JSON.parse(res);
          res = res.filter(item=>item.state != 2);
          this.stuData = res;
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
    getTeacher(){
      this.getCourse();
      this.sel.teacher = '';
      let obj = {campus: this.$store.state.userCampus};
      if(this.sel.department != 'all'){
        obj['department'] = this.sel.department;
      }
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "*",
          selobj: obj,
        },
        success:(res)=>{
          this.loading = false;
          // console.log(res);
          res = JSON.parse(res);
          this.teacherData = res;
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
    getCourse(){
      this.loading = true;
      this.sel.course = '';
      requestAjax({
        url: "/teach/ClassCourse.php",
        type: 'get',
        data:{
          type: "sel_more_course",
          department: this.sel.department,
          semester: this.sel.semester,
          campus: this.$store.state.userCampus,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classCourse = res;
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
  }
}
</script>

<style>
  .sellayout{
    margin-bottom: 12px;
  }
</style>