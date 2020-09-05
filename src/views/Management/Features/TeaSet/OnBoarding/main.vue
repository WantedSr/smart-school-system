<template>
  <div class="subpage">
    <add-tea-info></add-tea-info>
  </div>
</template>

<script>
import AddTeaInfo from "./AddTeaInfo";
import {requestAjax} from "network/request_ajax"; 
export default {
  data(){
    return{

    }
  },
  created(){
  },
  computed:{
    add1(){
      if(this.$route.query.board == undefined || this.$route.query.board == '1'){
        return true;
      }
      return false;
    },
    add2(){
      if(this.$route.query.board == '2'){
        return true;
      }
      return false;
    },
  },
  methods:{ 
  },
  components:{
    AddTeaInfo,
  }
}
</script>

<style>

</style>