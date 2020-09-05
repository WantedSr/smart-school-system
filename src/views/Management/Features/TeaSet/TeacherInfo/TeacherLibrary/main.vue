<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-teacher-info></sel-teacher-info>
    </div>
    <div v-if="toUpa">
      <upa-teacher-info></upa-teacher-info>
    </div>
  </div>
</template>

<script>
import SelTeacherInfo from "./SelTeacherInfo";
import UpaTeacherInfo from "./UpaTeacherInfo";
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
    SelTeacherInfo,
    UpaTeacherInfo,
  }
}
</script>

<style>

</style>