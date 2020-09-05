<template>
  
  <div class="sutdent">
    <stu-nav :username="this.$store.state.username"></stu-nav>
    <keep-alive>
      <router-view></router-view>
    </keep-alive>
  </div>

</template>

<script>
import StuNav from "./StudentNav/StudentNav";

export default {
  data(){
    return {
      
    }
  },
  created(){
    this.$store.commit("getUser");
  },
  components:{
    StuNav,
  }
}
</script>

<style>
  .content{
    width: 96%;
    margin: 0 auto;
    position: relative;
    padding: 12px;
  }
  .box{
    background-color: #fff;
    border-radius: 6px;
    box-shadow: 2px 2px 8px rgba(50,50,50,.2);
    margin-bottom: 25px;
    padding: 12px;
    overflow: hidden;
  }
  .box .head h4 small{
    right: 0;
    bottom: 0;
    color: #ccc;
    font-size: 12px;
  }
  .box .head{
    margin-bottom: 30px;
    text-align: left;
  }
  .box .head h4{
    font-weight: 500;
    color: #666;
  }
  .nodata{
    text-align: center;
    font-size: 14px;
    user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    -webkit-user-select: none;
    -o-user-select: none;
    color: #ccc;
    padding: 12px;
  }
  @media screen and (max-width: 768px){
    .box .head h4 small{
      right: 0;
      bottom: 0;
      color: #ccc;
      font-size: 12px;
    }
    .box .head{
      margin-bottom: 26px;
      text-align: left;
    }
    .box .head h4{
      font-weight: 500;
      font-size: 16px;
      color: #666;
    }
  }
</style>