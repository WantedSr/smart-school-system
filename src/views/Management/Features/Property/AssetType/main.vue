<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-asset-type></sel-asset-type>
    </div>
    <div v-else-if="toAdd">
      <add-asset-type></add-asset-type>
    </div>
    <div v-else-if="toUpa">
      <upa-asset-type></upa-asset-type>
    </div>
  </div>
</template>

<script>
import SelAssetType from "./SelAssetType";
import AddAssetType from "./AddAssetType";
import UpaAssetType from "./UpaAssetType";
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
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  components:{
    SelAssetType,
    AddAssetType,
    UpaAssetType,
  }
}
</script>

<style>

</style>