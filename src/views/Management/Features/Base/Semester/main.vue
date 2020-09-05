<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-semester></sel-semester>
    </div>
    <div v-if="Tadd">
      <add-semester></add-semester>
    </div>
    <div v-if="Tupa">
      <upa-semester></upa-semester>
    </div>
  </div>
</template>

<script>
import SelSemester from "./SelSemester";
import AddSemester from "./AddSemester";
import UpaSemester from "./UpaSemester";

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
    SelSemester,
    AddSemester,
    UpaSemester,
  },
  methods:{
  }
}
</script>

<style>

</style>