<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-option></sel-option>
    </div>
    <div v-if="Tadd">
      <add-option></add-option>
    </div>
    <div v-if="Tupa">
      <upa-option></upa-option>
    </div>
  </div>
</template>

<script>
import SelOption from "./SelOption";
import AddOption from "./AddOption";
import UpaOption from "./UpaOption";

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
    SelOption,
    AddOption,
    UpaOption,
  },
  methods:{
  }
}
</script>

<style>

</style>