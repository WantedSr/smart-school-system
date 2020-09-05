<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>添加卫生模块</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="100px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="health_id" label="模块代码">
          <el-input style="width: 250px" v-model="sel_add.health_id" placeholder="模块代码"></el-input>
        </el-form-item>
        <el-form-item prop="health_name" label="模块名称">
          <el-input style="width: 250px" v-model="sel_add.health_name" placeholder="模块名称"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onAdd('sel_add')">添加</el-button>
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
      sel_add:{
        health_id: "", 
        health_name: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId
      },
      rules:{
        health_name: [
          { required: true, message: '请输入模块名称', trigger: 'blur' },
        ],
        health_id: [
          { required: true, message: '请选择模块代码', trigger: 'blur' }
        ],
      },
      loading: false,
    }
  },
  created(){
    this.getId();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          this.loading = true;
          requestAjax({
            url: "/StuSet/HealthModel.php",
            type: 'post',
            data:{
              type: "add_model",
              arr: arr,
            },
            async: true,
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，添加卫生考勤模块成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加卫生考勤模块 "+ this.sel_add.health_id,
                  type: "添加记录",
                  model: "卫生考勤模块",
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
                message: '服务器有误！,请稍后再试！'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    getId(){
      this.loading = true;
      requestAjax({
        url: "/StuSet/HealthModel.php",
        type: 'get',
        data:{
          type: "get_model_id",
          campus: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel_add.health_id = res;
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