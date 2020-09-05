<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>添加课程类型</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="110px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="course_id" label="课程类型代码">
          <el-input style="width: 250px" v-model="sel_add.course_id" placeholder="课程类型代码"></el-input>
        </el-form-item>
        <el-form-item prop="course_name" label="课程类型名称">
          <el-input style="width: 250px" v-model="sel_add.course_name" placeholder="课程类型名称"></el-input>
        </el-form-item>
        <el-form-item prop="course_score" label="课程学分">
          <el-input style="width: 250px" v-model="sel_add.course_score" placeholder="课程学分"></el-input>
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
        course_id: "",
        course_name: "",
        course_score: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        status: '1',
        created_user: this.$store.state.userId,
      },
      rules:{
        course_name: [
          { required: true, message: '请输入课程类型名称', trigger: 'blur' },
        ],
        course_id: [
          { required: true, message: '请选择课程类型代码', trigger: 'blur' }
        ],
        course_score: [
          {required: true, message: '请选择作息开始时间', trigger: 'blur' }
        ],
      }
    }
  },
  created(){
    requestAjax({
      url: "/base/courseCredit.php",
      type: 'get',
      data:{
        type: "get_course_credit_id",
      },
      async: true,
      success:(res)=>{
        res = JSON.parse(res);
        this.sel_add.course_id = "C" + (parseInt(res) + 1);
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
            url: "/base/courseCredit.php",
            data: {
              type: "add_course_credit",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，添加课程类型成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加课程类型信息 "+ this.sel_add.course_id,
                  type: "添加记录",
                  model: "课程类型模块",
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
    }
  }
}
</script>

<style>

</style>