<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-class-room @getDep="getDepartment" @getBud="getBuild" :campusData="campusData" :departmentData="departmentData" :buildData="buildData"></sel-class-room>
    </div>
    <div v-if="Tadd">
      <add-class-room @getDep="getDepartment" @getBud="getBuild" :campusData="campusData" :departmentData="departmentData" :buildData="buildData"></add-class-room>
    </div>
    <div v-if="Tupa">
      <upa-class-room @getDep="getDepartment" @getBud="getBuild" :campusData="campusData" :departmentData="departmentData" :buildData="buildData"></upa-class-room>
    </div>
    <div v-if="Timp">
      <imp-class-room></imp-class-room>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelClassRoom from "./SelClassRoom";
import AddClassRoom from "./AddClassRoom";
import UpaClassRoom from "./UpaClassRoom";
import ImpClassRoom from "./ImpClassRoom";

export default {
  data(){
    return{
      campusData: '',
      departmentData: '',
      buildData: '',
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getCampus();
    this.buildData = '';
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
    SelClassRoom,
    AddClassRoom,
    UpaClassRoom,
    ImpClassRoom,
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
          sel: campus == 'all' ? 'school' : 'campus',
          val: campus == 'all' ? this.$store.state.userSchool : campus,
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
    getBuild(dep,cam){
      let sel,val;
      if(dep == 'all'){
        if(cam == 'all'){
          sel = 'build_school';
          val = this.$store.state.userSchool;
        }else{
          sel = 'build_campus';
          val = cam;
        }
      }else{
        sel = 'build_department';
        val = dep;
      }
      requestAjax({
        url: "/base/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          sel: sel,
          val: val,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.buildData = res;
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
  }
}
</script>

<style>

</style>