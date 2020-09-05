<template>
  <div class="subpage" v-loading="loading">

    <div v-if="Tdefault">
      <sel-housemaster-arrangement @getDormBuild="getDormBuild" :semesterData="semesterData" :departmentData="departmentData" :buildData="buildData"></sel-housemaster-arrangement>
    </div>

    <div v-if="Tadd">
      <add-housemaster-arrangement @getDormBuild="getDormBuild" :semesterData="semesterData" :departmentData="departmentData" :buildData="buildData"></add-housemaster-arrangement>
    </div>

    <div v-if="Timport">
      <imp-housemaster-arrangement @getDormBuild="getDormBuild" :semesterData="semesterData" :departmentData="departmentData" :buildData="buildData"></imp-housemaster-arrangement>
    </div>

    <div v-if="Tupa">
      <upa-housemaster-arrangement @getDormBuild="getDormBuild" :semesterData="semesterData" :departmentData="departmentData" :buildData="buildData"></upa-housemaster-arrangement>
    </div> 

  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import AddHousemasterArrangement from "./AddHousemasterArrangement";
import SelHousemasterArrangement from "./SelHousemasterArrangement";
import ImpHousemasterArrangement from "./ImpHousemasterArrangement";
import UpaHousemasterArrangement from "./UpaHousemasterArrangement";
export default {
  data(){
    return{
      n: this.$route.query.n,
      loading: false,
      buildData: [],
      semesterData: [],
      departmentData: [],
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getSemester();
    this.getDepartment();
  },
  components:{
    AddHousemasterArrangement,
    SelHousemasterArrangement,
    UpaHousemasterArrangement,
    ImpHousemasterArrangement,
  },
  methods:{
    getSemester(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
          col: col,
          sel: sel,
          val: val,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.semesterData = res;
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
    getDepartment(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          col: col,
          sel: 'campus',
          val: this.$store.state.userCampus,
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
    getDormBuild(dep){
      this.loading = true;
      let obj = dep == 'all' ? {'campus':this.$store.state.userCampus,'type':'学生宿舍'} : {"department":dep,'type':'学生宿舍'};
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          col: "*",
          selobj: obj,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.buildData = res;
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

}
</script>

<style>
  .upload-demo{
    margin-bottom: 24px;
  }
</style>