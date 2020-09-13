<template>
  <div class="subpage" v-if="departmentData.length >= 0">
    <div v-if="Tdefault">
      <sel-affairs></sel-affairs>
    </div>
    <div v-if="Tupa">
      <upa-affairs></upa-affairs>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelAffairs from "./SelAffairs";
import UpaAffairs from "./UpaAffairs";

export default {
  data(){
    return{
      departmentData: [],
    }
  },
  created(){
  },
  methods:{
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
    SelAffairs,
    UpaAffairs,
  },
}
</script>

<style>

</style>