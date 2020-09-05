<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-class @getPro="getProfession" :departmentData="departmentData" :professionData="professionData" :gradeData="gradeData"></sel-class>
    </div>
    <div v-if="Tadd">
      <add-class @getDep="getDepartment" @getPro="getProfession" :campusData="campusData" :departmentData="departmentData" :professionData="professionData" :gradeData="gradeData"></add-class>
    </div>
    <div v-if="Tupa">
      <upa-class @getDep="getDepartment" @getPro="getProfession" :campusData="campusData" :departmentData="departmentData" :professionData="professionData" :gradeData="gradeData"></upa-class>
    </div>
    <div v-if="Timp">
      <imp-class></imp-class>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelClass from "./SelClass";
import AddClass from "./AddClass";
import UpaClass from "./UpaClass";
import ImpClass from "./ImpClass";

export default {
  data(){
    return{
      departmentData: "",
      professionData: "",
      gradeData: "",
    }
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
    SelClass,
    AddClass,
    UpaClass,
    ImpClass,
  },
  created(){
    this.$store.commit("getUser");
    this.getCampus();
    this.getDepartment();
    this.getGrade();
    this.professionData = '';
  },
  methods:{
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
    },
    getGrade(){
      requestAjax({
        url: "/base/grade.php",
        type: 'get',
        data:{
          type: "sel_grade",
          selobj:{
            'campus': this.$store.state.userCampus,
          },
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.gradeData = res;
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