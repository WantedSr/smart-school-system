<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑课程类型</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="110px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="course_id" label="课程类型代码">
          <el-input style="width: 250px" v-model="sel_upa.course_id" placeholder="课程类型代码"></el-input>
        </el-form-item>
        <el-form-item prop="course_name" label="课程类型名称">
          <el-input style="width: 250px" v-model="sel_upa.course_name" placeholder="课程类型名称"></el-input>
        </el-form-item>
        <el-form-item prop="course_score" label="课程学分">
          <el-input style="width: 250px" v-model="sel_upa.course_score" placeholder="课程学分"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onUpa('sel_upa')">添加</el-button>
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
      sel_upa:{},
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
    let id = this.$route.query.courseId;
    requestAjax({
      url: "/base/courseCredit.php",
      type: 'get',
      data:{
        type: "sel_course_credit",
        sel: "course_id",
        val: id,
      },
      success:(res)=>{
        res = JSON.parse(res)[0];
        // console.log(res);
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
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/courseCredit.php",
            type: 'get',
            data:{
              type: "upa_course_credit",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改课程类型成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改课程类型信息 "+ this.sel_upa.course_id,
                  type: "修改记录",
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
              }else{
                this.$message.error('修改课程类型失败，请稍后再试！');
              }
            },
            error:(err)=>{
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
  }
}
</script>

<style>

</style>