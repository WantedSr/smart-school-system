<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-grade></sel-grade>
    </div>
    <div v-if="Tadd">
      <add-grade></add-grade>
    </div>
    <div v-if="Tupa">
      <upa-grade></upa-grade>
    </div>
  </div>
</template>

<script>
import SelGrade from "./SelGrade";
import AddGrade from "./AddGrade";
import UpaGrade from "./UpaGrade";

export default {
  data(){
    return{
    }
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    Tadd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    Timport(){
      if(this.$route.query.type == 'import'){
        return true;
      }
      return false;
    },
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  components:{
    SelGrade,
    AddGrade,
    UpaGrade,
  },
  methods:{
  }
}
</script>

<style>

</style>