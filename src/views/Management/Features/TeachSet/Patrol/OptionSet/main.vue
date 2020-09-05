<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-option></sel-option>
    </div>
    <div v-if="ToptionList">
      <sel-option-list></sel-option-list>
    </div>
    <div v-if="TupaOption">
      <upa-option></upa-option>
    </div>
    <div v-if="TupaOptionList">
      <upa-option-list></upa-option-list>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelOption from "./SelOption";
import SelOptionList from "./SelOptionList";
import UpaOption from "./UpaOption";
import UpaOptionList from "./UpaOptionList";

export default {
  data(){
    return{
      departmentData: "",
      professionData: "",
      gradeData: "",
    }
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'option' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    TupaOption(){
      if(this.$route.query.type == 'upa_option'){
        return true;
      }
      return false;
    },
    ToptionList(){
      if(this.$route.query.type == 'option_list'){
        return true;
      }
      return false;
    },
    TupaOptionList(){
      if(this.$route.query.type == 'upa_option_list'){
        return true;
      }
      return false;
    }
  },
  components:{
    SelOption,
    SelOptionList,
    UpaOption,
    UpaOptionList,
  },
  created(){
  },
  methods:{
  }
}
</script>

<style>

</style>