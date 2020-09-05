<template>
  <div class="semesterCourse">
    <div v-if="!choose">
      <sel-semester-course></sel-semester-course>
    </div>
    <div v-if="choose">
      <set-semester-course></set-semester-course>
    </div>
  </div>
</template>

<script>
import SelSemesterCourse from "./SelSemesterCourse";
import SetSemesterCourse from "./SetSemesterCourse";
export default {
  data(){
    return{
      checkList: [],
    }
  },
  methods:{
  },
  computed:{
    choose(){
      if(this.$route.query.pro != undefined && this.$route.query.pro != null){
        if(this.$route.query.sem != undefined && this.$route.query.sem != null){
          return true;
        }
      }
      return false;
    }
  },
  components:{
    SelSemesterCourse,
    SetSemesterCourse,
  }
}
</script>

<style>

</style>