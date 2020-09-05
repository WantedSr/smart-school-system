<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>作业登记查询</h1>
    </div>

    <el-form :model="sel" :inline="true" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCourse" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option 
          v-for="(item,i) in classData" 
          :key="'c-'+i" 
          :label="item.class_name" 
          :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getStu" v-model="sel.homework" size="small" placeholder="选择作业">
          <el-option 
          v-for="(item,i) in courseData" 
          :key="'homework-'+i" 
          :label="item.course_name + '-' + item.title" 
          :value="item.id"></el-option>
        </el-select>
      </el-form-item>
    </el-form>

    <div class="register" v-if="choose">
      <el-table
        :data="stuData"
        style="width: 100%"
        >
        <el-table-column
          type="index"
          label="序号"
          width="50">
        </el-table-column>
        <el-table-column
          prop="student_name"
          label="姓名"
          width="100">
        </el-table-column>
        <el-table-column
          prop="do"
          min-width="150"
          label="操作">
          <template v-slot="scope">
            <el-tag :type="scope.row.state == '1' ? 'success' : scope.row.state == '2' ? 'danger' : 'warning'" size="small">{{ scope.row.state == '1' ? '准时交' : scope.row.state == '2' ? '缺交' : '补交' }}</el-tag>
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
    return{
      sel:{
        department: "",
        class: "",
        homework: "",
      },

      departmentData: [],
      classData: [],
      stuData: [],
      courseData: [],
    }
  },
  created(){
    this.getDepartment();
    this.sel.department = this.$store.state.userDepartment;
    this.getClass();
  },
  methods:{
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
      this.sel.class = '';
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : null,
          selobj: {
            'semester': this.sel.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: false,
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
    getStu(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Homework/homework.php",
        data:{
          type: "sel_homework_register",
          col: "*",
          selobj: {
            homework: this.sel.homework,
            class: this.sel.class,
            department: this.sel.department,
            semester: this.$store.state.semester,
            campus: this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.stuData = res;
          console.log(this.stuData);
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
    getCourse(){
      this.loading = true;
      this.sel.homework = '';
      requestAjax({
        url: "/Homework/homework.php",
        type: 'get',
        data:{
          type: "sel_homework_name",
          selobj: {
            'semester': this.$store.state.semester,
            'class': this.sel.class,
            'department': this.sel.department,
            'campus': this.$store.state.userCampus,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.courseData = res;
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
      this.getStu();
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
    }
  }
}
</script>

<style>

</style>