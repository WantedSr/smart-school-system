<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑卫生模块</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="health_id" label="模块代码">
          <el-input style="width: 250px" v-model="sel_upa.health_id" placeholder="模块代码"></el-input>
        </el-form-item>
        <el-form-item prop="health_name" label="模块名称">
          <el-input style="width: 250px" v-model="sel_upa.health_name" placeholder="模块名称"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onUpa('sel_upa')">修改</el-button>
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
      sel_upa:{
        model_id: "",
        model_name: "",
      },
      rules:{
        model_name: [
          { required: true, message: '请输入模块名称', trigger: 'blur' },
        ],
        model_id: [
          { required: true, message: '请选择模块代码', trigger: 'blur' }
        ],
      }
    }
  },
  created(){
    this.getData({
      'campus':this.$store.state.userCampus,
      'healthId': this.$route.query.optionId,
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
            url: "/StuSet/HealthModel.php",
            type: 'post',
            data:{
              type: "upa_model",
              obj: this.sel_upa,
              sel: 'id',
              val: this.sel_upa.id,
            },
            async: true,
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，修改卫生模块选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改卫生模块选项 "+ this.sel_upa.health_id,
                  type: "修改记录",
                  model: "卫生模块",
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
    getData(selobj=null){
      requestAjax({
        url: "/StuSet/HealthModel.php",
        type: 'get',
        data:{
          type: "sel_model",
          col: "*",
          selobj: selobj
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.sel_upa = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
  }
}
</script>

<style>

</style>