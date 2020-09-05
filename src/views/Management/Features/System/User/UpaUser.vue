<template>
  <div>
    <div class="pagehead">
      <h1>添加用户</h1>
    </div>
    <el-form :rules="rules" ref="form" :model="form" label-width="100px">
      <el-form-item prop="user_id" label="用户id">
        <el-input v-model="form.user_id" placeholder="请输入用户id"></el-input>
      </el-form-item>
      <el-form-item prop="user_name" label="真实姓名">
        <el-input v-model="form.user_name" placeholder="请输入真实姓名"></el-input>
      </el-form-item>
      <el-form-item prop="user_phone" label="电话号码">
        <el-input v-model="form.user_phone" placeholder="请输入电话号码"></el-input>
      </el-form-item>
      <el-form-item prop="user_group" label="用户组">
        <el-select v-model="form.user_group" placeholder="用户组">
          <el-option v-for="(item,i) in authData" :key="'1-'+i" :label="item.authority_name" :value="item.authority_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="user_school" label="学校">
        <el-select @change="selCam" v-model="form.user_school" placeholder="学校">
          <el-option v-for="(item,i) in schData" :key="'2-'+i" :label="item.school_name" :value="item.school_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="user_campus" label="校区">
        <el-select @change="selDep" v-model="form.user_campus" placeholder="校区">
          <el-option v-for="(item,i) in camData" :key="'3-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onUpa('form')">修改</el-button>
        <el-button @click="onBack">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form:{
        user_id: "",
        user_name: "",
        password: "",
        user_phone: null,
        user_group: "",
        user_school: "",
        user_campus: "",
        user_wxid: "",
        user_ddid: "",
        created_user: this.$store.state.userId,
      },
      rules:{
        user_name: [
          { required: true, message: '请输入用户名称', trigger: 'blur' },
        ],
        user_id: [
          { required: true, message: '请选择用户代码', trigger: 'blur' }
        ],
        user_phone: [
          { required: true, message: '请选择用户电话号码', trigger: 'blur' }
        ],
        user_group: [
          { required: true, message: '请选择用户组', trigger: 'change' },
        ],
        user_school: [
          { required: true, message: '请选择用户所在学校', trigger: 'change' }
        ],
        user_campus: [
          { required: true, message: '请选择用户所在校区', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    requestAjax({
      type: "get",
      url: "/system/user.php",
      data:{
        type: "sel_user",
        selobj: {"user_id":this.$route.query.userId},
      },
      async: false,
      success:(res)=>{
        res = JSON.parse(res)[0];
        this.form = res;
      },
      error:(err)=>{
        console.log(err);
          this.$notify.error({
          title: '错误',
          message: '服务器有误！,请稍后再试！'
        });
      }
    });
    let cam = this.form.user_campus;
    let dep = this.form.user_dpmentid
    this.selCam(this.form.user_school);
    this.form.user_campus = cam;
    this.selDep(this.form.user_campus);
    this.form.user_dpmentid = dep;
  },
  props:{
    schData:{
      type: Array,
      require: true,
    },
    camData:{
      type: Array,
      require: true,
    },
    depData:{
      type: Array,
      require: true,
    },
    authData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.form);
          arr.push(new Date().getTime());
          requestAjax({
            type: "get",
            url: "/system/user.php",
            data:{
              type: 'upa_user',
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              res = JSON.parse(res);
              console.log(res);
              if(res){
                this.$message({
                  message: '修改成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改用户 "+this.form.user_id,
                  type: "修改记录",
                  model: "用户模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

              }else{
                this.$message.error('修改失败，请稍后再试！');
              }
              this.$router.go(-1);
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
    },
    selCam(v){
      this.form.user_dpmentid = "";
      this.form.user_campus = "";
      this.$emit("selCam",v);
    },
    selDep(v){
      this.form.user_dpmentid = '';
      this.$emit("selDep",v);
    }
  }
}
</script>

<style>

</style>