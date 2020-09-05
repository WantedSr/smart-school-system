<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>考勤补充登记</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select size="small" v-model="sel.date" placeholder="查询时间">
          <el-option label="早上" value="早上"></el-option>
          <el-option label="中午" value="中午"></el-option>
          <el-option label="下午" value="下午"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getDormitory" size="small" v-model="sel.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getStu" size="small" v-model="sel.dormroom" placeholder="查询宿舍">
          <el-option v-for="(item,i) in dormroomData" :key="'dormitory-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker
          size="small"
          value-format="timestamp"
          v-model="sel.time"
          type="date"
          placeholder="选择日期">
        </el-date-picker>
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
          prop="student_name"
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

    <div class="do" v-if="stuData.length > 0">
      <el-button size="small" @click="onSubmit" type="primary">补登</el-button>
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
        date: "早上",
        department: "",
        dormroom: "",
        time: new Date().setHours(0,0,0,0),
      },

      tableData:[],

      attendanceData: [],
      disciplineData: [],

      departmentData: [],
      dormroomData: [],
      courseData: [],

      sessionData: [],
      stuData: [],

      loading: false,

    }
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    getDormitory(){
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_more_dormroom",
          col: "*",
          buildtype: '学生宿舍',
          campus: this.$store.state.userCampus,
          department: this.sel.department,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.dormroomData = res;
          // console.log(res);
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
    getStu(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Dormitory/stuArrangement.php",
        data:{
          type: "sel_dormroom_name",
          col: "*",
          selobj: {
            dormroom: this.sel.dormroom,
            campus: this.$store.state.userCampus,
            semester: this.$store.state.userSemester,
            department: this.sel.department,
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
    onSubmit(){
      if(this.sel.date == '' || this.sel.dormroom == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
      }else{
        this.loading = true;

        let arr = [];
        $.each(this.stuData, (i, v)=>{ 
          let arr1 = {
            student: v.student,
            dormroom: this.sel.dormroom,
            hour: this.sel.date,
            dormitory: this.$store.state.userId,
            date: this.sel.time,
            semester: this.$store.state.semester,
            attendance: this.stuData[i].attendance,
            discipline: JSON.stringify(this.stuData[i].discipline),
            school: this.$store.state.userSchool,
            campus: this.$store.state.userCampus,
            department: this.sel.department,
            created_user: this.$store.state.userId,
            addTime: new Date().getTime(),
          };
          arr.push(arr1);
        });

        // console.log(arr);

        requestAjax({
          type: 'post',
          url: "/Dormitory/getOption.php",
          data:{
            type: "add_attendance_report",
            arr: arr,
            semester: this.$store.state.semester,
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
            hour: this.sel.date,
            date: this.sel.time,
            dormroom: this.sel.dormroom,
          },
          async: false,
          success:(res)=>{
            this.loading = false;
            res = JSON.parse(res);
            // console.log(res);
            if(res[0]){

              this.$message({
                message: '今日该节次课程考勤已登记，不能重复进行操作！',
                type: 'warning'
              });

            }else{

              let userarr = [];
              let errarr = [];
              $.each(res[1], (i, v)=>{ 
                if(!v){
                  userarr.push(this.stuData[i]['username']);
                  errarr.push(this.stuData[i]['userid']);
                }
              });
              if(userarr.length == 0 && errarr.length == 0){
                this.$message({
                  message: '课堂考勤修改成功！',
                  type: 'success'
                });
              }else{
                this.$message({
                  message: '数据修改有误，请打开 F12->console 查看',
                  type: 'warning'
                });
                console.log(userarr + " 同学课堂考勤有误");
              }

              // 日志写入
              let obj = {
                content: "宿舍考勤补充登记 宿舍："+ this.sel.dormroom + " 时间："+ this.getDate(this.sel.time) + " 时辰" + this.sel.hour ,
                type: "添加记录",
                model: "宿舍巡查模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }

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
    getDepartment(campus){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: campus ? campus : this.$store.state.userCampus,
        },
        async: true,
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
  },
  created(){
    
    this.loading = true;
    requestAjax({
      url: "/Dormitory/getOption.php",
      type: 'get',
      data:{
        type: "sel_option",
        col: "*",
        campus: this.$store.state.userCampus,
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

    // this.getDormitory();
    this.getDepartment();
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
  },
}
</script>

<style>
  .altersel{
    margin-bottom: 20px;
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