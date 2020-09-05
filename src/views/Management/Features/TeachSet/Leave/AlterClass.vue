<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>在线调课</h1>
    </div>
    <el-form ref="form" :model="form" :rules="rules" label-width="100px">
      <el-form-item prop="teacher" label="教师">
        <el-select size="small" v-model="form.teacher" placeholder="教师">
          <el-option v-for="(item,i) in teacherData" :key="'teacher-'+i" :label="item.username" :value="item.userid"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="class" label="班级">
        <el-select @change="getCourse" size="small" v-model="form.class" placeholder="选择班级">
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="course" label="课程">
        <el-select v-model="form.course" size="small" placeholder="请选择课程">
          <el-option v-for="(item,i) in courseData" :key="'course-'+i" :label="item.course_name" :value="item.course"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="代课日期" prop="date">
        <el-date-picker size="small"
          format="yyyy-MM-dd"
          value-format="timestamp"
          type="date"
          placeholder="选择日期"
          v-model="form.date"
          style="width: 100%;">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="代课节次" prop="session">
        <el-select size="small" v-model="form.session" placeholder="请选择节次">
          <el-option v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name" :value="item.schedule_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="build" label="所在教学楼">
        <el-select @change="getRoom" size="small" v-model="form.build" placeholder="请选择建筑">
          <el-option v-for="(item,i) in buildData" :key="'build-' + i" :label="item.build_name" :value="item.build_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="room" label="所在课室">
        <el-select size="small" v-model="form.room" placeholder="请选择课室">
          <el-option v-for="(item,i) in roomData" :key="'room-' + i" :label="item.room_name" :value="item.room_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="type" label="被调类型">
        <el-radio-group v-model="form.type">
          <el-radio label="公假">公假</el-radio>
          <el-radio label="事假">事假</el-radio>
          <el-radio label="病假">病假</el-radio>
          <el-radio label="其他">其他</el-radio>
          <el-radio label="被调">被调</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="备注">
        <el-input type="textarea" placeholder="限字数100内！" v-model="form.remark"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('form')">立即创建</el-button>
        <el-button @click="onBack">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form:{
        teacher: "",
        class: "",
        course: "",
        date: "",
        session: "",
        build: "",
        room: "",
        type: "",
        remark: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        department: this.$store.state.userDepartment,
        semester: this.$store.state.semester,
        created_user: this.$store.state.userId,
        addTime: "",
      },

      teacherData: [],
      classData: [],
      loading: false,

      courseData: [],
      sessionData: [],
      buildData: [],
      roomData: [],

      
      rules:{
        teacher: [
          { required: true, message: '请选择代课教师', trigger: 'change' },
        ],
        class: [
          { required: true, message: '请选择代课班级', trigger: 'change' }
        ],
        course: [
          {required: true, message: '请选择代课课程', trigger: 'change' }
        ],
        date: [
          {required: true, message: '请选择代课时间', trigger: 'change' }
        ],
        session: [
          {required: true, message: '请选择代课节次', trigger: 'change' }
        ],
        build: [
          {required: true, message: '请选择代课所在教学楼', trigger: 'change' }
        ],
        room: [
          {required: true, message: '请选择代课所在课室', trigger: 'change' }
        ],
        type: [
          {required: true, message: '请选择代课类型', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.getTeacher();
    this.getClass();
    this.getSession();
    this.getBuild();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.form.addTime = new Date().getTime();
          let arr = Object.values(this.form);
          this.loading = true;
          // console.log(arr);
          requestAjax({
            type: 'post',
            url: "/teach/AlterClass.php",
            data:{
              type: 'add_alter_class',
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '课程调整成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加课务调整信息 "+ JSON.stringify(arr),
                  type: "添加记录",
                  model: "课务调整模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.$router.go(-1);
              }
              else{
                this.$message.error('添加失败，请稍后再试！');
              }
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
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    getTeacher(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        async: true,
        data:{
          type: "sel_tea",
          col: "id,userid,username",
          selobj: {
            'department': this.$store.state.userDepartment,
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
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : null,
            'status': "1",
          },
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          // console.log(this.$store.state.userDepartment);
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
    getCourse(c){
      this.loading = true;
      requestAjax({
        url: "/teach/TeaSchedule.php",
        type: 'get',
        data:{
          type: "sel_course_name",
          semester: this.$store.state.semester,
          class: this.form.class,
          // skill: this.classInfo['class_skill'],
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.courseData = res;
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
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          res.sort((a,b)=> a.schedule_order - b.schedule_order);
          this.sessionData = res;
          // $.each(res, (i, v)=>{ 
          //   this.newtable.push({
          //     session: v.schedule_id,
          //     mon_room: [],
          //     tue_room: [],
          //     wed_room: [],
          //     thu_room: [],
          //     fri_room: [],
          //   });
          // });
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
        async: true,
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
    getRoom(build){
      this.form.room = '',
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
          this.roomData = res;
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
  }
}
</script>

<style>
  .line{
    text-align: center;
  }
</style>