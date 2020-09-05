<template>
  <div class="subpage" v-if="schoolData != ''">
    <div v-if="Tdefault">
      <sel-dormitory :schoolData="schoolData"></sel-dormitory>
    </div>
    <div v-if="Tadd">
      <add-dormitory :schoolData="schoolData"></add-dormitory>
    </div>
    <div v-if="Tupa">
      <upa-dormitory :schoolData="schoolData"></upa-dormitory>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelDormitory from "./SelDormitory";
import AddDormitory from "./AddDormitory";
import UpaDormitory from "./UpaDormitory";

export default {
  data(){
    return{
      schoolData: '',
    }
  },
  created(){
    requestAjax({
      url: "/base/school.php",
      type: 'get',
      data:{
        type: "sel_school",
      },
      async: true,
      success:(res)=>{
        res = JSON.parse(res);
        this.schoolData = res;
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
  components:{
    SelDormitory,
    AddDormitory,
    UpaDormitory,
  },
  methods:{
  }
}
</script>

<style>

</style>