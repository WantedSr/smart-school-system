<template>
  <div class="subpage">  
    <div class="pagehead">
      <h1>学期开课</h1>
    </div>
    <div style="margin-bottom: 36px" v-loading="loading" v-if="courseData.length > 0">
      <div class="courseItem" v-for="(item,i) in courseData" :key="'c-'+i">
        <el-switch
          v-model="semesterCourse.filter(item2 => item2.course == item.course_id)[0]['status']"
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
      profession: this.$route.query.pro,
      semester: this.$route.query.sem,
      courseData: [],
      value: "",
      loading: false,
      
      semesterCourse: [],
    }
  },
  created(){
    this.getCourse();
    this.getSemesterCourse();
    $.each(this.courseData,(i, v)=>{ 
      if(this.semesterCourse.filter(item=>item.course == v.course_id).length == 0){
        this.semesterCourse.push({
          'course': v.course_id,
          "semester": this.semester,
          "school": this.$store.state.userSchool,
          'campus': this.$store.state.userCampus, 
          "department": this.$store.state.userDepartment,
          "skill": this.profession,
          'status': 0,
          "created_user": this.$store.state.userId,
        })
      }
    });
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getCourse(){
      this.loading = true;
      requestAjax({
        url: "/base/course.php",
        type: 'get',
        data:{
          type: "sel_course",
          sel: 'course_profession',
          val: this.profession,
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
    getSemesterCourse(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterCourse.php",
        type: 'get',
        data:{
          type: "sel_course",
          semester: this.semester,
          skill: this.profession,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterCourse = res;
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
      requestAjax({
        url: "/teach/SemesterCourse.php",
        type: 'post',
        data:{
          type: "set_course",
          arr: this.semesterCourse,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          $.each(res, (i, v)=>{ 
            if(v){
              return;
            }else{
              this.$message.error(this.semesterCourse[i]['course']+' 设置错误!');
            }
          });
          
          this.$message({
            message: '恭喜你，课程设置成功！',
            type: 'success'
          });
          
          // 日志写入
          let obj = {
            content: "学期开课设置 学期: " + this.semester,
            type: "修改记录",
            model: "学期开课模块",
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