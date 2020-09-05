<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>修改包含选项</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="option_name" label="包含选项名称">
          <el-input size="small" v-model="form.option_name" placeholder="请输入包含选项名称"></el-input>
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
        option_name: "",
      },
      
      rules:{
        option_name: [
          { required: true, message: '请请输入包含选项名称', trigger: 'blur' },
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
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            type: "post",
            url: "/Office/Process/ContainOption.php",
            data: {
              type: "upa_contain_option",
              obj: this.form,
              val: this.form.id,
              sel: "id",
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，修改包含选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改包含选项信息 "+ JSON.stringify(this.form),
                  type: "修改记录",
                  model: "包含选项模块",
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
        url: "/Office/Process/ContainOption.php",
        data: {
          type: "sel_contain_option",
          selobj:{
            campus: this.$store.state.userCampus,
            option_id: this.$route.query.optionId,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.form = res[0];
          // console.log(res);
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
    }
  }
}
</script>

<style>

</style>