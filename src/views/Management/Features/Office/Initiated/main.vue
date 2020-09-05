<template>
  <div class="subpage">
    <div v-if="toDef">
      <oa-sel></oa-sel>
    </div>
    <div v-if="toAdd">
      <oa-created></oa-created>
    </div>
  </div>
</template>

<script>
import OaCreated from "./OaCreated";
import OaSel from './OaSel';
export default {
  data(){
    return{
      
    }
  },
  computed:{
    toDef(){
      if(this.$route.query.act == '' || this.$route.query.act == undefined){
        return true;
      }
      return false;
    },
    toAdd(){
      if(this.$route.query.act == '123'){
        return true;
      }
      return false;
    },
  },
  components:{
    OaSel,
    OaCreated,
  }
}
</script>

<style>

</style>