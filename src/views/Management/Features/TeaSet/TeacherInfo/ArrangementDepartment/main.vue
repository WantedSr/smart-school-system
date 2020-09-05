<template>
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-arrangement-department :depData="depData"></sel-arrangement-department>
    </div>
    <div v-if="toUpa">
      <upa-arrangement-department :depData="depData"></upa-arrangement-department>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import SelArrangementDepartment from "./SelArrangementDepartment";
import UpaArrangementDepartment from "./UpaArrangementDepartment";
export default {
  data(){
    return{
      loading: false,
      depData: [],
    }
  },
  computed:{
    toDef(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getDep();
  },
  methods:{
    getDep(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
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
  components: {
    SelArrangementDepartment,
    UpaArrangementDepartment,
  }
}
</script>

<style>

</style>