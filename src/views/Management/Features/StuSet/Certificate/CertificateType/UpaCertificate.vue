<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>添加证书类型</h1>
    </div>
    
    <div class="form">
      <el-form label-position="left" ref="form" :rules="rules" label-width="100px" :model="form" class="demo-form-inline">
        <el-form-item prop="certificate_name" label="证书名称">
          <el-input size="small" style="width: 300px" v-model="form.certificate_name" placeholder="证书名称"></el-input>
        </el-form-item>
        <el-form-item prop="type" label="证书类别">
          <el-select size="small" v-model="form.type" placeholder="证书类别">
            <el-option label="通用类" value="通用"></el-option>
            <el-option label="技能类" value="技能"></el-option>
          </el-select>              
        </el-form-item>
        <el-form-item prop="lever" label="证书等级">
          <el-select size="small" v-model="form.lever" placeholder="证书等级">
            <el-option label="初级类" value="初级"></el-option>
            <el-option label="中级类" value="中级"></el-option>
            <el-option label="高级类" value="高级"></el-option>
          </el-select>              
        </el-form-item>
        <el-form-item prop="issue" label="颁发部门">
          <el-input size="small" style="width: 300px" v-model="form.issue" placeholder="颁发部门"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onUpa('form')">修改</el-button>
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
        certificate_name: "",
        type: "",
        lever: "",
        issue: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: ""
      },
      rules:{
        certificate_name: [
          { required: true, message: '请输入证书名称', trigger: 'blur' },
        ],
        type: [
          { required: true, message: '请选择证书类型', trigger: 'change' }
        ],
        lever: [
          { required: true, message: '请选择证书等级', trigger: 'change' }
        ],
        issue: [
          { required: true, message: '请输入颁发部门', trigger: 'blur' }
        ],
      },
      loading: false,
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      url: "/StuManagement/Certificate/CertificateType.php",
      type: 'get',
      data:{
        type: "sel_type",
        col: "*",
        selobj: {
          campus: this.$store.state.userCampus,
          id: this.$route.query.typeId,
        },
      },
      async: true,
      success:(res)=>{
        this.loading = false;
        res = JSON.parse(res)[0];
        // console.log(res);
        this.form = res;
      },
      error:(err)=>{
        this.loading = false;
        console.log(err);
        this.$notify.error({
          title: '错误',
          message: '服务器有误！,请稍后再试！'
        });
      }
    })
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/StuManagement/Certificate/CertificateType.php",
            data: {
              type: "upa_type",
              obj: this.form,
              sel: "id",
              val: this.form.id,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，添加证书类型成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改证书类型信息 "+ this.form.id,
                  type: "修改记录",
                  model: "专业证书模块",
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
  }
}
</script>

<style>

</style>