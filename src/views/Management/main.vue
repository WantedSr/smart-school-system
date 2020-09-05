<template>
  <div class="backstage">
    <el-container>
      <el-aside :width="asideWidth">
        <div class="slide pof"> 
          <back-stage-aside></back-stage-aside>
        </div>
      </el-aside>
      <el-container>
        <el-header>
          <div class="top pof">
            <back-stage-nav></back-stage-nav>
          </div>
        </el-header>
        <el-main>
          <div class="main">
            <keep-alive>
              <router-view></router-view>
            </keep-alive>
          </div>
        </el-main>
      </el-container>
    </el-container>
  </div>
</template>

<script>
import BackStageNav from "./BackStageNav";
import BackStageAside from "./backStageAside";
export default {
  data(){
    return {
      asideWidth: "200px",
      cilentWidth: document.body.clientWidth,
    }
  },
  methods:{

  },
  created(){
    this.$store.commit("getUser");
    if(this.cilentWidth < 768){
      this.asideWidth = "64px";
    }
  },
  components:{
    BackStageNav,
    BackStageAside
  },
}
</script>

<style>
  body{
    background-color: #f5f6f7;
  }
  .top,.slide{
    top: 0;
    left: 0;
  }
  .top{
    width: 100%;
    z-index: 10;
    padding-left: 200px;
  }
  .slide{
    width: 200px;
    z-index: 20;
    height: 100%;
    box-shadow: 2px 0 5px rgba(44, 62, 80,.2);
  }
  .main{
    width: 100%;
    z-index: 1;
    min-height: 100%;
  }
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
  .head h4 small{
    right: 0;
    bottom: 0;
    color: #ccc;
    font-size: 12px;
  }
  .head{
    margin-bottom: 40px;
    text-align: left;
    border-bottom: 1px solid #cccccc;
    padding-bottom: 5px;
  }
  .head h4{
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
  .subpage{
    width: 100%;
    padding: 0 24px;
  }
  .pagehead , .pagehead2{
    width: 100%;
    border-bottom: 1px solid rgba(149, 165, 166,.3);
    margin-bottom: 30px;
  }
  .pagehead>h1{
    font-size: 24px;
    color: #2c3e50;
    font-weight: 400;
    padding-bottom: 5px;
  }
  .pagehead2>h2{
    font-size: 20px;
    color: #7f8c8d;
    font-weight: 400;
    padding-bottom: 5px;;
  }


  .backstage .el-header{
    height: 30px!important;
  }

  @media screen and (max-width: 768px){
    .slide{
      width: 64px;
    }
    .top{
      padding-left: 64px;
    }
    .el-main{
      padding: 6px;
    }
  }


</style>