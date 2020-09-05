<template>
  <div class="message">
    <el-form :model="message" label-width="100px" class="demo-ruleForm">
      <el-form-item label="我的事项" prop="mywork">
        <el-switch @change="toChang" active-value="1" inactive-value="0" v-model="message.mywork"></el-switch>
      </el-form-item>
      <el-form-item label="校园公告" prop="school">
        <el-switch @change="toChang" active-value="1" inactive-value="0" v-model="message.school"></el-switch>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      message: {
        mywork: JSON.parse(this.userdata)['msgSet'].split(',')[0],
        school: JSON.parse(this.userdata)['msgSet'].split(',')[1],
      }
    }
  },  
  computed:{
  },
  props:{
    userdata:{
      type: String,
      require: true,
    }
  },
  methods:{
    toChang(){
      let arr = [];
      $.each(this.message, function (i, v) { 
        arr.push(v);
      });
      let str = arr.join(',');
      requestAjax({
        type: "post",
        url: "/stuSetting.php",
        data:{
          token: localStorage.getItem("Authorization"),
          type: "setMessage",
          msg: str,
        },
        success:(res)=>{
          if(res != "error"){
            if(res){
              this.$message({
                message: '恭喜你，修改成功！',
                type: 'success'
              });
            }else{
              this.$message.error('修改失败！');
            }
          }
        },
        error:(err)=>{
          console.log(err);
          this.$message.error('服务器有误，请稍后重试或联系管理员！');
        }
      })
    }
  }
}
</script>

<style>

</style>