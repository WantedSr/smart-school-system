<template>
  <div class="subpage" v-if="departmentData.length >= 0">
    <div v-if="Tdefault">
      <sel-practice-place :departmentData="departmentData"></sel-practice-place>
    </div>
    <div v-if="Tadd">
      <add-practice-place :departmentData="departmentData"></add-practice-place>
    </div>
    <div v-if="Tupa">
      <upa-practice-place :departmentData="departmentData"></upa-practice-place>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelPracticePlace from "./SelPracticePlace";
import AddPracticePlace from "./AddPracticePlace";
import UpaPracticePlace from "./UpaPracticePlace";

export default {
  data(){
    return{
      departmentData: [],
    }
  },
  created(){
    this.getDepartment();
  },
  methods:{
    getDepartment(){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.departmentData = res;
          console.log(res);
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
    SelPracticePlace,
    AddPracticePlace,
    UpaPracticePlace,
  },
}
</script>

<style>

</style>