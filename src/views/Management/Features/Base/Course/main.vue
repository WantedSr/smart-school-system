<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-course @getPro="getProfession" :departmentData="departmentData" :professionData="professionData"></sel-course>
    </div>
    <div v-if="Tadd">
      <add-course @getPro="getProfession" @getDep="getDepartment" :courseType="courseType" :campusData="campusData" :departmentData="departmentData" :professionData="professionData"></add-course>
    </div>
    <div v-if="Tupa">
      <upa-course @getPro="getProfession" @getDep="getDepartment" :courseType="courseType" :campusData="campusData" :departmentData="departmentData" :professionData="professionData"></upa-course>
    </div>
    <div v-if="Timp">
      <imp-course></imp-course>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelCourse from "./SelCourse";
import AddCourse from "./AddCourse";
import UpaCourse from "./UpaCourse";
import ImpCourse from "./ImpCourse";

export default {
  data(){
    return{
      campusData: '',
      departmentData: '',
      professionData: '',
      courseType: '',
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getCampus();
    this.getDepartment();
    this.getCourseType();
    this.professionData = '';
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    Tadd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    Timp(){
      if(this.$route.query.type == 'imp'){
        return true;
      }
      return false;
    },
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  components:{
    SelCourse,
    AddCourse,
    UpaCourse,
    ImpCourse,
  },
  methods:{
    getCourseType(){
      requestAjax({
        url: "/base/courseCredit.php",
        type: 'get',
        data:{
          type: "sel_course_credit",
          selobj: {
            status: '1',
            campus: this.$store.state.userCampus,
          }
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.courseType = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getCampus(){
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus", 
          sel: "campus_school",
          val: this.$store.state.userSchool,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.campusData = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getDepartment(campus){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: campus ? campus : this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.departmentData = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getProfession(dep){
      requestAjax({
        url: "/base/profession.php",
        type: 'get',
        data:{
          type: "sel_profession",
          sel: dep != 'all' ? 'skill_department' : 'skill_campus',
          val: dep != 'all' ? dep : this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.professionData = res;
          // console.log(res)
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    }
  }
}
</script>

<style>

</style>