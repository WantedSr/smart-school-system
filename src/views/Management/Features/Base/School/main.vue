<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-school></sel-school>
    </div>
    <div v-if="Tadd">
      <add-school></add-school>
    </div>
    <div v-if="Tupa">
      <upa-school></upa-school>
    </div>
  </div>
</template>

<script>
import SelSchool from "./SelSchool";
import AddSchool from "./AddSchool";
import UpaSchool from "./UpaSchool";

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
    SelSchool,
    AddSchool,
    UpaSchool,
  },
  methods:{
  }
}
</script>

<style>

</style>