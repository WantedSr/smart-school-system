<template>
  <div class="message">
    <el-row :gutter="40">
      <el-col class="messList" :lg="18" :xs="24" style="padding: 0">
        <div class="message">
          <mess-list></mess-list>
        </div>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import MessList from "./MessList";
export default {
  data() {
    return {
    }
  },
  components:{
    MessList,
  },
  computed:{
  },
  methods: {
  }
}
</script>

<style>
  .message{
    margin: 0 auto;
    width: 95%;
  }
  .messList{
    margin-left: 12.5%;
  }
  @media screen and (max-width:768px){
    .messList{
      margin: 0;
    }
  }
</style>
