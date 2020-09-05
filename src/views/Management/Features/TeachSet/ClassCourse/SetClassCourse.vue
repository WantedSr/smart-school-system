<template>
  <div class="subpage">  
    <div class="pagehead">
      <h1>{{classInfo.class_name}} 班级开课</h1>
    </div>
    <div style="margin-bottom: 36px" v-loading="loading" v-if="semesterCourse.length > 0">
      <div class="courseItem" v-for="(item,i) in semesterCourse" :key="'c-'+i">
        <el-switch
          v-model="classCourse.filter(item2 => item2.course == item.course)[0]['status']"
          :inactive-text="item.course_name"
          active-color="#13ce66"
          inactive-color="#95a5a6"
          active-value="1"
          inactive-value="0">
        </el-switch>
      </div>
    </div>
    <div v-else class="nomore" style="margin-bottom: 36px">
      <el-alert
        title="暂无课程数据"
        show-icon 
        type="info"
        :closable="false">
      </el-alert>
    </div>
    <el-button size="small" @click="onBack">返回</el-button>
    <el-button size="small" type="primary" @click="onSubmit">提交</el-button>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      class: this.$route.query.class,
      semester: this.$route.query.sem,
      classInfo: [],
      courseData: [],
      value: "",
      loading: false,
      
      semesterCourse: [],
      classCourse: [],
    }
  },
  created(){
    this.getClassInfo();
    this.getSemesterCourse();
    this.getClassCourse();
    $.each(this.semesterCourse,(i, v)=>{ 
      if(this.classCourse.filter(item=>item.course == v.course).length == 0){
        this.classCourse.push({
          'course': v.course,
          "semester": this.semester,
          "school": this.$store.state.userSchool,
          'campus': this.$store.state.userCampus, 
          "skill": this.classInfo['class_skill'],
          "class": this.class,
          'status': '0',
          "created_user": this.$store.state.userId,
        })
      }
    });
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
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
    getSemesterCourse(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterCourse.php",
        type: 'get',
        data:{
          type: "sel_course_name",
          semester: this.semester,
          skill: this.classInfo['class_skill'],
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterCourse = res;
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
    getClassCourse(){
      this.loading = true;
      requestAjax({
        url: "/teach/ClassCourse.php",
        type: 'get',
        data:{
          type: "sel_course",
          semester: this.semester,
          class: this.class,
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
    onSubmit(){ 
      this.loading = true;
      console.log(this.classCourse);
      requestAjax({
        url: "/teach/ClassCourse.php",
        type: 'post',
        data:{
          type: "set_course",
          arr: this.classCourse,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          $.each(res, (i, v)=>{ 
            if(v){
              return;
            }else{
              this.$message.error(this.classCourse[i]['course']+' 设置错误!');
            }
          });
          
          this.$message({
            message: '恭喜你，课程设置成功！',
            type: 'success'
          });

          
          // 日志写入
          let obj = {
            content: "班级开课设置 学期:" + this.semester + " 班级: " + this.class,
            type: "修改记录",
            model: "班级开课模块",
            ip: sessionStorage.getItem('ip'),
            user: this.$store.state.userId,
            area: sessionStorage.getItem("area"),
            brower: sysTool.GetCurrentBrowser(),
            addTime: new Date().getTime(),
          }
          let arr = Object.values(obj);
          this.$store.commit("writeLog",arr);

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
  }
}
</script>

<style scoped>
  .courseItem{
    margin-bottom: 6px;
  }
</style>