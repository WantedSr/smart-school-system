<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <add-alter-recording></add-alter-recording>
    </div>
    <div v-if="Tadd">
      <sel-alter-recording></sel-alter-recording>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import AddAlterRecording from "./AlterRecording";
import SelAlterRecording from "./SelAlterRecording";

export default {
  data(){
    return{
    }
  },
  created(){
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    Tadd(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
  },
  components:{
    SelAlterRecording,
    AddAlterRecording,
  },
  methods:{

  }
}
</script>

<style>

</style>