<template>
  <div class="setNav">
    <el-tabs :tab-position="tabPosition" style="min-height: 650px;margin-bottom: 20px;width:100%">
      
      <el-tab-pane v-if="userData != ''" label="用户信息">
        <stu-info-set :userdata="userData"></stu-info-set>
      </el-tab-pane>

      <el-tab-pane label="更改密码">
        <stu-upa-pwd></stu-upa-pwd>
      </el-tab-pane>

      <!-- <el-tab-pane label="我的标签">
        <stu-label></stu-label>
      </el-tab-pane> -->

      <el-tab-pane v-if="userData != ''" label="消息通知">
        <stu-message :userdata="userData"></stu-message>
      </el-tab-pane>

      <el-tab-pane label="我的头像"> 
        <stu-user-head></stu-user-head>
      </el-tab-pane>

    </el-tabs>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import StuInfoSet from "./StuInfoSet";
import StuUpaPwd from "./StuUpaPwd";
import StuLabel from "./StuLabel";
import StuMessage from "./StuMessage";
import StuUserHead from "./StuUserHead"
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
  computed:{
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
    StuInfoSet,
    StuUpaPwd,
    StuLabel,
    StuMessage,
    StuUserHead,
  }
}
</script>

<style>
  .el-tabs--left .el-tabs__header.is-left{
    margin-right: 20px;
  }
  .el-tabs__nav-wrap{
    overflow: inherit; 
  }
  .el-tabs__nav-scroll{
    overflow: inherit;
    overflow-y: hidden;
  }


</style>