<template>
  <div class="subpage">
    
    <div class="pagehead">
      <h1>班级排课 {{classInfo['class_name']}}</h1>
    </div>

    <div class="scheduleTable box" v-loading="loading">
      <el-table 
        :data="newtable" 
        border >
        <el-table-column fixed label="" width="100">
          <template v-slot="scope">
            <p>{{sessionData[scope.$index]['schedule_name']}}</p>
          </template>
        </el-table-column>
        <el-table-column label="周一" prop="monday" v-if="classCourse.length > 0">
          <template v-slot="scope">
            <el-select
              v-model="classCourse.filter(item => item.session == scope.row.session)[0]['mon_course']"
              placeholder="请选择课程">
              <el-option v-for="(item,i) in course" :key="'mon_c_'+i" :label="item.teacher_name + '-' + item.course_name" :value="item.id"></el-option>
            </el-select>
            <el-select @change="getRoom(classCourse.filter(item => item.session == scope.row.session)[0]['mon_build'],scope.$index,'mon')" v-model="classCourse.filter(item => item.session == scope.row.session)[0]['mon_build']" placeholder="请选择教学楼">
              <el-option v-for="(item,i) in buildData" :key="'mon_b_' + i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
            <!-- {{ classCourse.filter(item=>item.session == scope.row.session)[0]['mon_room'] != '' ? getRoom(classCourse.filter(item=>item.session==scope.row.session)[0]['mon_build'],scope.$index,'mon') : '' }} -->
            <el-select v-model="classCourse.filter(item => item.session == scope.row.session)[0]['mon_room']" placeholder="请选择课室">
              <el-option v-for="(item,i) in scope.row.mon_room" :key="'mon_r_' + i" :label="item.room_name" :value="item.room_id"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="周二" prop="tuesday">
          <template v-slot="scope">
            <el-select
              v-model="classCourse.filter(item => item.session == scope.row.session)[0]['tue_course']"
              placeholder="请选择课程">
              <el-option v-for="(item,i) in course" :key="'tue_c_'+i" :label="item.teacher_name + '-' + item.course_name" :value="item.id"></el-option>
            </el-select>
            <el-select @change="getRoom(classCourse.filter(item => item.session == scope.row.session)[0]['tue_build'],scope.$index,'tue')" v-model="classCourse.filter(item => item.session == scope.row.session)[0]['tue_build']" placeholder="请选择教学楼">
              <el-option v-for="(item,i) in buildData" :key="'tue_b_' + i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
            <!-- {{ classCourse.filter(item=>item.session == scope.row.session)[0]['tue_room'] != '' ? getRoom(classCourse.filter(item=>item.session==scope.row.session)[0]['tue_build'],scope.$index,'tue') : '' }} -->
            <el-select v-model="classCourse.filter(item => item.session == scope.row.session)[0]['tue_room']" placeholder="请选择课室">
              <el-option v-for="(item,i) in scope.row.tue_room" :key="'tue_r_' + i" :label="item.room_name" :value="item.room_id"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="周三" prop="wednesday">
          <template slot-scope="scope">
            <el-select
              v-model="classCourse.filter(item => item.session == scope.row.session)[0]['wed_course']"
              placeholder="请选择课程">
              <el-option v-for="(item,i) in course" :key="'wed_c_'+i" :label="item.teacher_name + '-' + item.course_name" :value="item.id"></el-option>
            </el-select>
            <el-select @change="getRoom(classCourse.filter(item => item.session == scope.row.session)[0]['wed_build'],scope.$index,'wed')" v-model="classCourse.filter(item => item.session == scope.row.session)[0]['wed_build']" placeholder="请选择教学楼">
              <el-option v-for="(item,i) in buildData" :key="'wed_b_' + i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
            <!-- {{ classCourse.filter(item=>item.session == scope.row.session)[0]['wed_room'] != '' ? getRoom(classCourse.filter(item=>item.session==scope.row.session)[0]['wed_build'],scope.$index,'wed') : '' }} -->
            <el-select v-model="classCourse.filter(item => item.session == scope.row.session)[0]['wed_room']" placeholder="请选择课室">
              <el-option v-for="(item,i) in scope.row.wed_room" :key="'wed_r_' + i" :label="item.room_name" :value="item.room_id"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="周四" prop="thursday">
          <template slot-scope="scope">
            <el-select
              v-model="classCourse.filter(item => item.session == scope.row.session)[0]['thu_course']"
              placeholder="请选择课程">
              <el-option v-for="(item,i) in course" :key="'thu_c_'+i" :label="item.teacher_name + '-' + item.course_name" :value="item.id"></el-option>
            </el-select>
            <el-select @change="getRoom(classCourse.filter(item => item.session == scope.row.session)[0]['thu_build'],scope.$index,'thu')" v-model="classCourse.filter(item => item.session == scope.row.session)[0]['thu_build']" placeholder="请选择教学楼">
              <el-option v-for="(item,i) in buildData" :key="'thu_b_' + i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
            <!-- {{ classCourse.filter(item=>item.session == scope.row.session)[0]['thu_room'] != '' ? getRoom(classCourse.filter(item=>item.session==scope.row.session)[0]['thu_build'],scope.$index,'thu') : '' }} -->
            <el-select v-model="classCourse.filter(item => item.session == scope.row.session)[0]['thu_room']" placeholder="请选择课室">
              <el-option v-for="(item,i) in scope.row.thu_room" :key="'thu_r_' + i" :label="item.room_name" :value="item.room_id"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column label="周五" prop="firday">
          <template slot-scope="scope">
            <el-select
              v-model="classCourse.filter(item => item.session == scope.row.session)[0]['fri_course']"
              placeholder="请选择课程">
              <el-option v-for="(item,i) in course" :key="'fri_c_'+i" :label="item.teacher_name + '-' + item.course_name" :value="item.id"></el-option>
            </el-select>
            <el-select @change="getRoom(classCourse.filter(item => item.session == scope.row.session)[0]['fri_build'],scope.$index,'fri')" v-model="classCourse.filter(item => item.session == scope.row.session)[0]['fri_build']" placeholder="请选择教学楼">
              <el-option v-for="(item,i) in buildData" :key="'fri_b_' + i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
            <!-- {{ classCourse.filter(item=>item.session == scope.row.session)[0]['fri_room'] != '' ? getRoom(classCourse.filter(item=>item.session==scope.row.session)[0]['fri_build'],scope.$index,'fri') : '' }} -->
            <el-select v-model="classCourse.filter(item => item.session == scope.row.session)[0]['fri_room']" placeholder="请选择课室">
              <el-option v-for="(item,i) in scope.row.fri_room" :key="'fri_r_' + i" :label="item.room_name" :value="item.room_id"></el-option>
            </el-select>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <el-button @click="onBack" size="small">返回</el-button>
    <el-button @click="onSubmit" size="small" type="primary">提交</el-button>
  
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      active: 0,
      newtable:[],
      tableData:[],
      
      class: this.$route.query.class,
      semester: this.$route.query.sem,

      classInfo: [],
      courseData: [],
      sessionData: [],
      classCourse: [],

      course: [],

      value: "",
      loading: false,
      buildData: [],
    }
  },
  created(){
    this.getClassInfo();
    if(this.classInfo['class_department'] != this.$store.state.userDepartment){
      this.$message({
        message: '无法跨部门查询',
        type: 'warning'
      });
    }
    this.getSession();
    this.getClassSchedule();
    $.each(this.sessionData, (i, v)=>{ 
      let arr = this.classCourse.filter(item => item.session == v.schedule_id);
      if(arr.length == 0){
        this.classCourse.push({
          class: this.class,
          semester: this.semester,
          session: v.schedule_id,
          mon_course: "",
          mon_build: "",
          mon_room: "",
          tue_course: "",
          tue_build: "",
          tue_room: "",
          wed_course: "",
          wed_build: "",
          wed_room: "",
          thu_course: "",
          thu_build: "",
          thu_room: "",
          fri_course: "",
          fri_build: "",
          fri_room: "",
          school: this.$store.state.userSchool,
          campus: this.$store.state.userCampus,
          department: this.classInfo['class_department'],
          created_user: this.$store.state.userId,
        })
      }
    });
    this.getCourse();
    this.getBuild();

  },
  methods:{
    getClassInfo(){
      this.loading = true;
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
          selobj: {
            "class_id":this.class,
          },
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          // console.log(res);
          this.classInfo = res;
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
          $.each(res, (i, v)=>{ 
            this.newtable.push({
              session: v.schedule_id,
              mon_room: [],
              tue_room: [],
              wed_room: [],
              thu_room: [],
              fri_room: [],
            });
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
    getClassSchedule(){
      this.loading = true;
      requestAjax({
        url: "/teach/ClassSchedule.php",
        type: 'get',
        data:{
          type: "sel_schedule",
          selobj: {
            'campus':this.$store.state.userCampus,
            'class': this.class,
            'semester': this.semester,
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.classCourse = res;

          // console.log(this.classCourse.filter(item2 => item2.session == 'ZX1')[0]['mon_course']);
          // console.log(this.classCourse);
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
        url: "/teach/TeaSchedule.php",
        type: 'get',
        data:{
          type: "sel_course_name",
          semester: this.semester,
          class: this.class,
          skill: this.classInfo['class_skill'],
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.course = res;
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
    getBuild(){
      this.loading = true;
      requestAjax({
        url: "/base/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          sel: 'build_campus',
          val: this.$store.state.userCampus,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          // console.log(res);
          res = JSON.parse(res);
          this.buildData = res;
          // console.log(res)
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
    getRoom(build,index,day){
      let key = day + '_room';
      this.classCourse.filter(item => item.session == this.newtable[index]['session'])[0][key] = null;
      this.loading = true;
      requestAjax({
        type: 'get',
        url: "/base/classroom.php",
        data:{
          type: 'sel_classroom',
          sel: 'room_build',
          val: build,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.newtable[index][key] = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        },
      })
    },
    onSubmit(){
      this.loading = true;
      requestAjax({
        url: "/teach/ClassSchedule.php",
        type: 'post',
        data:{
          type: "set_schedule",
          arr: this.classCourse,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          
          $.each(res, (i, v)=>{ 
            if(v){
              return;
            }else{
              this.$message.error(JSON.stringify(this.classCourse[i])+' 设置错误!');
            }
          });
          
          this.$message({
            message: '恭喜你，班级开课成功！',
            type: 'success'
          });

          this.loading = false;

          this.$router.go(-1);
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
    onBack(){
      this.$router.go(-1);
    }
  }
}
</script>

<style>

</style>