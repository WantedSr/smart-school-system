<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-menu @getNav3="getNav3" @getNav2="getNav2" :nav1List="nav1List" :nav2List="nav2List" :nav3List="nav3List"></sel-menu>
    </div>
    <div v-if="Tadd">
      <add-menu @getNav3="getNav3" @getNav2="getNav2" :nav1List="nav1List" :nav2List="nav2List" :nav3List="nav3List"></add-menu>
    </div>
    <div v-if="Tupa">
      <upa-menu @getNav3="getNav3" @getNav2="getNav2" :nav1List="nav1List" :nav2List="nav2List" :nav3List="nav3List"></upa-menu>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelMenu from './SelMenu';
import AddMenu from './AddMenu';
import UpaMenu from './UpaMenu';
export default {
  data(){
    return{
      nav1List: "",
      nav2List: "",
      nav3List: "",
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getNav1();
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    Tadd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    Timport(){
      if(this.$route.query.type == 'import'){
        return true;
      }
      return false;
    },
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  components:{
    SelMenu,
    AddMenu,
    UpaMenu,
  },
  methods:{
    getnav(col="*",lever=1,upnav=null,type='data'){
      let navlist = [];
      requestAjax({
        url: "/system/menu.php",
        type: 'get',
        data:{
          reqType: "sel_menu",
          col: col,
          lever: lever,
          upnav: upnav,
          type: type,
        },
        async: false,
        success:(res)=>{
          // console.log(res);
          navlist = JSON.parse(res);
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
      return navlist;
    },
    getNav1(){
      this.nav1List = this.getnav("*",1);
    },
    getNav2(up,type){
      this.nav2List = this.getnav("*",2,up,type);
    },
    getNav3(up,type){
      this.nav3List = this.getnav("*",3,up,type);
    },

  },
}
</script>

<style>

</style>