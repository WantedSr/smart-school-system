<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-teacher-qualification></sel-teacher-qualification>
    </div>
    <div v-if="toUpa">
      <upa-teacher-qualification></upa-teacher-qualification>
    </div>
  </div>
</template>

<script>
import SelTeacherQualification from "./SelTeacherQualification";
import UpaTeacherQualification from "./UpaTeacherQualification";
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
    onDel(){

    },
  },
  components: {
    SelTeacherQualification,
    UpaTeacherQualification,
  }
}
</script>

<style>

</style>