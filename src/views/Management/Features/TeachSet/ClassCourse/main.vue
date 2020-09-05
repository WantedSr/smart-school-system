<template>
  <div class="classCourse">
    <div v-if="!choose">
      <sel-class-course></sel-class-course>
    </div>
    <div v-else>
      <set-class-course></set-class-course>
    </div>
  </div>
</template>

<script>
import SelClassCourse from "./SelClassCourse";
import SetClassCourse from "./SetClassCourse";
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
    SelClassCourse,
    SetClassCourse,
  }
}
</script>

<style>

</style>