<template>
  <div class="center">
    <div class="centerBox">

      <!-- <news></news> -->

      <schudule :cls="cls"></schudule>

    </div>
  </div>
</template>

<script>
import News from "./news";
import Schudule from "./schudule";
export default {
  data(){
    return {
      cls: "",
    }
  },
  created(){
    this.cls = JSON.parse(this.userData)['class_id'];
  },
  props:{
    userData:{
      type: String,
      require: true,
    }
  },
  components:{
    News,
    Schudule,
  }
}
</script>

<style>

</style>