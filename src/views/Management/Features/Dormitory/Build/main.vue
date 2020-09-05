<template>
  <div class="dormitoryBuild">
    <div v-if="Tdefault">
      <sel-build></sel-build>
    </div>
    <div v-if="Tadd">
      <add-build @getDep="getDepartment" :departmentData="departmentData" :campusData="campusData"></add-build>
    </div>
    <div v-if="Tupa">
      <upa-build @getDep="getDepartment" :departmentData="departmentData" :campusData="campusData"></upa-build>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import AddBuild from "./AddBuild";
import SelBuild from "./SelBuild";
import UpaBuild from "./UpaBuild";
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
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
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
          sel: campus == 'all' ? null : 'campus',
          val: campus == 'all' ? null : campus,
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
  },
  components:{
    AddBuild,
    SelBuild,
    UpaBuild,
  }
}
</script>

<style>

</style>