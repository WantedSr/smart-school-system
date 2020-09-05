<template>
  <div class="subpage" v-loading="loading">

    <div v-if="Tdefault">
      <sel-dorm-room :buildData="buildData"></sel-dorm-room>
    </div>

    <div v-if="Tadd">
      <add-dorm-room :buildData="buildData"></add-dorm-room>
    </div>

    <div v-if="Timport">
      <imp-dorm-room :buildData="buildData"></imp-dorm-room>
    </div>

    <div v-if="Tupa">
      <upa-dorm-room :buildData="buildData"></upa-dorm-room>
    </div> 

  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import AddDormRoom from "./AddDormRoom";
import SelDormRoom from "./SelDormRoom";
import ImpDormRoom from "./ImpDormRoom";
import UpaDormRoom from "./UpaDormRoom";
export default {
  data(){
    return{
      n: this.$route.query.n,
      loading: false,
      buildData: [],
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getDormBuild("*",{
      'campus':this.$store.state.userCampus,
    })
  },
  components:{
    AddDormRoom,
    SelDormRoom,
    UpaDormRoom,
    ImpDormRoom,
  },
  methods:{
    getDormBuild(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          col: col,
          selobj: selobj,
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
  .sel_add{
    padding: 0 36px;
  }
  .upload-demo{
    margin-bottom: 24px;
  }
</style>