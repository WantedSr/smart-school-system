<template>
  <div class="setNav">
    <el-tabs :tab-position="tabPosition" style="height: 650px;">
      <el-tab-pane v-if="userData != ''" label="用户信息">
        <tea-info-set :userData="userData"></tea-info-set>
      </el-tab-pane>
      <el-tab-pane label="更改密码">
        <tea-upa-pwd></tea-upa-pwd>
      </el-tab-pane>
      <el-tab-pane label="我的头像">
        <tea-user-head></tea-user-head>
      </el-tab-pane>
      <!-- <el-tab-pane v-if="userData != ''" label="消息通知">
        <tea-message :userData="userData"></tea-message>
      </el-tab-pane> -->
    </el-tabs>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import TeaInfoSet from "./TeaInfoSet";
import TeaUpaPwd from "./TeaUpaPwd";
import TeaMessage from "./TeaMessage";
import TeaUserHead from "./TeaUserHead";
export default {
  data(){
    return {
      tabPosition: "left",
      userData: "",
      outwidth: document.body.clientWidth,
    }
  },
  computed:{
    getData(){
      if(this.labelData != ""){
        return true;
      }
      return false
    }
  },
  methods:{

  },
  created(){
    if(this.outwidth < 768){
      this.tabPosition = 'top';
    }
    let token = localStorage.getItem("Authorization");
    requestAjax({
      type: "get",
      url: "/userStatus.php",
      data:{
        token: token,
        type: "teacher",
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
            // console.log(res);
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
    TeaInfoSet,
    TeaUpaPwd,
    TeaMessage,
    TeaUserHead,
  }
}
</script>

<style>
  .el-tabs--left .el-tabs__header.is-left{
    margin-right: 20px;
  }
</style>