<template>
  <div class="commonly secondbox">
    <div class="second" :class="{pof:ok}">
      <second-nav @close="closeSecond" :openDrawer="open" :navid="navId"></second-nav>
    </div>
    <div class="secondmain box">
      <div class="secondDo" v-if="clientWidth < 768">
        <el-button size="small" icon="el-icon-menu" type="primary" @click="openDra">菜单</el-button>
      </div>
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
    </div>
  </div>
</template>

<script>
import SecondNav from "../../SecondNav/main";
export default {
  data(){
    return{
      navId: this.$route.query.n,
      clientWidth: document.body.clientWidth,
      open: false,
    }
  },
  methods:{
    openDra(){
      this.open = !this.open;
    },
    closeSecond(){
      this.open = !this.open;
    }
  },
  computed:{
    ok(){
      if(this.clientWidth > 768){
        return true;
      }
      return false;
    }
  },
  created(){
    
  },
  components:{
    SecondNav,
  }
}
</script>

<style>
  .secondDo{
    margin-bottom: 12px;
  }
</style>