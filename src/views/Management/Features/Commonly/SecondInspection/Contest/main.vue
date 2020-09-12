<template>
  <div class="subpage" v-if="teacherData.length">
    <div v-if="Tdefault">
      <sel-contest :teacherData="teacherData"></sel-contest>
    </div>
    <div v-if="Tadd">
      <add-contest :teacherData="teacherData"></add-contest>
    </div>
    <div v-if="Tupa">
      <upa-contest :teacherData="teacherData"></upa-contest>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelContest from "./SelContest";
import AddContest from "./AddContest";
import UpaContest from "./UpaContest";

export default {
  data(){
    return{
      teacherData: [],
    }
  },
  created(){
    this.getTeacher();
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
    SelContest,
    AddContest,
    UpaContest,
  },
  methods:{
    getTeacher(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "userid,username,department",
          selobj: {
            campus: this.$store.state.userCampus,
            type: '1',
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.teacherData = res;
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
  }
}
</script>

<style>

</style>