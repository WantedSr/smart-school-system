<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-layout></sel-layout>
    </div>
    <div v-if="Tupa">
      <upa-layout></upa-layout>
    </div>
  </div>
</template>

<script>
import SelLayout from "./SelLayout";
import UpaLayout from "./UpaLayout";

export default {
  data(){
    return{
      departmentData: "",
      professionData: "",
      gradeData: "",
    }
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
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
    SelLayout,
    UpaLayout,
  },
  created(){
  },
  methods:{
  }
}
</script>

<style>

</style>