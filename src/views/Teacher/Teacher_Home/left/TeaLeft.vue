<template>
  <div class="left">
    <div class="leftBox">

      <my-work :show="work"></my-work>

      <today></today>

      <announcement :show="announcement"></announcement>

    </div>
  </div>
</template>

<script>
import MyWork from "./mywork";
import Announcement from "./announcement";
import Today from "./today";
export default {
  data(){
    return {
      show: JSON.parse(this.userData)['msgSet'].split(","),
    }
  },
  created(){
  },  
  computed:{
    work(){
      return this.show[0] == 1 ? true: false;
    },
    announcement(){
      return this.show[1] == 1 ? true: false;
    }
  },
  props:{
    userData:{
      type: String,
      require: true,
    }
  },
  components:{
    MyWork,
    Announcement,
    Today,
  }
}
</script>

<style>
  
</style>