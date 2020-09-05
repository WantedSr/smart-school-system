<template>
  <div class="upaTeacherInfo" v-loading="loading">
    <div class="pagehead">
      <h1>登记我的教师资格证</h1>
    </div>
    <div class="addQualification">
      <el-form ref="form" label-position="left" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="qualification_name" label="证书名称">
          <el-input placeholder="请填写资格证书名称" v-model="form.qualification_name"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_id" label="证书编号">
          <el-input placeholder="请填写资格证书编号" v-model="form.qualification_id"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_profession" label="所授专业">
          <el-input placeholder="请填写教授专业" v-model="form.qualification_profession"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_type" label="证书类型">
          <el-input placeholder="请填写证书类型" v-model="form.qualification_type"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_mechanism" label="颁发机构">
          <el-input placeholder="请填写颁发机构" v-model="form.qualification_mechanism"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_time" label="获得时间">
          <el-date-picker
            v-model="form.qualification_time"
            type="date"
            placeholder="选择日期"
            format="yyyy 年 MM 月 dd 日"
            value-format="yyyy-MM-dd">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="资格证书图片">
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
        <el-form-item>
          <el-button type="primary" @click="onAdd('form')">添加</el-button>
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
        qualification_name: "",
        qualification_id: "",
        qualification_profession: "",
        qualification_type: "",
        qualification_mechanism: "",
        qualification_time: "",
        qualification_img: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId,
      },
      loading: false,

      rules:{
        teacher_name: [
          { required: true, message: '请输入教师名称', trigger: 'change' },
        ],
        qualification_name: [
          { required: true, message: '请输入资格证书名称', trigger: 'blur' }
        ],
        qualification_id: [
          { required: true, message: '请输入资格证书编号', trigger: 'blur' }
        ],
        qualification_profession: [
          { required: true, message: '请输入教授专业', trigger: 'blur' }
        ],
        qualification_type: [
          { required: true, message: '请输入证书类型', trigger: 'blur' }
        ],
        qualification_mechanism: [
          { required: true, message: '请输入颁发机构', trigger: 'blur'}
        ],
        qualification_time: [
          { required: true, message: '请选择获得日期', trigger: 'change' }
        ],
        qualification_img: [
          { required: true, message: '请选择证书图片', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.$store.commit("getUser");
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

          requestAjax({
            type: "get",
            url: "/TeaManagement/TeaInfo/TeaQualification.php",
            data:{
              type: "add_tea",
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '教师资格证书登记成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "教师资格证书登记",
                  type: "添加记录",
                  model: "我的证书模块",
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
  .addQualification{
    padding: 0 12px;
  }
</style>