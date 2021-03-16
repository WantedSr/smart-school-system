<template>
  
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-certificate @selSkill="getSkill" @selClass="getClass" :depData="depData" :skillData="skillData" :gradeData="gradeData" :classData="classData"></sel-certificate>
    </div>
    <div v-else-if="toAdd">
      <add-certificate></add-certificate>
    </div>
    <div v-else-if="toUpa">
      <upa-certificate></upa-certificate>
    </div>
  </div>

</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import SelCertificate from './SelCertificate';
import addCertificate from './AddCertificate';
import upaCertificate from './UpaCertificate';
export default {
  data(){
    return{
      loading: false,
      depData: [],
      skillData: [],
      gradeData: [],
      classData: [],

      provinceData:[],
      addCityData:[],
      addAreaData:[],
      regCityData:[],
      regAreaData:[],

      statusData: [],
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
    }
  },
  created(){
    this.getDep();
    this.getGrade();
    this.getStatus();
    this.selProvince();
  },
  methods:{
    getStatus(){
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/schoolStatus.php",
        data:{
          type: "sel_status",
          selobj: {
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.statusData = res;
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
    getClass(dep){
      let selobj = {
        campus: this.$store.state.userCampus,
        department: dep,
      };
      requestAjax({
        type: "get",
        url: "/base/class.php",
        async: false,
        data:{
          type: 'sel_class',
          selobj: selobj,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.classData = res;
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
    selProvince(){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_provinces",
        },
        success:(res)=>{
          this.provinceData = JSON.parse(res);
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    selCity(v,t){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_cities",
          provinceId: v,
        },
        success:(res)=>{
          if(t == 'add'){
            this.addCityData = JSON.parse(res);
          }
          else if(t == 'reg'){
            this.regCityData = JSON.parse(res);
          }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    selArea(v,t){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_areas",
          cityId: v,
        },
        success:(res)=>{
          // console.log(res);
          if(t == 'add'){
            this.addAreaData = JSON.parse(res);
          }else if(t == 'reg'){
            this.regAreaData = JSON.parse(res);
          }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
  },
  components:{
    SelCertificate,
    addCertificate,
    upaCertificate,
  }
}
</script>

<style>

</style>