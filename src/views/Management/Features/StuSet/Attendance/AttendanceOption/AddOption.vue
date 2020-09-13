<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>添加考勤选项</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="100px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="option_id" label="选项代码">
          <el-input size="small" style="width: 250px" v-model="sel_add.option_id" placeholder="选项代码"></el-input>
        </el-form-item>
        <el-form-item prop="option_name" label="选项名称">
          <el-input size="small" style="width: 250px" v-model="sel_add.option_name" placeholder="选项名称"></el-input>
        </el-form-item>
        <el-form-item prop="model" label="隶属模块">
          <el-select size="small" v-model="sel_add.model" placeholder="隶属模块">
            <el-option label="课堂登记" value="课堂登记"></el-option>
            <el-option label="早段登记" value="早段登记"></el-option>
            <el-option label="课间操登记" value="课间操登记"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="type" label="选项类型">
          <el-select size="small" v-model="sel_add.type" placeholder="选项类型">
            <el-option label="出勤状态" value="attendance"></el-option>
            <el-option label="违纪情况" value="discipline"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop='score' label="分值">
          <el-input size="small" type="number" style="width: 400px" v-model="sel_add.score" max="100" placeholder="输入分值"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onAdd('sel_add')">添加</el-button>
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
      sel_add:{
        option_id: "",
        option_name: "",
        model: "",
        type: "",
        score: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        state: '1',
        status: '1',
        created_user: this.$store.state.userId,
      },
      rules:{
        option_name: [
          { required: true, message: '请输入选项名称', trigger: 'blur' },
        ],
        option_id: [
          { required: true, message: '请选择选项代码', trigger: 'blur' }
        ],
        model: [
          { required: true, message: '请选择选项隶属模块', trigger: 'change' }
        ],
        type: [
          { required: true, message: '请选择选项类型', trigger: 'change' }
        ],
        score: [
          { required: true, message: '请输入对应分值', trigger: 'blur' }
        ],
      },
      loading:false,
    }
  },
  created(){
    this.getId();
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
          this.loading = true;
          requestAjax({
            url: "/StuSet/AttendanceOption.php",
            type: 'post',
            data:{
              type: "add_option",
              arr: arr,
            },
            async: true,
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                // console.log(res);
                this.$message({
                  message: '恭喜你，添加考勤选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加考勤选项 "+ this.sel_add.option_id,
                  type: "添加记录",
                  model: "考勤选项模块",
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
    getId(){
      this.loading = true;
      requestAjax({
        url: "/StuSet/AttendanceOption.php",
        type: 'get',
        data:{
          type: "get_option_id",
          campus: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel_add.option_id = res;
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
    }
  }
}
</script>

<style>

</style>