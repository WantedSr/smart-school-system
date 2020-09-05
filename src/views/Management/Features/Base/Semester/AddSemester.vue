<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>添加学期信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="110px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="semester_id" label="学期代码">
          <el-input style="width: 250px" v-model="sel_add.semester_id" placeholder="学期代码"></el-input>
        </el-form-item>
        <el-form-item prop="semester_name" label="学期名称">
          <el-input style="width: 250px" v-model="sel_add.semester_name" placeholder="学期名称"></el-input>
        </el-form-item>
        <el-form-item prop="administrative_start" label="行政开始时间">
          <el-date-picker value-format="timestamp" type="date" placeholder="选择行政开始时间" v-model="sel_add.administrative_start" style="width: 250px;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="administrative_end" label="行政结束时间">
          <el-date-picker value-format="timestamp" type="date" placeholder="选择行政结束时间" v-model="sel_add.administrative_end" style="width: 250px;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="teach_start" label="教学开始时间">
          <el-date-picker value-format="timestamp" type="date" placeholder="选择教学开始时间" v-model="sel_add.teach_start" style="width: 250px;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="teach_end" label="教学结束时间">
          <el-date-picker value-format="timestamp" type="date" placeholder="选择教学结束时间" v-model="sel_add.teach_end" style="width: 250px;"></el-date-picker>
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
        semester_id: "",
        semester_name: "",
        administrative_start: "",
        administrative_end: "",
        teach_start: "",
        teach_end: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
      },
      rules:{
        semester_name: [
          { required: true, message: '请输入学期名称', trigger: 'blur' },
        ],
        semester_id: [
          { required: true, message: '请选择学期代码', trigger: 'blur' }
        ],
        administrative_start: [
          { type: 'date', required: true, message: '请选择行政开始时间', trigger: 'change' }
        ],
        administrative_end: [
          { type: 'date', required: true, message: '请选择行政结束时间', trigger: 'change' }
        ],
        teach_start: [
          { type: 'date', required: true, message: '请选择教学开始时间', trigger: 'change' }
        ],
        teach_end: [
          { type: 'date', required: true, message: '请选择教学结束时间', trigger: 'change' }
        ],
      }
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          // console.log(this.sel_add);
          let addTime = new Date().getTime();
          let user_id = '';
          let $token = localStorage.getItem('Authorization');
          requestAjax({
            type: 'get',
            url: "/userStatus.php",
            data:{
              token: $token,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              user_id = res['info']['user_id'];
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
              });
            }
          });;
          let arr = Object.values(this.sel_add);
          arr.push(addTime);
          arr.push(user_id);
          arr.push("0");
          requestAjax({
            type: "get",
            url: "/base/semester.php",
            data: {
              type: "add_semester",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                console.log(res);
                this.$message({
                  message: '恭喜你，添加学期信息成功',
                  type: 'success'
                });
                
                // 日志写入
                let obj = {
                  content: "添加学期信息 "+this.sel_add.semester_id,
                  type: "添加记录",
                  model: "学期模块",
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