<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-scrapped-type></sel-scrapped-type>
    </div>
    <div v-else-if="toAdd">
      <add-scrapped-type></add-scrapped-type>
    </div>
    <div v-else-if="toUpa">
      <upa-scrapped-type></upa-scrapped-type>
    </div>
  </div>
</template>

<script>
import SelScrappedType from "./SelScrappedType";
import AddScrappedType from "./AddScrappedType";
import UpaScrappedType from "./UpaScrappedType";
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
    SelScrappedType,
    AddScrappedType,
    UpaScrappedType,
  }
}
</script>

<style>

</style>