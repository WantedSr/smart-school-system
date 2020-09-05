<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑校区信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="school_id" label="学期代码">
          <el-input style="width: 250px" v-model="sel_upa.school_id" placeholder="学期代码"></el-input>
        </el-form-item>
        <el-form-item prop="school_name" label="学期名称">
          <el-input style="width: 250px" v-model="sel_upa.school_name" placeholder="学期名称"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onUpa('sel_upa')">修改</el-button>
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
      sel_upa:{
        school_name: "",
        school_id: "",
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
    let id = this.$route.query.schoolId;
    requestAjax({
      url: "/base/school.php",
      type: 'get',
      data:{
        type: "sel_school",
        sel: "school_id",
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
            url: "/base/school.php",
            type: 'get',
            data:{
              type: "upa_school",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改学校信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加学校信息 "+ this.sel_upa.school_id,
                  type: "修改记录",
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
              }else{
                this.$message.error('修改学校信息失败，请稍后再试！');
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