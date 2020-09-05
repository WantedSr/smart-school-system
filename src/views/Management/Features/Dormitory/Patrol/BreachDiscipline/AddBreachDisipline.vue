<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>添加宿舍考勤选项</h1>
    </div>
    
    <div class="sel">
      <el-form label-position="left" ref="sel" :rules="rules" label-width="100px" :model="sel" class="demo-form-inline">
        <el-form-item prop="option_id" label="选项代码">
          <el-input size="small" style="width: 250px" v-model="sel.option_id" placeholder="选项代码"></el-input>
        </el-form-item>
        <el-form-item prop="option_name" label="选项名称">
          <el-input size="small" style="width: 250px" v-model="sel.option_name" placeholder="选项名称"></el-input>
        </el-form-item>
        <el-form-item prop="type" label="选项类型">
          <el-select size="small" v-model="sel.type" placeholder="选项类型">
            <el-option label="出勤状态" value="attendance"></el-option>
            <el-option label="违纪情况" value="discipline"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="score" label="分数">
          <el-input size="small" type="number" style="width: 400px" v-model="sel.score"></el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input size="small" type="textarea" style="width: 400px" v-model="sel.remark" max="100" placeholder="备注,字数限制100字内"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onAdd('sel')">添加</el-button>
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
      sel:{
        option_id: "",
        option_name: "",
        type: "",
        score: 0,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        state: '1',
        status: '1',
        remark: "",
        created_user: this.$store.state.userId,
      },

      rules:{
        option_name: [
          { required: true, message: '请输入选项名称', trigger: 'blur' },
        ],
        option_id: [
          { required: true, message: '请选择选项代码', trigger: 'blur' }
        ],
        score: [
          { required: true, message: '请输入考勤分数', trigger: 'blur' }
        ],
        type: [
          { required: true, message: '请选择选项类型', trigger: 'change' }
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
          let arr = Object.values(this.sel);
          arr.push(new Date().getTime());
          this.loading = true;
          requestAjax({
            url: "/Dormitory/patrolOption.php",
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
                this.$message({
                  message: '恭喜你，添加宿舍考勤选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加宿舍考勤选项 "+ this.sel.option_id,
                  type: "添加记录",
                  model: "宿舍考勤选项模块",
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
        url: "/Dormitory/patrolOption.php",
        type: 'get',
        data:{
          type: "get_option_id",
          campus: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel.option_id = res;
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