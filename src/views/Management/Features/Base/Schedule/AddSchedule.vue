<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>添加作息信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="110px" :model="sel_add" class="demo-form-inline">
        <el-form-item label="作息代码" prop="schedule_id">
          <el-input style="width: 250px" v-model="sel_add.schedule_id" placeholder="作息代码"></el-input>
        </el-form-item>
        <el-form-item label="作息名称" prop="schedule_name">
          <el-input style="width: 250px" v-model="sel_add.schedule_name" placeholder="作息名称"></el-input>
        </el-form-item>
        <el-form-item label="作息开始时间" prop="schedule_start">
          <el-time-picker format="HH:mm" value-format="HH:mm" style="width: 250px;" placeholder="选择作息开始时间" v-model="sel_add.schedule_start"></el-time-picker>
        </el-form-item>
        <el-form-item label="作息结束时间" prop="schedule_end">
          <el-time-picker format="HH:mm" value-format="HH:mm" style="width: 250px;" placeholder="选择作息结束时间" v-model="sel_add.schedule_end"></el-time-picker>
        </el-form-item>
        <el-form-item label="作息顺序" prop="schedule_order">
          <el-input style="width: 250px" v-model="sel_add.schedule_order" placeholder="作息顺序"></el-input>
        </el-form-item>
        <el-form-item label="作息类型" prop="schedule_type">
          <el-select v-model="sel_add.schedule_type" placeholder="请选择作息类型态">
            <el-option label="教学课程" value="teach"></el-option>
            <el-option label="休息时间" value="rest"></el-option>
            <el-option label="课外活动" value="between_classes"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="备注">
          <el-input type="textarea" style="width: 50%;" v-model="sel_add.remake" placeholder="备注"></el-input>
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
        schedule_id: "",
        schedule_name: "",
        schedule_start: "",
        schedule_end: "",
        schedule_order: "",
        schedule_type: "",
        remake: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId,
        addTime: "",
        status: "1"
      },
      rules:{
        schedule_name: [
          { required: true, message: '请输入作息名称', trigger: 'blur' },
        ],
        schedule_id: [
          { required: true, message: '请选择作息代码', trigger: 'blur' }
        ],
        schedule_start: [
          {required: true, message: '请选择作息开始时间', trigger: 'change' }
        ],
        schedule_end: [
          {required: true, message: '请选择作息结束时间', trigger: 'change' }
        ],
        schedule_order: [
          {required: true, message: '请选择作息顺序', trigger: 'blur' }
        ],
        schedule_type: [
          {required: true, message: '请选择作息类型', trigger: 'change' }
        ],
      }
    }
  },
  created(){
    requestAjax({
      url: "/base/schedule.php",
      type: 'get',
      data:{
        type: "get_schedule_id",
        col: "COUNT(*)",
      },
      async: true,
      success:(res)=>{
        res = JSON.parse(res);
        this.sel_add.schedule_id = "ZX" + (parseInt(res)+1);
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.sel_add.addTime = new Date().getTime();
          let arr = Object.values(this.sel_add);
          requestAjax({
            type: "get",
            url: "/base/schedule.php",
            data: {
              type: "add_schedule",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，添加学期信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加作息信息 "+ this.sel_add.schedule_id,
                  type: "添加记录",
                  model: "作息模块",
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