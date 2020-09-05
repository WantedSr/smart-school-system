<template>
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-user :depData="depData" :authData="authData"></sel-user>
    </div>
    <div v-else-if="toAdd">
      <add-user @selCam="getCam" @selDep="getDep" :schData="schData" :camData="camData" :depData="depData" :authData="authData"></add-user>
    </div>
    <div v-else-if="toUpa">
      <upa-user @selCam="getCam" @selDep="getDep" :schData="schData" :camData="camData" :depData="depData" :authData="authData"></upa-user>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelUser from "./SelUser";
import AddUser from "./AddUser";
import UpaUser from "./UpaUser";
export default {
  data(){
    return{
      depData: [],
      authData: [],
      schData: [],
      loading: false,
    }
  },
  computed:{
    toDef(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    toAdd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    toImp(){
      if(this.$route.query.type == 'imp'){
        return true;
      }
      return false;
    },
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
  },
  methods:{
    getAuth(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        url: "/system/authority.php",
        type: 'get',
        data:{
          type: "sel_authority",
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.authData = res;
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
    getSch(col="*",sel=null,val=null){
      this.loading = true;
      requestAjax({
        url: "/base/school.php",
        type: 'get',
        data:{
          type: "sel_school",
          sel: sel,
          val: val,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.schData = res;
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
    getCam(v){
      this.loading = true;
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus",
          col: "*",
          sel: 'campus_school',
          val: v,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.camData = res;
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
    getDep(v){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: v ? v : this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.depData = res;
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
  created(){
    this.$store.commit('getUser');
    this.getSch();
    this.getDep();
    this.getAuth();
  },
  components:{
    SelUser,
    AddUser,
    UpaUser,
  }
}
</script>

<style>

</style>