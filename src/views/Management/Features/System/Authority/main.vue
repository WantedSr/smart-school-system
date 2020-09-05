<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-authority></sel-authority>
    </div>
    <div v-else-if="toAdd">
      <add-authority></add-authority>
    </div>
    <div v-else-if="toUpa">
      <upa-authority></upa-authority>
    </div>
  </div>
</template>

<script>
import SelAuthority from "./SelAuthority";
import AddAuthority from "./AddAuthority";
import UpaAuthority from "./UpaAuthority";
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
  },
  methods:{

  },
  created(){
    this.$store.commit("getUser");
  },
  components:{
    SelAuthority,
    AddAuthority,
    UpaAuthority,
  }
}
</script>

<style>

</style>