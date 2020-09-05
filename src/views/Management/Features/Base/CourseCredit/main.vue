<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-course-credit></sel-course-credit>
    </div>
    <div v-if="Tadd">
      <add-course-credit></add-course-credit>
    </div>
    <div v-if="Tupa">
      <upa-course-credit></upa-course-credit>
    </div>
  </div>
</template>

<script>
import SelCourseCredit from "./SelCourseCredit";
import AddCourseCredit from "./AddCourseCredit";
import UpaCourseCredit from "./UpaCourseCredit";

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
    SelCourseCredit,
    AddCourseCredit,
    UpaCourseCredit,
  },
  methods:{
  }
}
</script>

<style>

</style>