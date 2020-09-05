<template>
  <div class="subpage" v-if="campusData != ''">
    <div v-if="Tdefault">
      <sel-department :campusData="campusData"></sel-department>
    </div>
    <div v-if="Tadd">
      <add-department :campusData="campusData"></add-department>
    </div>
    <div v-if="Tupa">
      <upa-department :campusData="campusData"></upa-department>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelDepartment from "./SelDepartment";
import AddDepartment from "./AddDepartment";
import UpaDepartment from "./UpaDepartment";

export default {
  data(){
    return{
      campusData: '',
    }
  },
  created(){
    this.$store.commit("getUser");
    return this.getCampus();
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
    SelDepartment,
    AddDepartment,
    UpaDepartment,
  },
  methods:{
    getCampus(){
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus",
          col: "*",
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
    }
  }
}
</script>

<style>

</style>