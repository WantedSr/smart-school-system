<template>
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-organization></sel-organization>
    </div>
    <div v-else-if="toAdd">
      <add-organization :depData="depData"></add-organization>
    </div>
    <div v-else-if="toUpa">
      <upa-organization :depData="depData"></upa-organization>
    </div>
    <div v-else-if="toSet">
      <set-allow :depData="depData"></set-allow>
    </div>
    <div v-else-if="toResponsible">
      <set-responsible></set-responsible>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import SelOrganization from "./SelOrganization";
import AddOrganization from "./AddOrganization";
import UpaOrganization from "./UpaOrganization";
import SetAllow from "./SetAllow";
import SetResponsible from "./SetResponsible";
export default {
  data(){
    return{
      sel:{
        semester: "",
        department: "",
        class: "",
      },
      n: this.$route.query.n,
      loading: false,

      depData: [],
    }
  },
  created(){
    this.getDep();
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
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
    toSet(){
      if(this.$route.query.type == 'set'){
        return true;
      }
      return false;
    },
    toResponsible(){
      if(this.$route.query.type == 'responsible'){
        return true;
      }
      return false;
    },
  },
  components:{
    SelOrganization,
    AddOrganization,
    UpaOrganization,
    SetAllow,
    SetResponsible,
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
  }
}
</script>

<style>

</style>