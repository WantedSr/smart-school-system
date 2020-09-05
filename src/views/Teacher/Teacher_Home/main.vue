<template>
  <div class="content">
    <el-row :gutter="40">
      <el-col :lg="6" :md="12">
        <tea-left v-if="getData" :userData="userData"></tea-left>
      </el-col>
      <el-col :class="{toLeft50: true}" :lg="6" :md="12">
        <tea-right v-if="getData" :userData="userData"></tea-right>
      </el-col>
      <el-col :class="{toLeft25: true}" :lg="12" :md="24">
        <tea-center v-if="getData" :userData="userData"></tea-center>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import TeaLeft from "./left/TeaLeft";
import TeaRight from "./right/TeaRight";
import TeaCenter from "./center/TeaCenter";

export default {
  data(){
    return {
      userData: "",
    }
  },
  computed:{
    getData(){
      if(this.userData != ""){
        return true;
      }
      return false;
    },
  },
  created(){
    requestAjax({
      type: "get",
      url: "/userStatus.php",
      data:{
        token: this.$store.state.token,
        type: "teacher",
      },
      async: false,
      success:(res)=>{
        res = JSON.parse(res);
        if(res.code != 200){
          return this.error;
        }else{
          if(res == null || res == "" || res == "null"){
            this.$alert('用户数据有误，请检查LocalStorage是否冲突或被修改', '查询不到数据', {
              confirmButtonText: '确定',
              callback: action => {
                location.reload();
              }
            });
          }else{
            // console.log(res);
            this.userData = JSON.stringify(res['info']);
          }
        }
      },
      error:(err)=>{
        console.log(err);
        this.$notify.error({
          title: '服务器出错',
          message: '请求数据出现错误，请稍后再试或联系管理员',
        });
      }
    });
    // console.log(this.userData);
  },
  components:{
    TeaLeft,
    TeaRight,
    TeaCenter,
  }
}
</script>

<style>

  @media screen and (min-width: 1200px){
    .toLeft50{
      left: 50%;
      position: relative;
    }
    .toLeft25{
      left: -25%;
      position: relative;
    }
  }
</style>