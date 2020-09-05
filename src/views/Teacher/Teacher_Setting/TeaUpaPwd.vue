<template>
  <div class="upaPwd">
    <el-form v-loading="loading" :model="ruleForm" status-icon :rules="rules" ref="ruleForm" label-width="100px" class="demo-ruleForm">
      <el-form-item label="旧密码" prop="oldpass">
        <el-input type="password" v-model="ruleForm.oldpass"></el-input>
      </el-form-item>
      <el-form-item label="新密码" prop="newpass">
        <el-input type="password" v-model="ruleForm.newpass" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="确认密码" prop="checkPass">
        <el-input type="password" v-model="ruleForm.checkPass" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="primary" @click="submitForm('ruleForm')">提交</el-button>
        <el-button size="small" @click="resetForm('ruleForm')">重置</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data() {
    var oldpass = (rule, value, callback) => {
      if (!value) {
        return callback(new Error('原密码不能为空'));
      };
      setTimeout(() => {
        if (value == this.ruleForm.newpass) {
          callback(new Error('新密码与旧密码相同！'));
        } else {
          callback();
        }
      }, 1000);
    };
    var validatePass = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请输入新密码'));
      } else {
        if (this.ruleForm.checkPass !== '') {
          this.$refs.ruleForm.validateField('checkPass');
        }
        callback();
      }
    };
    var validatePass2 = (rule, value, callback) => {
      if (value === '') {
        callback(new Error('请再次输入密码'));
      } else if (value !== this.ruleForm.newpass) {
        callback(new Error('两次输入密码不一致!'));
      } else {
        callback();
      }
    };
    return {
      ruleForm: {
        oldpass: '',
        newpass: '',
        checkPass: '',
      },
      rules: {
        oldpass: [
          { validator: oldpass, trigger: 'blur' }
        ],
        newpass: [
          { validator: validatePass, trigger: 'blur' }
        ],
        checkPass: [
          { validator: validatePass2, trigger: 'blur' }
        ]
      },

      loading: false,
    };
  },
  methods: {
    submitForm(formName) {
      this.$refs[formName].validate((valid) => {
        let token = localStorage.getItem('Authorization');
        if (valid) {
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/teaSetting.php",
            data:{
              token: token,
              type: "upaPwd",
              oldpwd: this.ruleForm.oldpass,
              newpwd: this.ruleForm.newpass,
            },
            success:(res)=>{
              this.loading = false;
              if(res == "really"){
                this.$message({
                  message: '旧密码有误哦！',
                  type: 'warning'
                });
              }else if(res){
                this.$message({
                  message: '恭喜你，修改成功！',
                  type: 'success'
                });
                location.reload();
              }else{
                this.$message.error('修改失败！请稍后再试！');
              }
            },
            eror:(err)=>{
              this.loading = false;
              console.log(err);
              this.$message.error('服务器有误！请稍后再试或联系管理员！');
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs[formName].resetFields();
    }
  }
}
</script>

<style>

</style>