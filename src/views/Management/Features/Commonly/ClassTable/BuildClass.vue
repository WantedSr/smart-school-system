<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>课室课表</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getCrouse" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCrouse" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker
          v-model="sel.time"
          @change="getCourse"
          size="small"
          type="date"
          format="yyyy-MM-dd"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
    </el-form>
    <div class="buildtable" v-if="choose && this.roomData.length > 0">
        <!-- :span-method="objectSpanMethod"  -->
      <el-table 
        :data="sessionData" 
        border  
        style="100%">
        <el-table-column fixed align="center" width='100' label="节次" prop="schedule_name"></el-table-column>
        <el-table-column v-for="(item,i) in roomData" :key="'r-'+i" min-width='120' :label="item.room_name">
          <template v-slot="scope">

            <!-- <span class="course">{{ classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey]).length }}</span> -->
            
            <span class="course">{{ classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey]).length > 0
              && classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][courseKey] ? 
              classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][courseKey] :  
              '-' }}</span>
              <br>
            <span class="build">{{ classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey]).length > 0
              && classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][buildKey] ? 
              classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][buildKey] + '-' + 
              classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][roomNameKey] :  
              '-' }}</span>
              <br>
            <span class="teacher">{{ classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey]).length > 0
              && classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][teaKey] ? 
              classCourse.filter(item2 => item2.session == scope.row.schedule_id && item.room_id == item2[roomKey])[0][teaKey] :  
              '-' }}</span>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      sel:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        time: new Date().setHours(0,0,0,0),
      },
      tableData:[],

      roomData: [],

      classCourse: [],

      departmentData: [],
      semesterData: [],
      sessionData: [],
    }
  },
  created(){
    this.getSemester();
    this.getDepartment();
    this.getSession();
    this.getRoom();

    this.getCourse();
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
    dateDay(){
      let time = new Date();
      time.setTime(this.sel.time);
      return time.getDay();
    },
    courseKey(){
      switch(this.dateDay){
        case 1:
          return "mon_course_name"
          break;
        case 2:
          return "tue_course_name"
          break;
        case 3:
          return "wed_course_name"
          break;
        case 4:
          return "thu_course_name"
          break;
        case 5:
          return "fri_course_name"
          break;
      }
    },
    buildKey(){
      switch(this.dateDay){
        case 1:
          return "mon_build_name";
          break;
        case 2:
          return "tue_build_name";
          break;
        case 3:
          return "wed_build_name";
          break;
        case 4:
          return "thu_build_name";
          break;
        case 5:
          return "fri_build_name";
          break;
      }
    },
    roomKey(){
      switch(this.dateDay){
        case 1:
          return "mon_room";
          break;
        case 2:
          return "tue_room";
          break;
        case 3:
          return "wed_room";
          break;
        case 4:
          return "thu_room";
          break;
        case 5:
          return "fri_room";
          break;
      }
    },
    roomNameKey(){
      switch(this.dateDay){
        case 1:
          return "mon_room_name";
          break;
        case 2:
          return "tue_room_name";
          break;
        case 3:
          return "wed_room_name";
          break;
        case 4:
          return "thu_room_name";
          break;
        case 5:
          return "fri_room_name";
          break;
      }
    },
    teaKey(){
      switch(this.dateDay){
        case 1:
          return "mon_teacher_name";
          break;
        case 2:
          return "tue_teacher_name";
          break;
        case 3:
          return "wed_teacher_name";
          break;
        case 4:
          return "thu_teacher_name";
          break;
        case 5:
          return "fri_teacher_name";
          break;
      }
    },
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
    getRoom(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/base/classroom.php",
        data:{
          type: "sel_classroom",
          sel: 'room_department',
          val: this.sel.department,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.roomData = res;
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
      let col = null;
      let time = new Date();
      time.setTime(this.sel.time);
      time = time.getDay();
      switch(time){
        case 1:
          col = 'mon_course,mon_build,mon_room';
          break;
        case 2:
          col = 'tue_course,tue_build,tue_room';
          break;
        case 3:
          col = 'wed_course,wed_build,wed_room';
          break;
        case 4:
          col = 'thu_course,thu_build,thu_room';
          break;
        case 5:
          col = 'fri_course,fri_build,fri_room';
          break;
        case 6:
          return
          break;
        case 0:
          return;
          break;
      }
      col = col + ",session,class";
      if(time != 0 && time != 6){
        requestAjax({
          url: "/courseTable.php",
          type: 'get',
          data:{
            type: "sel_more_course_table",
            col: col,
            department: this.sel.department,
            time: this.sel.time,
            selobj: {
              'department':this.sel.department,
              'semester': this.sel.semester,
            },
          },
          async: false,
          success:(res)=>{
            res = JSON.parse(res);
            // console.log(res);
            this.classCourse = res;
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
      }else{
        this.classCourse = [];0
      }
    },
  }
}
</script>

<style>
  
</style>