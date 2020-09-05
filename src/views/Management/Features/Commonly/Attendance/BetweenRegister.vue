<template>
  <div class="subpage" 
    v-loading="loading"
    element-loading-text="拼命加载中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">
    <div class="pagehead">
      <h1>课间操登记{{ getDate(time) }}</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getStu" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
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
      <div class="do" style="margin-top: 12px">
        <el-button size="small" type="primary" @click="onSubmit">登记</el-button>
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
        class: '',
      },

      time: new Date().setHours(0,0,0,0),

      tableData:[],
      choose: false,

      classData: [],
      stuData: [],
      loading: false,
    }
  },
  created(){


    this.loading = true;
    requestAjax({
      url: "/StuSet/getOption.php",
      type: 'get',
      data:{
        type: "sel_option",
        col: "*",
        campus: this.$store.state.userCampus,
        model: "课间操登记",
      },
      success:(res)=>{

        this.loading = false;
        res = JSON.parse(res);
        this.attendanceData = res[0].length ? res[0] : [];
        this.attendanceData.sort((a,b)=> a.id - b.id);
        this.disciplineData = res[1].length ? res[1] : [];
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

    this.getClass();
  },
  methods:{
    onSubmit(){
      if(this.sel.class == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
      }else{
        this.loading = true;
        let arr = this.stuData;
        $.each(arr, (i, v)=>{ 
          arr[i]['discipline'] = JSON.stringify(v['discipline']);
        });
        requestAjax({
          type: 'post',
          url: "/StuSet/getOption.php",
          data:{
            type: "add_attendance_between",
            arr: arr,
            semester: this.$store.state.semester,
            user: this.$store.state.userId,
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
            class: this.sel.class,
            date: this.time,
          },
          async: false,
          success:(res)=>{
            this.loading = false;
            res = JSON.parse(res);
            // console.log(res);
            if(res[0]){

              this.$message({
                message: '今日课间操考勤已登记，不能重复进行操作！',
                type: 'warning'
              });

            }else if(res[1]){

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
                  message: '课间操考勤登记成功！',
                  type: 'success'
                });
              }else{
                this.$message({
                  message: '数据添加有误，请打开 F12->console 查看',
                  type: 'warning'
                });
                console.log(userarr + " 同学课间操考勤有误");
              }

              // 日志写入
              let obj = {
                content: "课间操考勤登记 班级："+ this.sel.class + " 时间："+ this.getDate(this.time),
                type: "添加记录",
                model: "课间操考勤模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }else{
              this.$message.error("学生健康登记失败！");
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
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : null,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : null,
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
    getStu(){
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
      if(new Date().getDay() == 1){
        return "mon_course_name";
      }
      else if(new Date().getDay() == 2){
        return "tue_course_name";
      }
      else if(new Date().getDay() == 3){
        return "wed_course_name";
      }
      else if(new Date().getDay() == 4){
        return "thu_course_name";
      }
      else if(new Date().getDay() == 5){
        return "fri_course_name";
      }
      else{
        return null;
      }
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