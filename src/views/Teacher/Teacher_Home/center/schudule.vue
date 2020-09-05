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
          :data="sessionData" 
          border 
          v-loading="loading"
          height="680"
          size="mini"
          style="100%">
          <el-table-column fixed label="" width="50" prop="schedule_name"></el-table-column>
          <el-table-column min-width="100" align="center" label="周一" prop="monday">
            <template v-slot="scope">
              <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId)[0]['mon_course_name']) : '-' }}</span>
              <br>
              <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId)[0]['mon_teacher_name']) : '-' }}</span>
              <br>
              <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId)[0]['mon_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == $store.state.userId)[0]['mon_room_name']) : '-', }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周二" prop="tuesday">
            <template v-slot="scope">
              <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId)[0]['tue_course_name']) : '-' }}</span>
              <br>
              <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId)[0]['tue_teacher_name']) : '-' }}</span>
              <br>
              <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId)[0]['tue_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == $store.state.userId)[0]['tue_room_name']) : '-', }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周三" prop="wednesday">
            <template v-slot="scope">
              <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId)[0]['wed_course_name']) : '-' }}</span>
              <br>
              <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId)[0]['wed_teacher_name']) : '-' }}</span>
              <br>
              <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId)[0]['wed_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == $store.state.userId)[0]['wed_room_name']) : '-', }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周四" prop="thursday">
            <template v-slot="scope">
              <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId)[0]['thu_course_name']) : '-' }}</span>
              <br>
              <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId)[0]['thu_teacher_name']) : '-' }}</span>
              <br>
              <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId)[0]['thu_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == $store.state.userId)[0]['thu_room_name']) : '-', }}</span>
            </template>
          </el-table-column>
          <el-table-column min-width="100" align="center" label="周五" prop="firday">
            <template v-slot="scope">
              <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId)[0]['fri_course_name']) : '-' }}</span>
              <br>
              <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId)[0]['fri_teacher_name']) : '-' }}</span>
              <br>
              <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId)[0]['fri_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == $store.state.userId)[0]['fri_room_name']) : '-', }}</span>
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
    //获得本周的开始日期
    getWeekStartDate() {
      var now = new Date(); //当前日期
      var nowDayOfWeek = now.getDay(); //今天本周的第几天
      var nowDay = now.getDate(); //当前日
      var nowMonth = now.getMonth(); //当前月
      var nowYear = now.getYear(); //当前年
      nowYear += (nowYear < 2000) ? 1900 : 0; //
      var weekStartDate = new Date(nowYear, nowMonth, nowDay - nowDayOfWeek);
      return weekStartDate.getTime();
    },
    //获得本周的结束日期
    getWeekEndDate() {
      var now = new Date(); //当前日期
      var nowDayOfWeek = now.getDay(); //今天本周的第几天
      var nowDay = now.getDate(); //当前日
      var nowMonth = now.getMonth(); //当前月
      var nowYear = now.getYear(); //当前年
      nowYear += (nowYear < 2000) ? 1900 : 0; //
      var weekEndDate = new Date(nowYear, nowMonth, nowDay + (6 - nowDayOfWeek));
      return weekEndDate.getTime();;
    },
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
      let start = this.getWeekStartDate();
      let end = this.getWeekEndDate();
      requestAjax({
        url: "/courseTable.php",
        type: 'get',
        data:{
          type: "sel_teacher_schedule_name",
          teacher:this.$store.state.userId,
          semester: this.$store.state.semester,
          campus: this.$store.state.userCampus,
          start: start,
          end: end,
          department: this.$store.state.userDepartment,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.classCourse = res;
          $.each(this.sessionData, (i, v)=>{ 
            if(this.classCourse.filter(item => item.session == v.schedule_id).length != 0){
              return;
            }else{
              this.classCourse.push({
                "session":v.schedule_id,
                "mon_course_name":null,
                "mon_build_name":null,
                "mon_room_name":null,
                "tue_course_name":null,
                "tue_build_name":null,
                "tue_room_name":null,
                "wed_course_name":null,
                "wed_build_name":null,
                "wed_room_name":null,
                "thu_course_name":null,
                "thu_build_name":null,
                "thu_room_name":null,
                "fri_course_name":null,
                "fri_build_name":null,
                "fri_room_name":null,
              });
            }
          });
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