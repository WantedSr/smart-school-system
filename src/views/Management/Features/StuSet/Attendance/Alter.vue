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
        <el-select size="small" v-model="sel.course" placeholder="查询课程">
          <el-option v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name + '-' + (courseData.filter(a=>a.session == item.schedule_id).length > 0 && courseData.filter(a=>a.session == item.schedule_id)[0][courseKey] != null ? courseData.filter(a=>a.session == item.schedule_id)[0][courseKey] : '无')" :value="item.schedule_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button size="small" @click="onSubmit" type="primary">查询</el-button>
        <el-button size="small" @click="onDel" type="danger">删除</el-button>
      </el-form-item>
    </el-form>

    <div class="altersel">
      <el-table 
        :data="tableData"
        border
        size="small"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="100%">
        <el-table-column label="" type="selection" width="50"></el-table-column>
        <el-table-column label="班级" sortable prop="class_name" width="100"></el-table-column>
        <el-table-column label="学生" sortable prop="student_name" width="90"></el-table-column>
        <el-table-column label="出勤" sortable prop="attendance" min-width="120"></el-table-column>
        <el-table-column label="违纪" sortable prop="discipline" min-width="120">
          <template v-slot="scope">
            {{ scope.row.discipline.length ? scope.row.discipline.join('-') : '无' }}
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
      
      multipleTable: [],

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
        type = "get_course_situation";
      }else if(this.sel.type == '早段登记'){
        type = "get_early_situation";
      }else{
        type = "get_between_situation";
      }

    
      this.loading = true;
      let arr = this.stuData;
      $.each(arr, (i, v)=>{ 
        arr[i]['discipline'] = JSON.stringify(v['discipline']);
      });
      requestAjax({
        type: 'post',
        url: "/StuSet/AlterAttendance.php",
        data:{
          type: type,
          semester: this.$store.state.semester,
          campus: this.$store.state.userCampus,
          department: this.sel.department,
          class: this.sel.class,
          session: this.sel.course,
          date: this.sel.date,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);

          this.tableData = res;

          
          let userarr = [];
          let errarr = [];
          $.each(res, (i, v)=>{ 
            if(!v){
              userarr.push(this.stuData[i]['username']);
              errarr.push(this.stuData[i]['userid']);
            }
          });
          if(userarr.length == 0 && errarr.length == 0){
            // this.$message({
            //   message: '课堂考勤补录登记成功！',
            //   type: 'success'
            // });
          }else{
            // this.$message({
            //   message: '数据添加有误，请打开 F12->console 查看',
            //   type: 'warning'
            // });
            // console.log(userarr + " 同学课堂考勤有误");
          }

          // 日志写入
          // let obj = {
          //   content: "课堂考勤登记 班级："+ this.sel.class + " 时间："+ this.getDate(this.sel.date) + " 节次" + this.sel.course ,
          //   type: "添加记录",
          //   model: "课堂考勤补登模块",
          //   ip: sessionStorage.getItem('ip'),
          //   user: this.$store.state.userId,
          //   area: sessionStorage.getItem("area"),
          //   brower: sysTool.GetCurrentBrowser(),
          //   addTime: new Date().getTime(),
          // }
          // let arr = Object.values(obj);
          // this.$store.commit("writeLog",arr);

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
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onDel(){
      let arr = [];
      $.each(this.multipleTable, function (i, v) { 
        arr.push(v.id);
      });
      if(arr.length != 0){
        this.$confirm('是否确定删除该记录？', '确认删除？', {
          distinguishCancelAndClose: true,
          confirmButtonText: '确定',
          cancelButtonText: '放弃'
        })
        .then(()=>{

          let type = "";
          if(this.sel.type == '课堂登记'){
            type = "del_course";
          }else if(this.sel.type == '早段登记'){
            type = "del_early";
          }else{
            type = "del_between";
          }

          requestAjax({
            url: "/StuSet/AlterAttendance.php",
            type: 'post',
            data:{
              type: type,
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除"+this.sel.type + '记录 '+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "班级模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.onSubmit();
              }else{
                this.$message.error('删除失败，请稍后再试！');
              }
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            }
          })
        })
        .catch(action=>{
          this.$message('取消操作');
        })
      }else{
        this.$message.error('请选择选项后在进行操作！');
      }
    },
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