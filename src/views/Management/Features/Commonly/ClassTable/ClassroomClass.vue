<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>班级课表</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCourse" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <div class="classTable" v-if="choose">
        <!-- :span-method="objectSpanMethod"  -->
      <el-table 
        :data="classCourse" 
        border  
        size="small"
        style="100%">
        <el-table-column label="" width="100" fixed>
          <template v-slot="scope">
            {{
              sessionData.filter(item => item.schedule_id == scope.row.session)[0]['schedule_name']
            }}
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周一" prop="monday">
          <template v-slot="scope">
            <span class="course">{{ setCourse(scope.row.mon_course_name) }}</span>
            <br>
            <span class="teacher">{{ setTeacher(scope.row.mon_teacher_name) }}</span>
            <br>
            <span class="build">{{ setBuild(scope.row.mon_build_name , scope.row.mon_room_name) }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周二" prop="tuesday">
          <template v-slot="scope">
            <span class="course">{{ setCourse(scope.row.tue_course_name) }}</span>
            <br>
            <span class="teacher">{{ setTeacher(scope.row.tue_teacher_name) }}</span>
            <br>
            <span class="build">{{ setBuild(scope.row.tue_build_name , scope.row.tue_room_name) }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周三" prop="wednesday">
          <template v-slot="scope">
            <span class="course">{{ setCourse(scope.row.wed_course_name) }}</span>
            <br>
            <span class="teacher">{{ setTeacher(scope.row.wed_teacher_name) }}</span>
            <br>
            <span class="build">{{ setBuild(scope.row.wed_build_name , scope.row.wed_room_name) }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周四" prop="thursday">
          <template v-slot="scope">
            <span class="course">{{ setCourse(scope.row.thu_course_name) }}</span>
            <br>
            <span class="teacher">{{ setTeacher(scope.row.thu_teacher_name) }}</span>
            <br>
            <span class="build">{{ setBuild(scope.row.thu_build_name , scope.row.thu_room_name) }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周五" prop="firday">
          <template v-slot="scope">
            <span class="course">{{ setCourse(scope.row.fri_course_name) }}</span>
            <br>
            <span class="teacher">{{ setTeacher(scope.row.fri_teacher_name) }}</span>
            <br>
            <span class="build">{{ setBuild(scope.row.fri_build_name , scope.row.fri_room_name) }}</span>
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周六" prop="saturday">
          <template>
            无
          </template>
        </el-table-column>
        <el-table-column min-width="110" align="center" label="周日" prop="sunday">
          <template>
            无
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
        time: new Date().getTime(),
        class: "",
      },
      tableData:[],
      semesterData: [],
      classData: [],
      departmentData: [],
      sessionData: [],
      classCourse: [],

      time: '',
    }
  },
  created(){
    this.getDate();
    this.getSession();
    this.getSemester();
    this.getDepartment();
    this.getClass();
  },
  methods:{
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
      this.getCourse();
    },
    getDate(){
      let date = new Date();
      date.setHours(0,0,0,0);
      this.time = date.getTime();
    },
    getCourse(){
      this.loading = true;
      requestAjax({
        url: "/courseTable.php",
        type: 'get',
        data:{
          type: "sel_more_course_table",
          department: this.sel.department,
          time: this.time,
          selobj: {
            'department':this.sel.department,
            'semester': this.semester,
            'class': this.sel.class,
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