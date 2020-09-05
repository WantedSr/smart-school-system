<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>补充登记</h1>
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
        <el-select v-model="sel.homework" size="small" placeholder="选择作业">
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
          prop="userid"
          label="学号"
          width="110">
        </el-table-column>
        <el-table-column
          prop="username"
          label="姓名"
          width="100">
        </el-table-column>
        <el-table-column
          prop="do"
          min-width="150"
          label="操作">
          <template v-slot="scope">
            <el-radio-group v-model="scope.row.state">
              <el-radio label="1">准时交</el-radio>
              <el-radio label="2">缺交</el-radio>
              <el-radio label="3">补交</el-radio>
            </el-radio-group>
          </template>
        </el-table-column>
      </el-table>
      <div>
        <br>
        <el-button size="small" type="primary" @click="onAdd">登记</el-button>
      </div>
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
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          col: "userid,username",
          selobj: {
            class: this.sel.class,
            department: this.sel.department,
            campus: this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          $.each(res, (i, v)=>{ 
            res[i]['state'] = '1';
            // res[i]['discipline'] = [];
          });
          this.stuData = res;
          // console.log(this.stuData);
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
    onAdd(){
      this.loading = true;
      requestAjax({
        type: "post",
        url: "/Homework/homework.php",
        data: {
          type: "add_register_homework",
          arr: this.stuData,
          campus: this.$store.state.userCampus,
          school: this.$store.state.userSchool,
          semester: this.$store.state.semester,
          department: this.sel.department,
          class: this.sel.class,
          homework: this.sel.homework,
          user: this.$store.state.userId,
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          let errarr = [];
          for(let i in res){
            if(!res[i]){
              errarr.push(this.stuData[i]['username']);
            }
          }
          if(errarr.length <= 0){
            this.$message({
              message: '作业补充登记成功',
              type: 'success'
            });

            // 日志写入
            let obj = {
              content: "作业补充登记信息 作业："+ this.sel.homework + " 班级：" + this.sel.class + " 日期：" + new Date().setHours(0,0,0,0),
              type: "添加记录",
              model: "作业模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);

            // this.$router.go(-1);
            location.reload();
            }else{
              this.$message({
                message: '部分数据添加有误,请打开F12-console查看！',
                type: 'warning'
              });
              console.log(errarr.join('-')+' 登记有误');
            }
          
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
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
    }
  }
}
</script>

<style>

</style>