<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>修改卫生评分项目</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="name" label="选项名称">
          <el-input size="small" style="width: 250px" v-model="sel_upa.name" placeholder="选项名称"></el-input>
        </el-form-item>
        <el-form-item prop="score" label="选项分数">
          <el-input size="small" type="number" style="width: 250px" v-model="sel_upa.score" placeholder="选项分数"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onAdd('sel_upa')">修改</el-button>
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
      sel_upa:{},
      rules:{
        name: [
          { required: true, message: '请输入选项名称', trigger: 'blur' },
        ],
        score: [
          { required: true, message: '请输入选项分数', trigger: 'blur' }
        ],
      },
      loading:false,
    }
  },
  props:{
    modelData:{
      type: Array,
      require: true,
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      url: '/StuSet/HealthOption.php',
      type: 'get',
      data:{
        type: "sel_option_list",
        selobj:{
          id: this.$route.query.listId,
        },
      },
      success:(res)=>{
        res = JSON.parse(res)[0];
        this.loading = false;
        this.sel_upa = res;
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {


          this.loading = true;
          requestAjax({
            url: "/StuSet/HealthOption.php",
            type: 'post',
            data:{
              type: "upa_option_list",
              obj: this.sel_upa,
              sel: 'id',
              val: this.sel_upa.id,
            },
            async: true,
            success:(res)=>{

              // console.log(arr);

              this.loading = false;
              res = JSON.parse(res);

              if(res){
                // console.log(res);
                this.$message({
                  message: '恭喜你，添加考勤选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改卫生选项 "+ this.sel_upa.id,
                  type: "修改记录",
                  model: "卫生选项模块",
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
        url: "/StuSet/HealthOption.php",
        type: 'get',
        data:{
          type: "get_option_id",
          campus: this.$store.state.userCampus,
          model: this.sel_upa.model,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel_upa.option_id = res;
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