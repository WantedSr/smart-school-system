<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>编辑卫生评分项目</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="option_name" label="项目名称">
          <el-input size="small" style="width: 250px" v-model="sel_upa.option_name" placeholder="选项名称"></el-input>
        </el-form-item>
        <el-form-item prop="model" label="隶属模块">
          <el-select @change="getId" size="small" v-model="sel_upa.model" placeholder="隶属模块">
            <el-option v-for="(item,i) in modelData" :key="'model-'+i" :label="item.health_name" :value="item.health_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="option_id" label="项目代码">
          <el-input size="small" style="width: 250px" v-model="sel_upa.option_id" placeholder="选项代码"></el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input size="small" type="textarea" style="width: 400px" v-model="sel_upa.remark" max="100" placeholder="备注,字数限制100字内"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onUpa('sel_upa')">修改</el-button>
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
      },
      loading:false,
    }
  },
  props:{
    modelData:{
      type: Array,
      require: true,
    }
  },
  created(){
    requestAjax({
      url: "/StuSet/HealthOption.php",
      type: 'get',
      data:{
        type: "sel_option",
        selobj: {
          campus: this.$store.state.userCampus,
          option_id: this.$route.query.optionId,
        }
      },
      async: true,
      success:(res)=>{
        res = JSON.parse(res)[0];
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
          this.loading = true;
          requestAjax({
            url: "/StuSet/HealthOption.php",
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
                // console.log(res);
                this.$message({
                  message: '恭喜你，添加考勤选项成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加考勤选项 "+ this.sel_upa.option_id,
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
        url: "/StuSet/HealthOption.php",
        type: 'get',
        data:{
          type: "get_option_id",
          campus: this.$store.state.userCampus,
          model: this.sel_upa.model,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel_upa.option_id = res;
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