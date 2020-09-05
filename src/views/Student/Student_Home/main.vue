<template>
  <div class="content">
    <el-row :gutter="40">
      <el-col :lg="6" :md="12">
        <stu-home-left v-if="getData" :userData="userData"></stu-home-left>
      </el-col>
      <el-col :class="{toLeft50: true}" :lg="6" :md="12">
        <stu-home-right v-if="getData" :userData="userData"></stu-home-right>
      </el-col>
      <el-col :class="{toLeft25: true}" :lg="12" :md="24">
        <stu-home-center v-if="getData" :userData="userData"></stu-home-center>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import StuHomeLeft from "./left/StuHomeLeft";
import StuHomeRight from "./right/StuHomeRight";
import StuHomeCenter from "./center/StuHomeCenter";

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
    let token = localStorage.getItem("Authorization");
    requestAjax({
      type: "get",
      url: "/userStatus.php",
      data:{
        token: token,
        type: "student",
      },
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
  },
  components:{
    StuHomeLeft,
    StuHomeRight,
    StuHomeCenter,
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