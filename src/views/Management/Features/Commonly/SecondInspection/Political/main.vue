<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-political></sel-political>
    </div>
    <div v-if="Tadd">
      <add-political></add-political>
    </div>
    <div v-if="Tupa">
      <upa-political></upa-political>
    </div>
  </div>
</template>

<script>
import SelPolitical from "./SelPolitical";
import AddPolitical from "./AddPolitical";
import UpaPolitical from "./UpaPolitical";

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
    Timp(){
      if(this.$route.query.type == 'imp'){
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
    SelPolitical,
    AddPolitical,
    UpaPolitical,
  },
  methods:{
  }
}
</script>

<style>

</style>