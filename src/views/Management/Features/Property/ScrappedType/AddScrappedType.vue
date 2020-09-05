<template>
  <div class="purchase">
    <div class="pagehead">
      <h1>添加资产报废情况信息</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="type_id" label="报废类型编号">
          <el-input placeholder="请输入报废类型编号" v-model="form.type_id"></el-input>
        </el-form-item>
        <el-form-item prop="type_name" label="报废类型名称">
          <el-input placeholder="请输入报废类型名称" v-model="form.type_name"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">立即创建</el-button>
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
      form:{
        type_id: "",
        type_name: "",
        created_user: this.$store.state.userId,
      },
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
      url: "/property/scrappedType.php",
      data:{
        type: "get_scrapped_type_id",
      },
      success:(res)=>{
        res = JSON.parse(res);
        this.form.type_id = res;
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

          let arr = Object.values(this.form);
          let addTime = new Date().getTime();
          arr.push(addTime);

          requestAjax({
            url: "/property/scrappedType.php",
            type: 'get',
            data:{
              type: "add_scrapped_type",
              arr: arr,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '报废类型添加成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加报废类型:" + this.form.type_name + " 报废类型id:" + this.form.type_id,
                  type: "添加记录",
                  model: "报废类型模块",
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