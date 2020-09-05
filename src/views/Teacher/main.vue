<template>
  
  <div class="sutdent">
    <teacher-nav :username="this.$store.state.username"></teacher-nav>
    <keep-alive>
      <router-view></router-view>
    </keep-alive>
  </div>

</template>

<script>
import TeacherNav from "./Teacher_Nav/TeacherNav";

export default {
  data(){
    return {
      
    }
  },
  created(){
    this.$store.commit("getUser");
  },
  components:{
    TeacherNav,
  }
}
</script>

<style>
  .content{
    width: 96%;
    margin: 0 auto;
    position: relative;
    background-color: #fff;
    padding: 12px;
  }
  .box{
    background-color: #fff;
    box-shadow: 2px 2px 8px rgba(44, 62, 80,.2);
    margin-bottom: 25px;
    padding: 12px;
    border-radius: 6px;
    overflow: hidden;
  }
  .box .head h4 small{
    right: 0;
    bottom: 0;
    color: #ddd;
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