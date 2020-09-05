<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑年级信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="110px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="grade_id" label="年级代码">
          <el-input style="width: 250px" v-model="sel_upa.grade_id" placeholder="学期代码"></el-input>
        </el-form-item>
        <el-form-item prop="grade_name" label="年级名称">
          <el-input style="width: 250px" v-model="sel_upa.grade_name" placeholder="学期名称"></el-input>
        </el-form-item>
        <el-form-item prop="state" label="状态">
          <el-select v-model="sel_upa.state" placeholder="请选择年级状态">
            <el-option label="在读" value="1"></el-option>
            <el-option label="毕业" value="0"></el-option>
          </el-select>
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
        grade_id: "",
        grade_name: "",
        state: "",
      },
      rules:{
        grade_name: [
          { required: true, message: '请输入年级名称', trigger: 'blur' },
        ],
        grade_id: [
          { required: true, message: '请选择年级代码', trigger: 'blur' }
        ],
        state: [
          {required: true, message: '请选择年级状态', trigger: 'change' }
        ],
      }
    }
  },
  created(){
    let id = this.$route.query.gradeId;
    requestAjax({
      url: "/base/grade.php",
      type: 'get',
      data:{
        type: "sel_grade",
        sel: "grade_id",
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
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/grade.php",
            type: 'get',
            data:{
              type: "upa_grade",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改年级信息成功',
                  type: 'success'
                });
                
                // 日志写入
                let obj = {
                  content: "修改年级信息 "+ this.sel_upa.grade_id,
                  type: "修改记录",
                  model: "年级模块",
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
                this.$message.error('修改年级信息失败，请稍后再试！');
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
    onBack(){
      this.$router.go(-1);
    },
  }
}
</script>

<style>

</style>