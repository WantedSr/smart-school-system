<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-form></sel-form>
    </div>
    <div v-else-if="toAdd">
      <add-form></add-form>
    </div>
    <div v-else-if="toUpa">
      <upa-form></upa-form>
    </div>
    <div v-else-if="toSelPro">
      <sel-process></sel-process>
    </div>
    <div v-else-if="toSetPro">
      <set-process></set-process>
    </div>
  </div>
</template>

<script>
import SelForm from "./SelForm";
import AddForm from "./AddForm";
import UpaForm from "./UpaForm";
import SelProcess from "./SelProcess";
import SetProcess from "./SetProcess";
export default {
  data(){
    return{
    }
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
    },
    toSelPro(){
      if(this.$route.query.type == 'sel_pro'){
        return true;
      }
      return false;
    },
    toSetPro(){
      if(this.$route.query.type == 'set_pro'){
        return true;
      }
      return false;
    },
  },
  components:{
    SelForm,
    AddForm,
    UpaForm,
    SelProcess,
    SetProcess,
  },
}
</script>

<style>

</style>