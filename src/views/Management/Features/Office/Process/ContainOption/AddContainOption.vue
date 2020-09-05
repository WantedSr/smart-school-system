<template>
  <div>
    <div class="pagehead" v-loading="false">
      <h1>添加包含选项</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="option_name" label="包含选项名称">
          <el-input size="small" v-model="form.option_name" placeholder="请输入包含选项名称"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onAdd('form')">添加</el-button>
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
        option_id: this.$store.state.userCampus,
        option_name: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },
      
      rules:{
        option_name: [
          { required: true, message: '请请输入包含选共享名称', trigger: 'blur' },
        ],
      },

      loading: false,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.form.addTime = new Date().getTime();
          let arr = Object.values(this.form);
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/Office/Process/ContainOption.php",
            data: {
              type: "add_contain_option",
              arr: arr,
              campus: this.$store.state.userCampus,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              // console.log(res);
              // console.log(arr);
              if(res){
                this.$message({
                  message: '恭喜你，添加包含选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加包含选项信息 "+ JSON.stringify(this.form),
                  type: "添加记录",
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
                this.$message.error('添加失败，请稍后再试！');
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