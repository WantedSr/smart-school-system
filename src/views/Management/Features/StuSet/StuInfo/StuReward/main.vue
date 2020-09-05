<template>
  
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-reward></sel-reward>
    </div>
    <div v-else-if="toAdd">
      <add-reward></add-reward>
    </div>
    <div v-else-if="toUpa">
      <upa-reward></upa-reward>
    </div>
  </div>

</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import SelReward from './selReward';
import addReward from './addReward';
import upaReward from './upaReward';
export default {
  data(){
    return{
      loading: false,
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
    this.$store.commit("getUser");
  },
  methods:{
    
  },
  components:{
    SelReward,
    addReward,
    upaReward,
  }
}
</script>

<style>

</style>