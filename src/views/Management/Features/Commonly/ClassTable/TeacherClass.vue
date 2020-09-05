<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>教师课表</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getTeacher" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCourse" size="small" v-model="sel.teacher" placeholder="查询教师">
          <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <div class="teacherTable" v-if="choose">
      <el-table 
        :data="sessionData" 
        border  
        size="mini"
        style="100%">
        <el-table-column fixed label="" width="90" prop="schedule_name"></el-table-column>
        <el-table-column min-width="100" align="center" label="周一" prop="monday">
          <template v-slot="scope">
            <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher)[0]['mon_course_name']) : '' }}</span>
            <br>
            <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher)[0]['mon_teacher_name']) : '' }}</span>
            <br>
            <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher)[0]['mon_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.mon_teacher == sel.teacher)[0]['mon_room_name']) : '', }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="100" align="center" label="周二" prop="tuesday">
          <template v-slot="scope">
            <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher)[0]['tue_course_name']) : '' }}</span>
            <br>
            <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher)[0]['tue_teacher_name']) : '' }}</span>
            <br>
            <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher)[0]['tue_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.tue_teacher == sel.teacher)[0]['tue_room_name']) : '', }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="100" align="center" label="周三" prop="wednesday">
          <template v-slot="scope">
            <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher)[0]['wed_course_name']) : '' }}</span>
            <br>
            <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher)[0]['wed_teacher_name']) : '' }}</span>
            <br>
            <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher)[0]['wed_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.wed_teacher == sel.teacher)[0]['wed_room_name']) : '', }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="100" align="center" label="周四" prop="thursday">
          <template v-slot="scope">
            <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher)[0]['thu_course_name']) : '' }}</span>
            <br>
            <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher)[0]['thu_teacher_name']) : '' }}</span>
            <br>
            <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher)[0]['thu_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.thu_teacher == sel.teacher)[0]['thu_room_name']) : '', }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="100" align="center" label="周五" prop="firday">
          <template v-slot="scope">
            <span class="course">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher).length > 0 ? setCourse(classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher)[0]['fri_course_name']) : '' }}</span>
            <br>
            <span class="teacher">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher).length > 0 ? setTeacher(classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher)[0]['fri_teacher_name']) : '' }}</span>
            <br>
            <span class="build">{{ classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher).length > 0 && classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher).length > 0 ? setBuild(classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher)[0]['fri_build_name'] , classCourse.filter(item=>item.session == scope.row.schedule_id && item.fri_teacher == sel.teacher)[0]['fri_room_name']) : '', }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="100" align="center" label="周六" prop="saturday"></el-table-column>
        <el-table-column min-width="100" align="center" label="周日" prop="sunday"></el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        teacher: this.$store.state.userId,
        department: this.$store.state.userDepartment,
        semester: this.$store.state.semester,
      },
      teacherTable:[],

      // time: Date.parse(new Date().getTime()),

      sessionData: [],
      semesterData: [],
      departmentData: [],
      teacherData: [],

      classCourse: [],

      time: "",
    }
  },
  computed:{
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
    getDate(){
      let date = new Date();
      date.setHours(0,0,0,0);
      this.time = date.getTime();
    },
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
      if (columnIndex === 0) {
        if (rowIndex === 0) {
          return {
            rowspan: 4,
            colspan: 1
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
    getTeacher(){
      this.loading = true;
      this.sel.teacher = '';
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        async: false,
        data:{
          type: "sel_tea",
          col: "id,userid,username",
          selobj: {
            'department':this.sel.department,
            'type': '1',
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.teacherData = res;
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
    getCourse(){
      this.loading = true;
      let start = this.getWeekStartDate();
      let end = this.getWeekEndDate();
      requestAjax({
        url: "/courseTable.php",
        // url: "/teach/ClassSchedule.php",
        type: 'get',
        data:{
          type: "sel_teacher_schedule_name",
          // col: col,
          teacher:this.sel.teacher,
          semester: this.sel.semester,
          campus: this.$store.state.userCampus,
          start: start,
          end: end,
          department: this.sel.department,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          // res.sort((prev,next)=>{
          //   return this.sessionData.filter(item => prev.session == item.schedule_id)[0]['schedule_order'] - this.sessionData.filter(item => item.schedule_id == next.session)[0]['schedule_order'];
          // });
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
  },
  created(){
    this.getDate();
    this.getSession();
    this.getSemester();
    this.getDepartment();
    this.getTeacher();
    this.sel.teacher = this.$store.state.userId;
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
        return teacher;
      }
    },
  }
}
</script>

<style scoped>
  .course{
    color: #000;
    font-size: 13px;
  }
  .teacher{
    color: #666;
    font-size: 13px;
  }
  .build{
    color: #999;
    font-size: 12px;
  }
</style>