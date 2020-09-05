<template>
  <div class="secondnav">
    <el-menu
      :default-active="$route.path"
      width="150"
      class="el-menu-vertical-demo"
      :collapse="isCollapse"
      v-if="navData != '' && cilentWidth > 768">

      <div v-for="(item,i) in navData" :key="'nav'+i">
        <el-menu-item v-if="item.children.length == 0" @click="toLink(item.href)" :index="item.href">{{item.nav_name}}</el-menu-item>
        <el-submenu v-else :index="String(i+1)">
          <template slot="title">
            <span>{{item.nav_name}}</span>
          </template>
          <el-menu-item v-for="(item2,r) in item.children" @click="toLink(item2.href)" :key="i+'subnav'+r" :index="item2.href">{{item2.nav_name}}</el-menu-item>
        </el-submenu>   
      </div>

    </el-menu>
    <el-drawer
      size="150"
      v-if="cilentWidth < 768 && navData != ''"
      :show-close="false"
      :with-header="false"
      :visible.sync="openDrawer"
      :before-close="handleClose"
      :direction="direction">
        <el-menu
        :default-active="getDea"
        width="150"
        class="el-menu-vertical-demo"
        :collapse="isCollapse">

        <div v-for="(item,i) in navData" :key="'nav'+i">
          <el-menu-item v-if="item.children.length == 0" @click="toLink2(item.href)" :index="String(i+1)">{{item.nav_name}}</el-menu-item>
          <el-submenu v-else :index="String(i+1)">
            <template slot="title">
              <span>{{item.nav_name}}</span>
            </template>
            <el-menu-item v-for="(item2,r) in item.children" @click="toLink2(item2.href)" :key="i+'subnav'+r" :index="(i+1)+'_'+(r+1)">{{item2.nav_name}}</el-menu-item>
          </el-submenu>   
        </div>
      </el-menu>
    </el-drawer>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      navData: "",
      n: this.$route.query.n,
      isCollapse: false,
      cilentWidth: document.body.clientWidth,
      // drawer: this.openDrawer,
      direction: 'ltr',
    }
  },
  methods:{
    toLink(link){
      this.$router.push({
        path: link,
        query: {
          n: this.n,
        },
      });
    },
    toLink2(link){
      this.$emit("close",false);
      this.$router.push({
        path: link,
        query: {
          n: this.n,
        },
      });
    },
    handleClose() {
      this.$emit("close",false);
    }
  },
  computed:{
    getDea(){
      if(this.navData[0].children.length == 0){
        return "1";
      }
      return "1_1"
    },
  },
  props:{
    navid:{
      type: String,
      require: true,
    },
    openDrawer:{
      type: Boolean,
      default: false,
    }
  },
  created(){
    // if(this.cilentWidth < 768){
    //   this.isCollapse = true;
    // }
    requestAjax({
      type: 'get',
      url: "/backNav2.php",
      data:{
        navid: this.navid,
      },
      success:(res)=>{
        this.navData = JSON.parse(res);
      },
      error:(err)=>{
        console.log(err);
        this.$message.error('服务器有误，请稍后再试！');
      }
    })
  },
}
</script>

<style>
  .secondnav{
    height: 100%;
  }
  .secondnav>.el-menu{
    height: 100%;
    box-shadow: 3px 0 5px rgba(33,33,33,.2);
    overflow-x: hidden;
    overflow-y: inherit;
  }
  .second{
    width: 150px;
    height: 100%;
    top: 0;
    padding-top: 30px;
    left: 200px;
  }
  .secondbox{
    padding-left: 160px;
  }
  .secondmain{
    min-height: 755px;
  }
  @media screen and (max-width: 768px){
    .second{
      left: 0px;
      top: 0px;
      z-index: 20;;
      padding: 0;
      background-color: transparent;
    }
    .secondbox{
      padding-left: 00px;
    }
  }
</style>