<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-borrow></sel-borrow>
    </div>
    <div v-else-if="toAdd">
      <add-borrow></add-borrow>
    </div>
    <div v-else-if="toImp">
      <imp-borrow></imp-borrow>
    </div>
    <div v-else-if="toUpa">
      <upa-borrow></upa-borrow>
    </div>
  </div>
</template>

<script>
import SelBorrow from "./SelBorrow";
import AddBorrow from "./AddBorrow";
import ImpBorrow from "./ImpBorrow";
import UpaBorrow from "./UpaBorrow";
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
    SelBorrow,
    AddBorrow,
    ImpBorrow,
    UpaBorrow,
  }
}
</script>

<style>

</style>