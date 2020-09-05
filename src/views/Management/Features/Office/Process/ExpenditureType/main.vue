<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-expenditure-type></sel-expenditure-type>
    </div>
    <div v-else-if="toAdd">
      <add-expenditure-type></add-expenditure-type>
    </div>
    <div v-else-if="toUpa">
      <upa-expenditure-type></upa-expenditure-type>
    </div>
  </div>
</template>

<script>
import SelExpenditureType from "./SelExpenditureType";
import AddExpenditureType from "./AddExpenditureType";
import UpaExpenditureType from "./UpaExpenditureType";
export default {
  data(){
    return{
      sel:{
        semester: "",
        department: "",
        class: "",
      },
      n: this.$route.query.n,
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
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
  },
  components:{
    SelExpenditureType,
    AddExpenditureType,
    UpaExpenditureType,
  },
  methods:{
  }
}
</script>

<style>

</style>