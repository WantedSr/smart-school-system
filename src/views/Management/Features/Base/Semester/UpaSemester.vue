<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑学期信息</h1>
    </div>
    
    <div class="sel_add" v-if="sel_upa != ''">
      <el-form :rules="rules" ref="sel_upa" label-position="left" label-width="110px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="semesterId" label="学期代码">
          <el-input style="width: 250px" v-model="sel_upa.semesterId" placeholder="学期代码"></el-input>
        </el-form-item>
        <el-form-item prop="semester" label="学期名称">
          <el-input style="width: 250px" v-model="sel_upa.semester" placeholder="学期名称"></el-input>
        </el-form-item>
        <el-form-item prop="administrative_start" label="行政开始时间">
          <el-date-picker type="date" value-format="timestamp" placeholder="选择行政开始时间" v-model="sel_upa.administrative_start" style="width: 250px;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="administrative_end" label="行政结束时间">
          <el-date-picker type="date" value-format="timestamp" placeholder="选择行政结束时间" v-model="sel_upa.administrative_end" style="width: 250px;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="teach_start" label="教学开始时间">
          <el-date-picker type="date" value-format="timestamp" placeholder="选择教学开始时间" v-model="sel_upa.teach_start" style="width: 250px;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="teach_end" label="教学结束时间">
          <el-date-picker type="date" value-format="timestamp" placeholder="选择教学结束时间" v-model="sel_upa.teach_end" style="width: 250px;"></el-date-picker>
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
      sel_upa: "",
      rules:{
        semester: [
          { required: true, message: '请输入学期名称', trigger: 'blur' },
        ],
        semesterId: [
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
      },
    }
  },
  created(){
    let id = this.$route.query.semId;
    requestAjax({
      url: "/base/semester.php",
      type: 'get',
      data:{
        type: "sel_semester",
        selobj: {
          semesterId: id,
          campus: this.$store.state.userCampus,
        },
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
      for(let i in this.sel_upa){
        if(this.sel_upa[i] == ''){
          this.$message.error('所有选项不能为空!');
          return false;
        }
      }
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "upa_semester",
          obj: this.sel_upa,
          sel: "id",
          val: this.sel_upa.id,
        },
        success:(res)=>{
          if(res){
            this.$message({
              message: '恭喜你，修改学期信息成功',
              type: 'success'
            });

            // 日志写入
            let obj = {
              content: "编辑学期信息 "+this.sel_upa.semesterId,
              type: "修改记录",
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

          }else{
            this.$message.error('修改学期信息失败，请稍后再试！');
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
    },
  }
}
</script>

<style>

</style>