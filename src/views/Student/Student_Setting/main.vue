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
import SetNav from "./StuSetNav";
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

<style scoped>
  .setting{
    max-width: 1170px;
    margin: 50px auto 0;
    background-color: #fff;
    padding: 20px;
  }
  .el-main{
    padding: 12px;
  }
  @media screen and (max-width: 768px) {
    .setting{
      padding: 0;
    }
  }
</style>