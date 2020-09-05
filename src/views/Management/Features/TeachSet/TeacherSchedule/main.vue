<template>
  <div class="classCourse">
    <div v-if="!choose">
      <sel-tea-schedule></sel-tea-schedule>
    </div>
    <div v-else>
      <set-tea-schedule></set-tea-schedule>
    </div>
  </div>
</template>

<script>
import SelTeaSchedule from "./SelTeaSchedule";
import SetTeaSchedule from "./SetTeaSchedule";
export default {
  data(){
    return{
    }
  },
  computed:{
    choose(){
      if(this.$route.query.class != undefined && this.$route.query.class != null){
        if(this.$route.query.sem != undefined && this.$route.query.sem != null){
          return true;
        }
      }
      return false;
    }
  },
  methods:{
  },
  components:{
    SelTeaSchedule,
    SetTeaSchedule,
  }
}
</script>

<style>

</style>