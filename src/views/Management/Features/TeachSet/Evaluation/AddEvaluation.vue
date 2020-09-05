<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>添加公开课任务</h1>
    </div>
    <div class="addEvaluation">
      <el-form size="small" ref="form" :rules="rules" :model="form" label-width="100px">
        <el-form-item prop="title" label="公开课课题">
          <el-input v-model="form.title" autocomplete="off" placeholder="输入公开课课题"></el-input>
        </el-form-item>
        <el-form-item prop="department" label="所在部门">
          <el-select @change="getTeacher" size="small" v-model="form.department" placeholder="查询部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="teacher" label="授课教师">          
          <el-select size="small" v-model="form.teacher" placeholder="教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="organizer" label="组织者">          
          <el-select size="small" v-model="form.organizer" placeholder="教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="type" label="公开课类型">          
          <el-select size="small" v-model="form.type" placeholder="公开课类别">
            <el-option label="校内" value="校内"></el-option>
            <el-option label="校外" value="校外"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="lever" label="公开课级别">
          <el-select size="small" v-model="form.lever" placeholder="公开课类别">
            <el-option label="部级" value="部级"></el-option>
            <el-option label="校级" value="校级"></el-option>
            <el-option label="区级" value="区级"></el-option>
            <el-option label="市级" value="市级"></el-option>
            <el-option label="省级" value="省级"></el-option>
            <el-option label="国家级" value="国家级"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="date" label="公开课时间">
          <el-date-picker
            v-model="form.date"
            type="date"
            placeholder="选择日期"
            format="yyyy-MM-dd"
            value-format="timestamp">
          </el-date-picker>
        </el-form-item>
        <el-form-item prop="session" label="公开课节次">
          <el-select size="small" v-model="form.session" placeholder="公开课节次">
            <el-option v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name" :value="item.schedule_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class" label="上课班级">
          <el-select size="small" v-model="form.class" placeholder="公开课节次">
            <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="course" label="公开课课程">
          <el-select size="small" v-model="form.course" placeholder="公开课课程">
            <el-option v-for="(item,i) in courseData" :key="'course-'+i" :label="item.course_name" :value="item.course_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onAdd('form')">添加</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form: {
        title: "",
        teacher: "",
        organizer: "",
        informant: "",
        type: "",
        lever: "",
        date: "",
        session: "",
        class: "",
        course: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        department: this.$store.state.userDepartment,
        semester: this.$store.state.semester,
        status: '0',
        created_user: this.$store.state.userId,
      },

      rules:{
        teacher: [
          { required: true, message: '请选择教师', trigger: 'change' },
        ],
        organizer: [
          { required: true, message: '请选择组织者', trigger: 'change' },
        ],
        informant: [
          { required: true, message: '请选择填报人', trigger: 'change' },
        ],
        title: [
          { required: true, message: '请选择专业代码', trigger: 'blur' }
        ],
        department: [
          {required: true, message: '请选择所在部门', trigger: 'change' }
        ],
        type: [
          {required: true, message: '请选择公开课类型', trigger: 'change' }
        ],
        lever: [
          {required: true, message: '请选择专业所在部门', trigger: 'change' }
        ],
        date: [
          {required: true, message: '请选择所在时间', trigger: 'change' }
        ],
        session: [
          {required: true, message: '请选择所在节次', trigger: 'change' }
        ],
        class: [
          {required: true, message: '请选择上课班级', trigger: 'change' }
        ],
        course: [
          {required: true, message: '请选择公开课课程', trigger: 'change' }
        ],
      },

      departmentData: [],
      teacherData: [],
      sessionData: [],
      classData: [],
      courseData: [],
    }
  },
  methods:{
    getTeacher(){
      this.form.teacher = "";
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        async: false,
        data:{
          type: "sel_tea",
          col: "id,userid,username",
          selobj: {
            'department':this.form.department,
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
      });
      this.getClass();
      this.getCourse();
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
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.form.department,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.form.department,
            'status': "1",
          },
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
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
    getCourse(){
      this.loading = true;
      requestAjax({
        url: "/base/course.php",
        type: 'get',
        data:{
          type: "sel_course",
          sel: 'course_department',
          val: this.form.department,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.courseData = res;
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.form);
          arr.push(new Date().getTime());
          requestAjax({
            type: "post",
            url: "/teach/Evaluation.php",
            data: {
              type: "add_evaluation",
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '添加公开课成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加公开课信息 "+ JSON.stringify(arr),
                  type: "添加记录",
                  model: "公开课模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);
                
                for(let i in this.form){
                  this.form[i] = '';
                }
              }
              else{
                this.$message.error('添加失败，请稍后再试！');
              }
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  },
  created(){
    this.getDepartment();
    this.getTeacher();
    this.getSession();
  },
}
</script>

<style>
  .addEvaluation{
    padding: 0 24px;
  }
</style>