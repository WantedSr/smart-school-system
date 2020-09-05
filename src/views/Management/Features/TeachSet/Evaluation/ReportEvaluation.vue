<template>
  <div class="classCourse">
    <div v-if="choose">
      <sel-report-evaluation></sel-report-evaluation>
    </div>
    <div v-if="toUpa">
      <upa-report-evaluation></upa-report-evaluation>
    </div>
  </div>
</template>

<script>
import SelReportEvaluation from "./SelReportEvaluation";
import UpaReportEvaluation from "./UpaReportEvaluation";
export default {
  data(){
    return{
    }
  },
  computed:{
    choose(){
      if(this.$route.query.type == undefined || this.$route.query.type == 'sel'){
        return true;
      }
    },
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
    }
  },
  methods:{
  },
  components:{
    SelReportEvaluation,
    UpaReportEvaluation,
  }
}
</script>

<style>

</style>