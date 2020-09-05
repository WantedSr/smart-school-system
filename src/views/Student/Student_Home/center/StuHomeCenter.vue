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
  props:{
    userData:{
      type: String,
      require: true,
    }
  },
  created(){
    this.cls = JSON.parse(this.userData)['class_id'];
  },
  components:{
    News,
    Schudule,
  }
}
</script>

<style>

</style>