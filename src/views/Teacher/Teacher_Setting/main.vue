<template>
  <div class="setting box">
    <el-container>
      <el-main>
        <set-nav></set-nav>
      </el-main>
    </el-container>
  </div>
</template>

<script>
import SetNav from "./TeaSetNav";
export default {
  data(){
    return{ 
      
    }
  },
  components:{
    SetNav,
  },
}
</script>

<style>
  .setting{
    max-width: 1170px;
    margin: 20px auto 0;
    background-color: #fff;
    padding: 20px 0;
    overflow: hidden;
    min-height: 730px;
  }
</style>