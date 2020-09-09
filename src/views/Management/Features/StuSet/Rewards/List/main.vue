<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-list></sel-list>
    </div>
    <div v-if="Tadd">
      <add-list></add-list>
    </div>
    <div v-if="Tupa">
      <upa-list></upa-list>
    </div>
    <div v-if="Timp">
      <imp-list></imp-list>
    </div>
  </div>
</template>

<script>
import SelList from "./SelList";
import AddList from "./AddList";
import UpaList from "./UpaList";
import ImpList from "./ImpList";

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
    SelList,
    AddList,
    UpaList,
    ImpList,
  },
  methods:{
  }
}
</script>

<style>

</style>