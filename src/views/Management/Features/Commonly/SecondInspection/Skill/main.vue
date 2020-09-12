<template>
  <div class="subpage" v-if="departmentData.length >= 0">
    <div v-if="Tdefault">
      <sel-skill :departmentData="departmentData"></sel-skill>
    </div>
    <div v-if="Tadd">
      <add-skill :departmentData="departmentData"></add-skill>
    </div>
    <div v-if="Tupa">
      <upa-skill :departmentData="departmentData"></upa-skill>
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
          // this.departmentData = res;
          // console.log(res);
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
    SelSkill,
    AddSkill,
    UpaSkill,
  },
}
</script>

<style>

</style>