<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-schedule></sel-schedule>
    </div>
    <div v-if="Tadd">
      <add-schedule></add-schedule>
    </div>
    <div v-if="Tupa">
      <upa-schedule></upa-schedule>
    </div>
  </div>
</template>

<script>
import SelSchedule from "./SelSchedule";
import AddSchedule from "./AddSchedule";
import UpaSchedule from "./UpaSchedule";

export default {
  data(){
    return{
    }
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
    Timport(){
      if(this.$route.query.type == 'import'){
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
    SelSchedule,
    AddSchedule,
    UpaSchedule,
  },
  methods:{
  }
}
</script>

<style>

</style>