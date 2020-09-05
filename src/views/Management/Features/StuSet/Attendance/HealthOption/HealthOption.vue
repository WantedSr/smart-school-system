<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-option :modelData="modelData"></sel-option>
    </div>
    <div v-if="Tadd">
      <add-option :modelData="modelData"></add-option>
    </div>
    <div v-if="Tupa">
      <upa-option :modelData="modelData"></upa-option>
    </div>
    <div v-if="TdefaultList">
      <sel-option-list :modelData="modelData"></sel-option-list>
    </div>
    <div v-if="TaddList">
      <add-option-list :modelData="modelData"></add-option-list>
    </div>
    <div v-if="TupaList">
      <upa-option-list :modelData="modelData"></upa-option-list>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelOption from "./SelOption";
import AddOption from "./AddOption";
import UpaOption from "./UpaOption";
import SelOptionList from "./SelOptionList";
import AddOptionList from "./AddOptionList";
import UpaOptionList from "./UpaOptionList";

export default {
  data(){
    return{
      modelData: [],
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
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
    TdefaultList(){
      if(this.$route.query.type == 'sel_list' && this.$route.query.optionId != undefined && this.$route.query.optionId != ''){
        return true;
      }
      return false;
    },
    TaddList(){
      if(this.$route.query.type == 'add_list' && this.$route.query.optionId != undefined && this.$route.query.optionId != ''){
        return true;
      }
      return false;
    },
    TupaList(){
      if(this.$route.query.type == 'upa_list' && this.$route.query.optionId != undefined && this.$route.query.optionId != ''){
        return true;
      }
      return false;
    },
  },
  components:{
    SelOption,
    AddOption,
    UpaOption,
    SelOptionList,
    AddOptionList,
    UpaOptionList,
  },
  created(){
    this.getModel({
      campus: this.$store.state.userCampus,
    })
  },
  methods:{
    getModel(selobj=null){
      requestAjax({
        url: "/StuSet/HealthModel.php",
        type: 'get',
        data:{
          type: "sel_model",
          col: "*",
          selobj: selobj
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.modelData = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
  }
}
</script>

<style>

</style>