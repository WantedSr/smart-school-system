<template>
  <div class="subpage" v-if="campusData != ''">
    <div v-if="Tdefault">
      <sel-skill @getDep="getDepartment" :departmentData="departmentData" :campusData="campusData"></sel-skill>
    </div>
    <div v-if="Tadd">
      <add-skill @getDep="getDepartment" :departmentData="departmentData" :campusData="campusData"></add-skill>
    </div>
    <div v-if="Tupa">
      <upa-skill @getDep="getDepartment" :departmentData="departmentData" :campusData="campusData"></upa-skill>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelSkill from "./SelSkill";
import AddSkill from "./AddSkill";
import UpaSkill from "./UpaSkill";

export default {
  data(){
    return{
      departmentData: '',
      campusData: '',
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getCampus();
    // this.getDepartment('all');
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
    Timport(){
      if(this.$route.query.type == 'import'){
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
    SelSkill,
    AddSkill,
    UpaSkill,
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
        async: false,
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
  }
}
</script>

<style>

</style>