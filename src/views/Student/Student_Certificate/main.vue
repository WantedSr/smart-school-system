<template>
  <div class="certificate">
    <el-row :gutter="40">
      <el-col :lg="18">
        <certificate-list></certificate-list>
      </el-col>
      <el-col class="hidden-sm-and-down" :lg="6">
        <stu-home-right v-if="getData" :userData="userData"></stu-home-right>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import StuHomeRight from "views/Student/Student_Home/right/StuHomeRight";
import CertificateList from "./CertificateList";
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
      return false;
    },
  },
  created(){
    let token = localStorage.getItem("Authorization");
    requestAjax({
      url: "/userStatus.php",
      data:{
        token: token,
        type: "student",
      },
      type: "get",
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
  },
  methods:{

  },
  components:{
    StuHomeRight,
    CertificateList,
  }
}
</script>

<style>
  .certificate{
    width: 95%;
    margin: 0 auto;
  }
</style>