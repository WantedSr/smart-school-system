<template>
  <div class="upaTeacherInfo" v-loading="loading">
    <div class="pagehead">
      <h1>编辑教师资格证书信息</h1>
    </div>
    <div>
      <el-form ref="form" label-position="left" :rules="rules" :model="form" label-width="110px">
        <el-form-item prop="teacher_name" label="教师">
          <el-select v-model="form.teacher_name" placeholder="请选择教师">            
            <el-option v-for="(item,i) in teacherData" :key="i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="qualification_name" label="证书名称">
          <el-input placeholder="请填写资格证书名称" v-model="form.qualification_name"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_id" label="证书编号">
          <el-input placeholder="请填写资格证书编号" v-model="form.qualification_id"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_profession" label="所授专业">
          <el-input placeholder="请填写教授专业" v-model="form.qualification_profession"></el-input>
        </el-form-item>
        <el-form-item prop="qualification_type" label="所授专业">
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
        <el-form-item prop="qualification_img" label="资格证书图片">
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
          <el-button type="primary" @click="onUpa('form')">编辑</el-button>
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
      form:{},
      loading: false,

      teacherData: [],
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
    this.getData();
    this.getTeaData();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaQualification.php",
        data:{
          type: "sel_tea",
          selobj: {
            'id':this.$route.query.quaId,
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          // console.log(res);
          this.loading = false;
          this.form = res;
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
    getTeaData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "userid,username",
          selobj: {
            'campus':this.$store.state.userCampus,
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.teacherData = res;
          // console.log(this.teacherData);
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
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            type: "get",
            url: "/TeaManagement/TeaInfo/TeaQualification.php",
            data:{
              type: "upa_tea",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              // console.log(res);
              if(res){
                this.$message({
                  message: '修改教师资格证书信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改教师学资格证书信息 "+this.form.userid,
                  type: "修改记录",
                  model: "教师资格证书模块",
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

<style>

</style>