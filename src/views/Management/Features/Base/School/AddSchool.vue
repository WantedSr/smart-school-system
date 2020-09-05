<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>添加学校信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="100px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="school_id" label="学校代码">
          <el-input style="width: 250px" v-model="sel_add.school_id" placeholder="校区代码"></el-input>
        </el-form-item>
        <el-form-item prop="school_name" label="学校名称">
          <el-input style="width: 250px" v-model="sel_add.school_name" placeholder="校区名称"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onAdd('sel_add')">添加</el-button>
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
      sel_add:{
        school_id: "",
        school_name: "",
      },
      rules:{
        school_name: [
          { required: true, message: '请输入学校名称', trigger: 'blur' },
        ],
        school_id: [
          { required: true, message: '请选择学校代码', trigger: 'blur' }
        ],
      }
    }
  },
  created(){
    requestAjax({
      url: "/base/school.php",
      type: 'get',
      data:{
        type: "sel_school",
        col: "COUNT(*)"
      },
      async: true,
      success:(res)=>{
        res = parseInt(JSON.parse(res)[0]['COUNT(*)']) + 1;
        if(String(res).length == 1){
          res = '000'+res;
        }else if(String(res).length == 2){
          res = '00'+res
        }else if(String(res).length == 3){
          res = '0'+res;
        }
        // console.log(res);
        this.sel_add.school_id = 'S'+res;
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
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          requestAjax({
            type: "get",
            url: "/base/school.php",
            data: {
              type: "add_school",
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                // console.log(res);
                this.$message({
                  message: '恭喜你，添加学校成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加学校信息 "+ this.sel_add.school_id,
                  type: "添加记录",
                  model: "学校模块",
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