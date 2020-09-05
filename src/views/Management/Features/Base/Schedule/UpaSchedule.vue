<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑作息信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="110px" :model="sel_upa" class="demo-form-inline">
        <el-form-item label="作息代码" prop="schedule_id">
          <el-input style="width: 250px" v-model="sel_upa.schedule_id" placeholder="作息代码"></el-input>
        </el-form-item>
        <el-form-item label="作息名称" prop="schedule_name">
          <el-input style="width: 250px" v-model="sel_upa.schedule_name" placeholder="作息名称"></el-input>
        </el-form-item>
        <el-form-item label="作息开始时间" prop="schedule_start">
          <el-time-picker format="HH:mm" value-format="HH:mm" style="width: 250px;" placeholder="选择作息开始时间" v-model="sel_upa.schedule_start"></el-time-picker>
        </el-form-item>
        <el-form-item label="作息结束时间" prop="schedule_end">
          <el-time-picker format="HH:mm" value-format="HH:mm" style="width: 250px;" placeholder="选择作息结束时间" v-model="sel_upa.schedule_end"></el-time-picker>
        </el-form-item>
        <el-form-item label="作息顺序" prop="schedule_order">
          <el-input style="width: 250px" v-model="sel_upa.schedule_order" placeholder="作息顺序"></el-input>
        </el-form-item>
        <el-form-item label="作息类型" prop="schedule_type">
          <el-select v-model="sel_upa.schedule_type" placeholder="请选择作息类型态">
            <el-option label="教学课程" value="teach"></el-option>
            <el-option label="休息时间" value="rest"></el-option>
            <el-option label="课外活动" value="between_classes"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="备注">
          <el-input type="textarea" style="width: 50%;" v-model="sel_upa.remake" placeholder="备注"></el-input>
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
      },
    }
  },
  created(){
    let id = this.$route.query.scheId;
    requestAjax({
      url: "/base/schedule.php",
      type: 'get',
      data:{
        type: "sel_schedule",
        sel: "schedule_id",
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
            url: "/base/schedule.php",
            type: 'get',
            data:{
              type: "upa_schedule",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改作息信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改作息信息 "+ this.sel_upa.schedule_id,
                  type: "修改记录",
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
              }else{
                this.$message.error('修改作息信息失败，请稍后再试！');
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
    }
  }
}
</script>

<style>

</style>