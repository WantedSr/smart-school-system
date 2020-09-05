<template>
  <div class="schudule box">
    <el-row>
      <el-col :lg="24" class="head por">
        <h4 class="por">课程表<small class="poa">Schudule</small></h4>
        <!-- <div class="classDate poa">
          <a href="#"><i class="el-icon-arrow-left"></i></a>
          <span>临时课表</span>
          <a href="#"><i class="el-icon-arrow-right"></i></a>
        </div> -->
      </el-col>
      <el-col :lg="24" class="classTable por table-responsive">
        <el-table 
          v-loading="loading"
          :data="classCourse" 
          border 
          height="680"
          size="mini"
          style="100%">
          <el-table-column label="" width="50" fixed>
            <template v-slot="scope">
              {{
                sessionData.filter(item => item.schedule_id == scope.row.session)[0]['schedule_name']
              }}
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周一" prop="monday">
            <template v-slot="scope">
              <span class="course">{{ setCourse(scope.row.mon_course_name) }}</span>
              <br>
              <span class="teacher">{{ setTeacher(scope.row.mon_teacher_name) }}</span>
              <br>
              <span class="build">{{ setBuild(scope.row.mon_build_name , scope.row.mon_room_name) }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周二" prop="tuesday">
            <template v-slot="scope">
              <span class="course">{{ setCourse(scope.row.tue_course_name) }}</span>
              <br>
              <span class="teacher">{{ setTeacher(scope.row.tue_teacher_name) }}</span>
              <br>
              <span class="build">{{ setBuild(scope.row.tue_build_name , scope.row.tue_room_name) }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周三" prop="wednesday">
            <template v-slot="scope">
              <span class="course">{{ setCourse(scope.row.wed_course_name) }}</span>
              <br>
              <span class="teacher">{{ setTeacher(scope.row.wed_teacher_name) }}</span>
              <br>
              <span class="build">{{ setBuild(scope.row.wed_build_name , scope.row.wed_room_name) }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周四" prop="thursday">
            <template v-slot="scope">
              <span class="course">{{ setCourse(scope.row.thu_course_name) }}</span>
              <br>
              <span class="teacher">{{ setTeacher(scope.row.thu_teacher_name) }}</span>
              <br>
              <span class="build">{{ setBuild(scope.row.thu_build_name , scope.row.thu_room_name) }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周五" prop="firday">
            <template v-slot="scope">
              <span class="course">{{ setCourse(scope.row.fri_course_name) }}</span>
              <br>
              <span class="teacher">{{ setTeacher(scope.row.fri_teacher_name) }}</span>
              <br>
              <span class="build">{{ setBuild(scope.row.fri_build_name , scope.row.fri_room_name) }}</span>
            </template>
          </el-table-column>
        </el-table>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      tableData:[],
      loading: false,
      classCourse: [],
      sessionData: [],
    }
  },
  methods:{
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
      if (columnIndex === 0) {
        if (rowIndex === 0) {
          return {
            rowspan: 4,
            colspan: 1,
          };
        }
        else if(rowIndex === 4 || rowIndex === 6){
          return {
            rowspan: 2,
            colspan: 1
          };
        }
        else {
          return {
            rowspan: 0,
            colspan: 0
          };
        }
      }
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
    getCourse(){
      this.loading = true;
      requestAjax({
        url: "/courseTable.php",
        type: 'get',
        data:{
          type: "sel_more_course_table",
          department: this.$store.state.userDepartment,
          time: new Date().setHours(0,0,0,0),
          selobj: {
            'department':this.$store.state.userDepartment,
            'semester': this.$store.state.semester,
            'class': this.cls,
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          res.sort((prev,next)=>{
            return this.sessionData.filter(item => prev.session == item.schedule_id)[0]['schedule_order'] - this.sessionData.filter(item => item.schedule_id == next.session)[0]['schedule_order'];
          });
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
    },
  },
  created(){
    // console.log(this.cls);
    this.getSession();
    this.getCourse();
  },
  props:{
    cls:{
      type: String,
      require: true,
    }
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
    setBuild(){
      return (build,room)=>{
        if(build == '' || build == null){
          build = '无具体位置';
        }
        if(room == '' || room == null){
          room = '无具体位置'
        }
        return build + ' - ' + room;
      }
    },
    setCourse(){
      return (course)=>{
        if(course == '' || course == null){
          course = '自习';
        }
        return course;
      }
    },
    setTeacher(){
      return (teacher)=>{
        if(teacher == '' || teacher == null){
          teacher = '无教师'
        }
        return teacher;
      }
    },
  }
}
</script>

<style>
  /* ----------------schudule------------- */

  .schudule{
    height: 755px;
    overflow: hidden;
    padding-bottom: 20px;
  }
  .classDate{
    top: 12px;
    left: 50%;
    transform: translateX(-50%);
  }
  .classDate>a>i{
    font-size: 16px;
    margin: 0 6px;
  }
  .classDate>i{
    font-size: 16px;
  }
  .classTable table{
    font-size: 12px;
    text-align: center;
  }
</style>