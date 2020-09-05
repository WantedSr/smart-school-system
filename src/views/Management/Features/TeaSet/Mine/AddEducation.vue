<template>
  <div class="upaTeacherInfo" v-loading="loading">
    <div class="pagehead">
      <h1>登记我的学历信息</h1>
    </div>
    <div class="addEducation">
      <el-form ref="form" label-position="left" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="graduated_school" label="毕业学校">
          <el-input placeholder="请填写毕业学校" v-model="form.graduated_school"></el-input>
        </el-form-item>
        <el-form-item prop="profession" label="专业">
          <el-input placeholder="请填写所学专业" v-model="form.profession"></el-input>
        </el-form-item>
        <el-form-item prop="education" label="学位">
          <el-select v-model="form.education" placeholder="请选择学位">
            <el-option label="博士" value="博士"></el-option>
            <el-option label="硕士" value="硕士"></el-option>
            <el-option label="学士/本科" value="学士/本科"></el-option>
            <el-option label="大专/专科" value="大专/专科"></el-option>
            <el-option label="中专/高中/中技" value="中专/高中/中技"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="school_system" label="学制">
          <el-select v-model="form.school_system" placeholder="请选择学制">
            <el-option label="全日制" value="全日制"></el-option>
            <el-option label="自考" value="自考"></el-option>
            <el-option label="网大" value="网大"></el-option>
            <el-option label="函授" value="函授"></el-option>
            <el-option label="夜大" value="夜大"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="graduated_time" label="毕业时间">
          <el-date-picker
            v-model="form.graduated_time"
            type="date"
            placeholder="选择日期"
            format="yyyy 年 MM 月 dd 日"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
        <el-form-item prop="witness" label="证明人">
          <el-input placeholder="请填写证明人" v-model="form.witness"></el-input>
        </el-form-item>
        <el-form-item label="毕业证书图片">
          <el-upload
            class="upload-demo"
            ref="upload"
            action="https://jsonplaceholder.typicode.com/posts/"
            :on-preview="handlePreview"
            :on-remove="handleRemove"
            :limit="1"
            :auto-upload="false">
            <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
            <!-- <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传到服务器</el-button> -->
            <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
          </el-upload>
        </el-form-item>
        <el-form-item prop="certificate_id" label="毕业证书编号">
          <el-input placeholder="请填写毕业证书编号" v-model="form.certificate_id"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onUpa('form')">登记</el-button>
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
      form:{
        teacher_name: this.$store.state.userId,
        graduated_school: "",
        profession: "",
        education: "",
        school_system: "",
        witness: "",
        certificate_img: "",
        certificate_id: "",
        graduated_time: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId,
      },
      loading: false,

      rules:{
        graduated_school: [
          { required: true, message: '请输入毕业学校', trigger: 'blur' }
        ],
        profession: [
          { required: true, message: '请输入所学专业', trigger: 'blur' }
        ],
        witness: [
          { required: true, message: '请输入证明人', trigger: 'blur' }
        ],
        education: [
          { required: true, message: '请选择学位', trigger: 'change' }
        ],
        school_system: [
          { required: true, message: '请选择学制', trigger: 'change' }
        ],
        graduated_time: [
          { required: true, message: '请选择毕业时间', trigger: 'change'}
        ],
        certificate_img: [
          { required: true, message: '请选择证书图片', trigger: 'change' }
        ],
        certificate_id: [
          { required: true, message: '请输入证书编码', trigger: 'blur' }
        ],
      },
    }
  },
  created(){
    this.$store.commit("getUser");
  },
  methods:{
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
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
            url: "/TeaManagement/TeaInfo/TeaEducation.php",
            data:{
              type: "add_tea",
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '教师学位登记成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "教师学历登记",
                  type: "添加记录",
                  model: "我的学历模块",
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
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
    }
  }
}
</script>

<style scoped>
  .addEducation{
    padding: 0 12px;
  }
</style>