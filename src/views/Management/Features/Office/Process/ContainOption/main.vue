<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-contain-option></sel-contain-option>
    </div>
    <div v-else-if="toAdd">
      <add-contain-option></add-contain-option>
    </div>
    <div v-else-if="toUpa">
      <upa-contain-option></upa-contain-option>
    </div>
  </div>
</template>

<script>
import SelContainOption from "./SelContainOption";
import AddContainOption from "./AddContainOption";
import UpaContainOption from "./UpaContainOption";
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
    SelContainOption,
    AddContainOption,
    UpaContainOption,
  },
  methods:{
  }
}
</script>

<style>

</style>