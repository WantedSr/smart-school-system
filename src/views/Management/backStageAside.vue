<template>
  <div class="backstageaside">

    <div class="username hidden-xs-only">
      <div class="userhead flex">
        <el-avatar :src="src" :size="100" icon="el-icon-user"></el-avatar>
      </div>
      <h3>{{username}}</h3>
      <p>信息部</p>
    </div>

    <div class="options">
      <el-menu
      :default-active="$route.path"
      :collapse="isCollapse" 
      class="el-menu-vertical-demo" 
      background-color="#2c3e50" 
      text-color="#7f8c8d" 
      active-text-color="#fff" 
      @open="handleOpen"
      @close="handleClose">
        <el-menu-item index="/management/backstage" @click="toLink('/management/backstage')">
          <i class="el-icon-s-home"></i>
          <span slot="title">首页</span>
        </el-menu-item>
        <el-menu-item v-if="$store.state.userGroup.indexOf('T') != -1" index="/teacher/home" @click="toOther('/teacher/home')">
          <i class="el-icon-discount"></i>
          <span slot="title">教师主页</span>
        </el-menu-item>
        <el-menu-item v-if="$store.state.userGroup.indexOf('D') != -1" index="/other/data_center" @click="toOther('/other/data_center')">
          <i class="el-icon-s-marketing"></i>
          <span slot="title">数据中心</span>
        </el-menu-item>
        <el-menu-item index="/other/help" @click="toOther('/other/help')">
          <i class="el-icon-document"></i>
          <span slot="title">帮助文档</span>
        </el-menu-item>
        <el-menu-item index="7" @click="toLogout">
          <i class="el-icon-close"></i>
          <span slot="title">退出</span>
        </el-menu-item>
      </el-menu>
    </div>

    <div class="copyright poa hidden-xs-only">
      <p>&copy;Copyright 深圳市智慧校园</p>
    </div>
    
  </div>
</template>

<script>
export default {
  data(){
    return {
      isCollapse: false,
      navData: "",
      username: localStorage.getItem("username"),
      outWidth: document.body.clientWidth,

      src: 'https://cube.elemecdn.com/6/94/4d3ea53c084bad6931a56d5158a48jpeg.jpeg',
    }
  },
  methods:{
    handleOpen(key, keyPath) {
      // console.log(key, keyPath);
    },
    handleClose(key, keyPath) {
      // console.log(key, keyPath);
    },
    toLink(link){
      // this.$router.push(link);
      location.href = link;
    },
    toOther(link){
      open(link,"_blank");
    },
    toLogout(){
      this.$store.commit('logout');
      this.$router.replace('/login');
      this.$message({
        message: '退出成功!',
        duration: 2000,
        type: 'success'
      });
    }
  },
  created(){
    if(this.outWidth < 768){
      this.isCollapse = true;
    }
  }
}
</script>

<style scoped>
  .backstageaside{
    width: 100%;
    height: 100%;
    background-color: #2c3e50;
    padding-top: 30px;
    overflow: hidden;
    position: relative;
  }
  .backstageaside::before{
    width: 150%;
    height: 150%;
    position: absolute;;
    content: "";
    bottom: -102%;
    right: -80%;
    z-index: 1;
    background-color: rgba(255, 255, 255,.2);
    transform: rotate(-45deg);
  }
  .backstageaside::after{
    width: 150%;
    height: 150%;
    position: absolute;;
    content: "";
    bottom: -70%;
    right: -60%;
    z-index: 1;
    background-color: rgba(255, 255, 255,.1);
    transform: rotate(48deg);
  }
  .username{
    margin-bottom: 40px;
    text-align: center;
    color: #FFF;
    position: relative;
    z-index: 1;
  }
  .userhead{
    width: 100px;
    height: 100px;
    /* background-color: #FFF; */
    margin: 0 auto;
    border-radius: 100px;;
    font-size: 24px;
    font-weight: 600;
    color: #34495e;
    margin-bottom: 5px;
  }
  .userhead > .el-avatar i{
    font-size: 50px;
    position: relative;
    top: 12px;
  }
  .username h3{
    font-weight: 400;;
    font-size: 18px;;
  }
  .username p{
    font-size: 14px;
    color: #ddd;
  }

  .copyright,.copyright>p{
    color: #FFF;
    font-size: 12px;
    bottom: 30px;
    width: 100%;
    text-align: center;
    z-index: 3;
  }


  .el-menu{
    border-right: none;
    position: relative;
    z-index: 3;
  }




  @media screen and (max-width:768px){
    .backstageaside{
      padding: 0;
    }
  }
</style>