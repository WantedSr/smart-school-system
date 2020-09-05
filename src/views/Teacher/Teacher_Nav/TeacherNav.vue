<template>
  <!-- <div class="top col-lg-12"> -->
  <div class="topHead">
    <div class="header por">
      <el-row>
        <el-col class="logo" :lg="10" :md="10" :sm="22" :xs="20">
          <img draggable="false" src="~assets/images/logo_write.png" alt="no_logo">
          <el-link @click="goHome" :underline="false">{{ $store.state.school_name }}({{ $store.state.campus_name }})</el-link>
        </el-col>
        <el-col class="hidden-sm-and-down" :lg="14" :md="14" :sm="12" :xs="8">
          <div class="clearfix">
            <span ><a href="/teacher/home">{{username}}</a></span>
            <span>{{getTime}}</span>
            <span>{{getWeek}}</span>
            <!-- <span>{{ $store.state.week }}</span> -->
            <span>
              <a href="#" @click="toLogout" title="退出"><i class="el-icon-error" aria-hidden="true"></i></a>
            </span>
          </div>
        </el-col>
        <el-col :sm="1" :xs="2" class="hidden-md-and-up">
            <span>
              <el-dropdown>
                <span class="el-dropdown-link">
                  <i class="el-icon-menu"></i>
                </span>
                <el-dropdown-menu slot="dropdown">
                  <el-dropdown-item><el-link :underline="false" @click="goHome">首页</el-link></el-dropdown-item>
                  <el-dropdown-item><el-link :underline="false" @click="toSetting">设置</el-link></el-dropdown-item>
                  <el-dropdown-item><el-link @click="toLogout" :underline="false">退出</el-link></el-dropdown-item>
                </el-dropdown-menu>
              </el-dropdown>
            </span>
        </el-col>
      </el-row>
    </div>
  </div>

</template>

<script>
export default {
  data(){
    return {
      
    }
  },
  computed:{
    getTime(){
      let $date = new Date();
      let Y = $date.getFullYear();
      let m = $date.getMonth() + 1;
      let d = $date.getDate();
      let str = Y+"年"+m+"月"+d+"日";
      return str;
    },
    getWeek(){
      let $old = this.$store.state.semester_start;
      let $new = new Date().getTime();
      let $zhou = parseInt(($new-$old)/1000/60/60/24/7 + 1);
      return "第 "+$zhou+" 周";
    }
  },
  props:{
    username:{
      type: String,
      default: "admin",
    },
  },
  methods:{
    toHome(){
      this.$router.push('/student/home');
    },
    toSetting(){
      this.$router.push('/student/setting');
    },
    toLogout(){
      this.$store.commit('logout');
      this.$router.replace('/login');
      this.$message({
        message: '退出成功!',
        duration: 2000,
        type: 'success'
      });
    },
    goHome(){
      location.href = '/teacher';
    }
  }
}
</script>

<style>
  .header{  
    padding: 12px 24px;
    top: 0;
    left: 0;
    height: 48px;
    background-color: #2c3e50;
  }
  .header h1{
    text-align: left;
    font-size: 18px;
    line-height: 24px;
    color: #fff;
    font-weight: 600;
    margin: 0;
  }
  .header .el-link.el-link--default{
    color: #FFF;
  }
  .header{
    text-align: right;
    margin-bottom: 25px;
  }
  .header a{
    text-decoration: none;
    color: #3498db;
    font-size: 14px;
  }
  .header > div > div:nth-child(2) span{
    display: inline-block;
    font-size: 14px;
    position: relative; 
    margin-right: 25px;
    color: #3498db;
  }
  .header > div > div:nth-child(2) span:nth-child(4){
    transform: scale(1.3);
  }

  .header > div > div:nth-child(3){
    transform: scale(1.5);
  }
  .header > div > div:nth-child(3) span{
    color: #3498db;
    position: relative;
  }
  .header > div > div:nth-child(3) span:hover{
    color: #2980b9;
  }

  .logo{
    text-align: left;
    position: relative;
  }
  .logo a{
    font-size: 20px;
    margin-left: 30px;
  }
  .logo img{
    position: absolute;
    height: 26px;
    top: 1px;
  }
  @media screen and (max-width: 500px){
    .logo a{
      font-size: 14px;
    }
  }
</style>