<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>添加年级信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="110px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="grade_id" label="年级代码">
          <el-input style="width: 250px" v-model="sel_add.grade_id" placeholder="年级代码"></el-input>
        </el-form-item>
        <el-form-item prop="grade_name" label="年级名称">
          <el-input style="width: 250px" v-model="sel_add.grade_name" placeholder="年级名称"></el-input>
        </el-form-item>
        <el-form-item prop="state" label="状态">
          <el-select v-model="sel_add.state" placeholder="请选择年级状态">
            <el-option label="在读" value="1"></el-option>
            <el-option label="毕业" value="0"></el-option>
          </el-select>
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
        grade_id: new Date().getFullYear(),
        grade_name: "",
        state: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        status: '1',
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
      },
    }
  },
  computed:{
  },
  methods:{
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          requestAjax({
            type: "get",
            url: "/base/grade.php",
            data: {
              type: "add_grade",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                console.log(res);
                this.$message({
                  message: '恭喜你，添加年级成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加年级信息 "+ this.sel_add.grade_id,
                  type: "添加记录",
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
    onBack(){
      this.$router.go(-1);
    },
  }
}
</script>

<style>

</style>