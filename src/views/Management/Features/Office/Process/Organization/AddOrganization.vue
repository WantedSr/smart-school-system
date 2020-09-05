<template>
  <div v-loading="loading">
    <div class="pagehead">
      <h1>添加组织架构</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="organization_name" label="组织架构名称">
          <el-input size="small" v-model="form.organization_name" placeholder="请输入审批流程名称"></el-input>
        </el-form-item>
        <el-form-item prop="department" label="所属部门">
          <el-select size="small" v-model="form.department" placeholder="选择部门">
            <el-option v-for="(item,i) in depData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onAdd('form')">添加</el-button>
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
      form:{
        organization_id: "",
        organization_name: "",
        department: "",
        sponsor: "",
        department_responsible: "",
        course_responsible: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },
      
      rules:{
        organization_name: [
          { required: true, message: '请输入组织架构名称', trigger: 'blur' },
        ],
        department: [
          { required: true, message: '请选择部门', trigger: 'change' },
        ],
      },

      loading: false,
    }
  },
  props:{
    depData:{
      type: Array,
      require: true,
    }
  },  
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.form.addTime = new Date().getTime();
          let arr = Object.values(this.form);
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/Office/Process/Organization.php",
            data: {
              type: "add_organization",
              arr: arr,
              campus: this.$store.state.userCampus,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              // console.log(res);
              if(res){
                this.$message({
                  message: '恭喜你，添加组织架构成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加组织架构信息 "+ JSON.stringify(this.form),
                  type: "添加记录",
                  model: "组织架构模块",
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
  }
}
</script>

<style>

</style>