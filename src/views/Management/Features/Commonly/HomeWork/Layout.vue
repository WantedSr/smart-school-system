<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>布置作业</h1>
    </div>
    
    <div class="layout">
      <el-form ref="form" :rules="rules" :model="form" label-width="80px">
        <el-form-item prop="title" label="作业标题">
          <el-input size="small" v-model="form.title"></el-input>
        </el-form-item>
        <el-form-item prop="class" label="班级">
          <el-select @change="getCourse" size="small" v-model="form.class" placeholder="选择班级">
            <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="course" label="课程">
          <el-select size="small" v-model="form.course" placeholder="课程">
            <el-option v-for="(item,i) in classCourse" :key="'c-'+i+'-'" :label="item.course_name" :value="item.course"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="duration" label="作业时长">
          <el-time-picker size="small" value-format="HH:mm" format="HH:mm" placeholder="选择时间" v-model="form.duration" style="width: 100%;"></el-time-picker>
        </el-form-item>
        <el-form-item prop="end_date" label="日期">
          <el-date-picker size="small" value-format="timestamp" type="date" placeholder="选择日期" v-model="form.end_date" style="width: 100%;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="type" label="类型">
          <el-radio-group size="small" v-model="form.type">
            <el-radio label="实操"></el-radio>
            <el-radio label="书面"></el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item prop="content" label="内容">
          <editor-bar v-model="form.content" :isClear="isClear" @change="change"></editor-bar>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onSubmit('form')">立即创建</el-button>
          <el-button size="small" @click="onExit">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
import EditorBar from "components/common/wangeditor"
export default {
  data(){
    return{

      form:{
        semester: this.$store.state.semester,
        title: "",
        course: "",
        class: "",
        duration: "01:00",
        end_date: new Date().setHours(0,0,0,0) + 86400000,
        type: "",
        content: "",
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },

      rules:{
        title: [
          { required: true, message: '请输入作业标题', trigger: 'blur' },
        ],
        course: [
          { required: true, message: '请选择作业课程', trigger: 'blur' }
        ],
        class: [
          {required: true, message: '请选择班级', trigger: 'change' }
        ],
        duration: [
          {required: true, message: '请选择作业时长', trigger: 'change' }
        ],
        end_date: [
          {required: true, message: '请选择作业时间', trigger: 'change' }
        ],
        type: [
          {required: true, message: '请选择作业类型', trigger: 'change' }
        ],
        content: [
          {required: true, message: '请输入作业内容', trigger: 'blur' }
        ],
      },

      classCourse: [],
      classData: [],

      classInfo: {},
    }
  },
  components:{
    EditorBar,
  },
  created(){
    this.getClass();
  },
  methods:{
    change(val){
      this.form.content = val;
    },
    getClassInfo(){
      this.loading = true;
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
          selobj: {
            "class_id":this.form.class,
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
    onExit(){
      location.href = '/management/backstage';
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
            'department': this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : '0',
            'status': "1",
          },
        },
        async: true,
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
    getCourse(){
      this.loading = true;
      this.form.course = '';
      this.getClassInfo();
      requestAjax({
        url: "/teach/ClassCourse.php",
        type: 'get',
        data:{
          type: "sel_course_name",
          semester: this.$store.state.semester,
          class: this.form.class,
          skill: this.classInfo['class_skill'],
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classCourse = res;
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
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if(this.form.content == ''){
            this.$message({
              message: "请输入作业内容",
              type: 'warning',
            })  
            return false;
          }
          this.form.addTime = new Date().getTime();
          requestAjax({
            type: "post",
            url: "/Homework/homework.php",
            data: {
              type: "add_homework",
              obj: this.form,
            },
            success:(res)=>{
              res = JSON.parse(res);
              // console.log(res);
              if(res){
                this.$message({
                  message: '作业布置成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "作业布置信息 学科："+ this.form.course + " 班级：" + this.form.class + " 日期：" + new Date().setHours(0,0,0,0),
                  type: "添加记录",
                  model: "作业模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                // this.$router.go(-1);
                location.reload();
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
  }
}
</script>

<style>
  .layout{
    padding: 12px;
  }
</style>