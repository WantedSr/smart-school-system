<template>
  <div class="subpage">
    <div v-if="toDef">
      <fixed-assets></fixed-assets>
    </div>
    <div v-else-if="toAdd">
      <purchase></purchase>
    </div>
    <div v-else-if="toImp">
      <imp-assets></imp-assets>
    </div>
    <div v-else-if="toUpa">
      <upa-assets></upa-assets>
    </div>
  </div>
</template>

<script>
import FixedAssets from "./FixedAssets";
import Purchase from "./Purchase";
import UpaAssets from "./UpaAssets";
import ImpAssets from "./ImpAssets";
export default {
  data(){
    return{

    }
  },
  methods:{
    
  },
  computed:{
    toDef(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    toAdd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    toImp(){
      if(this.$route.query.type == 'imp'){
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
  components:{
    FixedAssets,
    ImpAssets,
    UpaAssets,
    Purchase,
  }
}
</script>

<style>

</style>