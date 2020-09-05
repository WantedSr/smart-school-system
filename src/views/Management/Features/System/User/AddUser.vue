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
        <el-button type="primary" @click="onAdd('form')">立即创建</el-button>
        <el-button @click="onBack">取消</el-button>
      </el-form-item>
    </el-form>
    <el-alert
      title="提示：新创建的用户统一密码都是123456，请及时修改！"
      type="info"
      :closable="false"
      show-icon>
    </el-alert>
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
        created_user: this.$store.state.userId
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
      teaForm:{
        userid: '',
        username: '',
        sex: "",
        nation: "",
        tea_phone: "",
        user_ID:"",
        job: '',
        school: '',
        campus: '',
        dpmentid: '0',
        tea_bd: "",
        china_m: "",
        hour_province: "",
        hour_city: "",
        hour_area: "",
        add_province: "",
        add_city: "",
        add_area: "",
        add_detailed: "",
        msgSet: "1,1,1",
        type: "1",
        created_user: this.$store.state.userId,
      },
      stuForm:{
        userid: '',
        username: '',
        sex: "",
        nature: "",
        school: '',
        campus: '',
        grade: "",
        dpmentid: "",
        skill: "",
        class: "",
        add_province: "",
        add_city: "",
        add_area: "",
        add_detailed: "",
        hour_province: "",
        hour_city: "",
        hour_area: "",
        hour_type: "",
        p_me: "",
        p_home: "",
        p_par: "",
        bd: "",
        nation: "",
        china_m: "",
        exam: "",
        job: "",
        type: "",
        p_name: "",
        user_ID:"",
        eva: "",
        msgSet: "1,1",
        created_user: this.$store.state.userId,
      },
    }
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {

          let arr = Object.values(this.form);
          arr.push(new Date().getTime());

          this.stuForm.userid = this.teaForm.userid = this.form.user_id;
          this.stuForm.username = this.teaForm.username = this.form.user_name;
          this.stuForm.school = this.teaForm.school = this.form.user_school;
          this.stuForm.campus = this.teaForm.campus = this.form.user_campus;
          this.stuForm.job = this.teaForm.job = this.form.user_group;

          if(this.form.user_group.indexOf("S") != -1){
            let stuArr = Object.values(this.stuForm);
            stuArr.push(new Date().getTime());
            requestAjax({
              type: "get",
              url: "/StuManagement/StuInfo/StuLibrary.php",
              data:{
                type: "add_stu",
                arr: stuArr,
              },
              success:(res)=>{
                res = JSON.parse(res);
                if(res){
                  this.$message({
                    message: '添加学生信息成功',
                    type: 'success'
                  });
                }
                else{
                  this.$message.error('添加学生失败，请稍后再试！');
                }
              },
            });
          }else{
            let teaArr = Object.values(this.teaForm);
            teaArr.push(new Date().getTime());
            requestAjax({
              type: "get",
              url: "/TeaManagement/TeaInfo/TeaLibrary.php",
              data:{
                type: "add_tea",
                arr: teaArr,
              },
              success:(res)=>{
                if(res){
                  this.$message({
                    message: '添加教师信息成功',
                    type: 'success'
                  });
                }
                else{
                  this.$message.error('添加教师失败，请稍后再试！');
                }
              },
            });
          }

          requestAjax({
            type: "get",
            url: "/system/user.php",
            data:{
              type: 'add_user',
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '添加用户成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加用户 "+this.form.user_id,
                  type: "添加记录",
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
                this.$message.error('添加失败，请稍后再试！');
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
      this.form.user_campus = "";
      this.$emit("selCam",v);
    },
  }
}
</script>

<style>

</style>