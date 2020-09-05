<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-scrapped></sel-scrapped>
    </div>
    <div v-else-if="toAdd">
      <add-scrapped></add-scrapped>
    </div>
    <div v-else-if="toImp">
      <imp-scrapped></imp-scrapped>
    </div>
    <div v-else-if="toUpa">
      <upa-scrapped></upa-scrapped>
    </div>
  </div>
</template>

<script>
import SelScrapped from './SelScrapped';
import AddScrapped from './AddScrapped';
import UpaScrapped from './UpaScrapped';
import ImpScrapped from './ImpScrapped';
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
    SelScrapped,
    AddScrapped,
    UpaScrapped,
    ImpScrapped,
  }
}
</script>

<style>

</style>