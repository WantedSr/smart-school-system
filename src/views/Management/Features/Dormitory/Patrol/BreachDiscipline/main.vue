<template>
  <div class="subpage" v-if="schoolData != ''">
    <div v-if="Tdefault">
      <sel-breach-disipline></sel-breach-disipline>
    </div>
    <div v-if="Tadd">
      <add-breach-disipline></add-breach-disipline>
    </div>
    <div v-if="Tupa">
      <upa-breach-disipline></upa-breach-disipline>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelBreachDisipline from "./SelBreachDisipline";
import AddBreachDisipline from "./AddBreachDisipline";
import UpaBreachDisipline from "./UpaBreachDisipline";

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
    SelBreachDisipline,
    AddBreachDisipline,
    UpaBreachDisipline,
  },
  methods:{
  }
}
</script>

<style>

</style>