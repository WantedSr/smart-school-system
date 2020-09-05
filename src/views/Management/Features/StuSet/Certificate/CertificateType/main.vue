<template>
  
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-certificate></sel-certificate>
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
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  created(){
  },
  methods:{
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