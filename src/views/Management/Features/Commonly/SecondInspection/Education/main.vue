<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-education></sel-education>
    </div>
    <div v-if="Tadd">
      <add-education></add-education>
    </div>
    <div v-if="Tupa">
      <upa-education></upa-education>
    </div>
  </div>
</template>

<script>
import SelEducation from "./SelEducation";
import AddEducation from "./AddEducation";
import UpaEducation from "./UpaEducation";

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
    Timp(){
      if(this.$route.query.type == 'imp'){
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
    SelEducation,
    AddEducation,
    UpaEducation,
  },
  methods:{
  }
}
</script>

<style>

</style>