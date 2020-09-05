<template>
  <div v-loading="loading">
    <div class="pagehead">
      <h1>修改审批流程</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="approval_name" label="审批流程名称">
          <el-input size="small" v-model="form.approval_name" placeholder="请输入审批流程名称"></el-input>
        </el-form-item>
        <el-form-item prop="approval_type" label="审批流程名称">
          <el-select size="small" v-model="form.approval_type" placeholder="选择审批类型">
            <el-option label="统一设置" value="统一设置"></el-option>
            <el-option label="分部设置" value="分部设置"></el-option>
            <!-- <el-option label="分类设置" value="分类设置"></el-option> -->
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onUpa('form')">修改</el-button>
          <el-button size="small" @click="onBack">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form:{
        approval_id: "",
        approval_name: "",
        approval_type: "",
        approvers: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },
      
      rules:{
        approval_name: [
          { required: true, message: '请输入组织架构名称', trigger: 'blur' },
        ],
        approval_type: [
          { required: true, message: '请选择部门', trigger: 'change' },
        ],
      },

      loading: false,
    }
  },
  created(){
    this.getData();
  },
  methods:{
    onBack(){
      this.$router.go(-1)
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/Office/Process/Approval.php",
            data: {
              type: "upa_approval",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              // console.log(res);
              if(res){
                this.$message({
                  message: '恭喜你，修改审批流程成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改审批流程信息 "+ JSON.stringify(this.form),
                  type: "修改记录",
                  model: "审批流程模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.$router.go(-1);
              }
              else{
                this.$message.error('修改失败，请稍后再试！');
              }
            },
            error:(err)=>{
              this.loading = false;
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: '/Office/Process/Approval.php',
        data:{
          type: "sel_approval",
          selobj: {
            campus: this.$store.state.userCampus,
            id: this.$route.query.appId,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.form = res[0];
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
  }
}
</script>

<style>

</style>