<template>
  <div class="classCourse">
    <div v-if="!choose">
      <sel-semester-class></sel-semester-class>
    </div>
    <div v-else>
      <set-semester-class></set-semester-class>
    </div>
  </div>
</template>

<script>
import SelSemesterClass from "./SelSemesterClass";
import SetSemesterClass from "./SetSemesterClass";
export default {
  data(){
    return{
    }
  },
  computed:{
    choose(){
      if(this.$route.query.sem != undefined && this.$route.query.sem != null){
        return true;
      }
      return false;
    }
  },
  methods:{
  },
  components:{
    SelSemesterClass,
    SetSemesterClass,
  }
}
</script>

<style>

</style>