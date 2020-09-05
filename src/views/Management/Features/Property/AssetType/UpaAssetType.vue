<template>
  <div class="purchase">
    <div class="pagehead">
      <h1>编辑资产类型信息</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="type_id" label="资产类型编号">
          <el-input placeholder="请输入资产类型编号" v-model="form.type_id"></el-input>
        </el-form-item>
        <el-form-item prop="type_name" label="资产类型名称">
          <el-input placeholder="请输入资产类型名称" v-model="form.type_name"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">修改</el-button>
          <el-button @click="onBack">取消</el-button>
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
      form:{},
      rules:{
        type_id: [
          { required: true, message: '请输入资产类型编号', trigger: 'blur' },
        ],
        type_name: [
          { required: true, message: '请输入资料类型名称', trigger: 'blur' },
        ],
      },
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      type: "get",
      url: "/property/assetType.php",
      data:{
        type: "sel_asset_type",
        selobj:{
          'id' : this.$route.query.typeId,
        }
      },
      success:(res)=>{
        res = JSON.parse(res)[0];
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
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/property/assetType.php",
            type: 'get',
            data:{
              type: "upa_asset_type",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '资产类型修改成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改资产类型:" + this.form.type_name + " 资产类型id:" + this.form.type_id,
                  type: "修改记录",
                  model: "资产类型模块",
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
                this.$message.error('登记失败，请稍后再试！');
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
          });
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
  .line{
    text-align: center;
  }
</style>