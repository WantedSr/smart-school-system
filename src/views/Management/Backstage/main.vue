<template>
  <div class="teaHome" v-loading="loading">
    <div class="nav" v-if="navData!=''">

      <div class="navItem" v-for="(item) in navData" :key="item.nav_id">
        <div class="navItemHead">
          <h4 class="por">{{item.nav_name}}</h4>
        </div>
        <div class="navItemItem clearfix">
          <ul>
            <li v-for="(item2) in item.children" :key="item2.nav_id"> 
              <el-link :underline="false" @click="toLink(item2.href,item2.nav_id)">
                <i :class="'el-icon-'+item2.icon"></i>
                <p>{{item2.nav_name}}</p>
              </el-link>
              <!-- <el-link v-else target="_blank" :underline="false" :href="item2.href">
                <i :class="'el-icon-'+item2.icon"></i>
                <p>{{item2.nav_name}}</p>
              </el-link> -->
            </li>
          </ul>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      navData: "",
      loading: true,
    }
  },
  methods:{
    toLink(link,id){
      this.$router.push({
        path: link,
        query:{
          n: id,
        }
      });
    }
  },
  computed:{
  },
  mounted(){
    requestAjax({
      type: "get",
      url: "/backNav.php",
      data:{
        token: this.$store.state.token,
      },
      success:(res)=>{
        // res = JSON.parse(res);
        // console.log(res);
        this.loading = false;
        this.navData = JSON.parse(res);
        // console.log(this.navData);
      },
      error:(err)=>{
        this.loading = false;
        this.$notify.error({
          title: '服务器有误',
          message: '服务器出错，请稍后再试，或联系管理员',
          position: 'top-left'
        });
        console.log(err);
      }
    })
  },
  // mounted(){
  // }
}
</script>

<style>
  .navItemHead{
    color: #333;
    padding-bottom: 3px;
    font-size: 16px;
    border-bottom: 1px solid rgba(149, 165, 166,.2);
  }
  .navItemHead h4{
    font-size: 20px;
    color: rgba(52, 73, 94,1.0);
    font-weight: 500;
  }
  .navItemHead small{
    right: 3px;
    font-size: 12px;
    color: #95a5a6;
    bottom: 0;
  }

  .navItem{
    margin-bottom: 10px;
  }

  .navItemItem{
    width: 100%;
  }
  .navItemItem ul li:hover{
    top: -5px;
    box-shadow: 0 3px 5px rgba(128,128,128,.3);
  }
  .navItemItem ul li{
    transition: .2s ease-in-out;
	  transition-property: top,box-shadow;
    position: relative;
    top: 0;
    float: left;
    margin: 16px;
    text-align: center;
    background-color: #FFF;
    padding: 18px;
    border-radius: 3px;
  }
  .navItemItem>ul>li i{
    font-size: 35px;
    margin-bottom: 10px;
  }
  .navItemItem>ul>li p{
    font-size: 14px;
  }

  @media screen and (max-width:768px){
    .navItemItem ul li{
      margin: 12px;
      padding: 8px;
    }
    .navItemItem>ul>li i{
      font-size: 30px;
      margin-bottom: 10px;
    }
    .navItemItem>ul>li p{
      font-size: 12px;
    }
  }
  
  

</style>