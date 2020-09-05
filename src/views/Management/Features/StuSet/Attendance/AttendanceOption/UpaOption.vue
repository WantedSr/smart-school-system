<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑考勤选项</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="option_id" label="选项代码">
          <el-input style="width: 250px" v-model="sel_upa.option_id" placeholder="选项代码"></el-input>
        </el-form-item>
        <el-form-item prop="option_name" label="选项名称">
          <el-input style="width: 250px" v-model="sel_upa.option_name" placeholder="选项名称"></el-input>
        </el-form-item>
        <el-form-item prop="model" label="隶属模块">
          <el-select size="small" v-model="sel_upa.model" placeholder="隶属模块">
            <el-option label="课堂登记" value="课堂登记"></el-option>
            <el-option label="早段登记" value="早段登记"></el-option>
            <el-option label="课间操登记" value="课间操登记"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="type" label="选项类型">
          <el-select size="small" v-model="sel_upa.type" placeholder="选项类型">
            <el-option label="出勤状态" value="attendance"></el-option>
            <el-option label="违纪情况" value="discipline"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="备注">
          <el-input style="width: 400px" v-model="sel_upa.remark" placeholder="备注"></el-input>
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
      sel_upa:{},
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
      }
    }
  },
  created(){
    this.getData({
      option_id: this.$route.query.optionId,
      campus: this.$store.state.userCampus,
    })
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/StuSet/AttendanceOption.php",
            type: 'post',
            data:{
              type: "upa_option",
              obj: this.sel_upa,
              sel: 'id',
              val: this.sel_upa.id,
            },
            async: true,
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，修改考勤选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改考勤选项 "+ this.sel_upa.option_id,
                  type: "修改记录",
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
                this.$message.error('修改失败，请稍后再试！');
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
    getData(selobj=null){
      this.loading = true;
      requestAjax({
        url: "/StuSet/AttendanceOption.php",
        type: 'get',
        data:{
          type: "sel_option",
          col: "*",
          selobj: selobj
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          this.sel_upa = res;
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
    },
  }
}
</script>

<style>

</style>