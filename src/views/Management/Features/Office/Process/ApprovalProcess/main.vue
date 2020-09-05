<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-approval-process></sel-approval-process>
    </div>
    <div v-else-if="toAdd">
      <add-approval-process></add-approval-process>
    </div>
    <div v-else-if="toUpa">
      <upa-approval-process></upa-approval-process>
    </div>
    <div v-else-if="toSet">
      <set-approval-process></set-approval-process>
    </div>
  </div>
</template>

<script>
import SelApprovalProcess from "./SelApprovalProcess";
import AddApprovalProcess from "./AddApprovalProcess";
import UpaApprovalProcess from "./UpaApprovalProcess";
import SetApprovalProcess from "./SetApprovalProcess";
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
    toSet(){
      if(this.$route.query.type == 'set'){
        return true;
      }
      return false;
    },
  },
  components:{
    SelApprovalProcess,
    AddApprovalProcess,
    UpaApprovalProcess,
    SetApprovalProcess,
  },
  methods:{
  }
}
</script>

<style>

</style>