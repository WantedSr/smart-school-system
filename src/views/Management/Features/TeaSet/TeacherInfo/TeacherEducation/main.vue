<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-teacher-education></sel-teacher-education>
    </div>
    <div v-if="toUpa">
      <upa-teacher-education></upa-teacher-education>
    </div>
  </div>
</template>

<script>
import SelTeacherEducation from "./SelTeacherEducation";
import UpaTeacherEducation from "./UpaTeacherEducation";
export default {
  data(){
    return{
    }
  },
  computed:{
    toDef(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
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
  components: {
    SelTeacherEducation,
    UpaTeacherEducation,
  }
}
</script>

<style>

</style>