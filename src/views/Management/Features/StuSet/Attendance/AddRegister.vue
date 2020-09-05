<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>补充填报</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getOption" size="small" v-model="sel.type" placeholder="查询类型">
          <el-option label="课堂登记" value="课堂登记"></el-option>
          <el-option label="早段登记" value="早段登记"></el-option>
          <el-option label="课间操登记" value="课间操登记"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-date-picker
          v-model="sel.date"
          @change="getCourse"
          type="date"
          size="small"
          placeholder="选择日期"
          format="yyyy-MM-dd"
          value-format="timestamp">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCourse" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="sel.type == '课堂登记'" label="">
        <el-select @change="getStu" size="small" v-model="sel.course" placeholder="查询课程">
          <el-option v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name + '-' + (courseData.filter(a=>a.session == item.schedule_id).length > 0 && courseData.filter(a=>a.session == item.schedule_id)[0][courseKey] != null ? courseData.filter(a=>a.session == item.schedule_id)[0][courseKey] : '无')" :value="item.schedule_id"></el-option>
        </el-select>
      </el-form-item>
    </el-form>

    <div class="altersel">
      <el-table
      :data="stuData"
      stripe
      size="small"
      ref="multipleTable"
      tooltip-effect="dark"
      @selection-change="handleSelectionChange"
      v-loading="loading"
      element-loading-text="拼命加载中"
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
      border
      style="width: 100%">
        <el-table-column 
          type="index" 
          label="序号">
        </el-table-column>
        <el-table-column
          prop="username"
          label="姓名">
        </el-table-column>
        <el-table-column
          v-for="(item,i) in attendanceData"
          :key="'attendance-'+i"
          prop="attendance"
          :label="item.option_name">
          <template v-slot="scope">
            <el-radio v-model="scope.row.attendance" :label="item.option_id">{{ item.option_name }}</el-radio>
          </template>
        </el-table-column>
        <el-table-column
          v-for="(item,i) in disciplineData"
          :key="'discipline-'+i"
          prop="discipline"
          :label="item.option_name">
          <template v-slot="scope">
            <el-checkbox v-model="scope.row.discipline" :label="item.option_id">{{ item.option_name }}</el-checkbox>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div class="do">
      <el-button size="small" @click="onSubmit" type="primary">登记</el-button>
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
        date: new Date().setHours(0,0,0,0),
        department: this.$store.state.userDepartment,
        class: "",
        course: "",
        type: "课堂登记",
      },
      tableData:[],

      courseData: [],
      departmentData: [],
      classData: [],
      sessionData: [],
      stuData: [],

      loading: false,

      attendanceData: [],
      disciplineData: [],

    }
  },
  methods:{
    onSubmit(){
      if(this.sel.type == '课堂登记' && this.sel.course == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }
      if(this.sel.type == '' || this.sel.date == '' || this.sel.department == '' || this.sel.class == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }

      let type = "";
      if(this.sel.type == '课堂登记'){
        type = "add_attendance_course";
      }else if(this.sel.type == '早段登记'){
        type = "add_attendance_early";
      }else{
        type = "add_attendance_between";
      }

    
      this.loading = true;
      let arr = this.stuData;
      $.each(arr, (i, v)=>{ 
        arr[i]['discipline'] = JSON.stringify(v['discipline']);
      });
      requestAjax({
        type: 'post',
        url: "/StuSet/setAttendance.php",
        data:{
          type: type,
          arr: arr,
          semester: this.$store.state.semester,
          user: this.$store.state.userId,
          campus: this.$store.state.userCampus,
          department: this.$store.state.userDepartment,
          class: this.sel.class,
          session: this.sel.course,
          date: this.sel.date,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);

          
          let userarr = [];
          let errarr = [];
          $.each(res, (i, v)=>{ 
            if(!v){
              userarr.push(this.stuData[i]['username']);
              errarr.push(this.stuData[i]['userid']);
            }
          });
          if(userarr.length == 0 && errarr.length == 0){
            this.$message({
              message: '课堂考勤补录登记成功！',
              type: 'success'
            });
          }else{
            this.$message({
              message: '数据添加有误，请打开 F12->console 查看',
              type: 'warning'
            });
            console.log(userarr + " 同学课堂考勤有误");
          }

          // 日志写入
          let obj = {
            content: "课堂考勤登记 班级："+ this.sel.class + " 时间："+ this.getDate(this.sel.date) + " 节次" + this.sel.course ,
            type: "添加记录",
            model: "课堂考勤补登模块",
            ip: sessionStorage.getItem('ip'),
            user: this.$store.state.userId,
            area: sessionStorage.getItem("area"),
            brower: sysTool.GetCurrentBrowser(),
            addTime: new Date().getTime(),
          }
          let arr = Object.values(obj);
          this.$store.commit("writeLog",arr);

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
    getCourse(){
      if(this.sel.type == '课堂登记'){
        this.sel.course = '';
        this.courseData = [];
        this.loading = true;
  
        requestAjax({
          url: "/courseTable.php",
          type: 'get',
          data:{
            type: "sel_more_course_table",
            col: "*",
            department: this.sel.department,
            time: this.sel.date,
            selobj: {
              'class': this.sel.class,
              'department':this.sel.department,
              'semester': this.$store.state.semester,
            },
          },
          async: false,
          success:(res)=>{
            res = JSON.parse(res);
            this.courseData = res;
            this.courseData.sort((a,b) => a.session - b.session);
            this.loading = false;
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
        this.getSession();
      }else{
        this.getStu();
      }
    },
    getStu(){
      this.stuData = [];
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          col: "userid,username,class,school,campus",
          selobj: {
            class: this.sel.class,
            campus: this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          $.each(res, (i, v)=>{ 
            res[i]['attendance'] = this.attendanceData[0]['option_id'];
            res[i]['discipline'] = [];
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
    getOption(){
      this.loading = true;
      requestAjax({
        url: "/StuSet/getOption.php",
        type: 'get',
        data:{
          type: "sel_option",
          col: "*",
          campus: this.$store.state.userCampus,
          model: this.sel.type,
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
    }
  },
  created(){
    this.getOption();
    this.getDepartment();
    this.getClass();
  },
  computed:{
    getDate(){
      return (time)=>{
        let date = new Date();
        date.setTime(time);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        let d = date.getDate();
        return y + "年" + m + '月' + d + "日";
      }
    },
    courseKey(){
      let date = new Date();
      date.setTime(this.sel.date);
      if(date.getDay() == 1){
        return "mon_course_name";
      }
      else if(date.getDay() == 2){
        return "tue_course_name";
      }
      else if(date.getDay() == 3){
        return "wed_course_name";
      }
      else if(date.getDay() == 4){
        return "thu_course_name";
      }
      else if(date.getDay() == 5){
        return "fri_course_name";
      }
      else{
        return null;
      }
    },
    choose(){
      for(let i in this.sel){
        if(this.sel[i] == '' || this.sel[i] == undefined || this.sel[i] == null){
          return false;
        }
      }
      return true;
    },
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