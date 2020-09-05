<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>修改选项</h1>
    </div>
    <el-form :model="form" ref="form" :rules="rules">
      <el-form-item prop="name" label="选项名称" :label-width="formLabelWidth">
        <el-input v-model="form.name" placeholder="请输入项目编号" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item prop="score" label="选项分数" :label-width="formLabelWidth">
        <el-input v-model="form.score" placeholder="请输入项目名称" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="" @click="onBack">取消</el-button>
        <el-button size="small" type="success" @click="onUpa('form')">修改</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{

      form:{},

      rules:{
        score: [
          { required: true, message: '请输入选项分数', trigger: 'blur' },
        ],
        name: [
          { required: true, message: '请输入选项名称', trigger: 'blur' },
        ],
      },

      optionId: this.$route.query.optionId,
      loading: false,
    }
  },
  created(){
    // console.log(this.optionId);
    this.loading = true;
    requestAjax({
      type: "get",
      url: '/teach/Option.php',
      data:{
        type: "sel_option_list",
        selobj:{
          id: this.optionId,
        },
      },
      success:(res)=>{
        res = JSON.parse(res)[0];
        // console.log(res);
        this.form = res;
        this.loading = false;
      },
      error:(err)=>{
        this.loading = false;
        console.log(err);
        this.$notify.error({
          title: '错误',
          message: '服务器错误，请稍后再试!'
        });
      }
    });

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
            url: "/teach/Option.php",
            data: {
              type: "upa_option_list",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '修改选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改选项 "+ this.form.id,
                  type: "修改记录",
                  model: "巡堂查课模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.dialogFormVisible = false;
                this.$router.go(-1);
              }
              else{
                this.$message.error('添加失败，请稍后再试！');
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
    }
  }
}
</script>

<style>

</style>