<template>
  <div class="classCourse">
    <div v-if="!choose">
      <sel-class-schedule></sel-class-schedule>
    </div>
    <div v-else>
      <set-class-schedule></set-class-schedule>
    </div>
  </div>
</template>

<script>
import SelClassSchedule from "./SelClassSchedule";
import SetClassSchedule from "./SetClassSchedule";
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
    SelClassSchedule,
    SetClassSchedule,
  }
}
</script>

<style>

</style>