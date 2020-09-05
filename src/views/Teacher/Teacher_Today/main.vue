<template>
  <div class="today">
    <el-row :gutter="40">
      <el-col :lg="12">
        <tea-today></tea-today>
      </el-col>
      <el-col class="" :lg="12">
        <tea-today-work></tea-today-work>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import TeaToday from "./TeaToday";
import TeaTodayWork from './TeaTodayWork';
export default {
  data(){
    return{
      userData: "",
    }
  },
  computed:{
    getData(){
      if(this.userData != ""){
        return true;
      }
      return false
    }
  },
  methods:{
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
    TeaToday,
    TeaTodayWork,
  }
}
</script>

<style>
  .today{
    width: 90%;
    margin: 0 auto;
  }
</style>