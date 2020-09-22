<template>
  <div class="subpage" v-loading="loading">

    <div class="pagehead">
      <h1>通知发送</h1>
    </div>
    
    <div class="layout">
      <el-form ref="form" :rules="rules" :model="form" label-width="80px">
        <el-form-item prop="title" label="通知标题">
          <el-input size="small" v-model="form.title"></el-input>
        </el-form-item>
        <el-form-item prop="type" label="通知对象">
          <el-select @change="clearTarget" size="small" v-model="form.type" placeholder="选择通知的对象">
            <el-option label="所有人" value="all"></el-option>
            <el-option label="老师" value="teacher"></el-option>
            <el-option label="学生" value="student"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="target" label="通知范围">
          <el-select @change="clearAims" size="small" v-model="form.target" placeholder="选择通知的范围">
            <el-option v-if="form.type == 'student'" label="班级" value="class"></el-option>
            <el-option v-if="form.type == 'student'" label="年级" value="grade"></el-option>
            <el-option label="部门" value="department"></el-option>
            <el-option label="校区" value="campus"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.target == 'class'" prop="" label="选择班级">
          <el-select size="small" v-model="form.aims" placeholder="选择班级">
            <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.target == 'grade'" prop="" label="选择年级">
          <el-select size="small" v-model="form.aims" placeholder="选择年级">
            <el-option v-for="(item,i) in gradeData" :key="'g-'+i" :label="item.grade_name" :value="item.grade_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.target == 'department'" prop="" label="选择部门">
          <el-select size="small" v-model="form.aims" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="" label="内容">
          <editor-bar v-model="form.content" :isClear="isClear" @change="change"></editor-bar>
        </el-form-item>
        <el-form-item prop="" label="附件">
          <el-upload
            class="upload-demo"
            :limit="1" 
            ref="upload"
            accept="application/x-zip-compressed,application/octet-stream,application/x-rar,application/x-7z-compressed,application/zip"
            :multiple="false" 
            :on-change="getFile" 
            :on-preview="handlePreview"
            :on-remove="handleRemove"
            :auto-upload="false">
            <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
            <div slot="tip" class="el-upload__tip">只能一个上传压缩包文件，且不超过10MB</div>
          </el-upload>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onSubmit('form')">立即创建</el-button>
          <el-button size="small" @click="onExit">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
// import "network/ajaxfileupload";
import * as sysTool from "assets/js/systemTool";
import {requestAjax,fileUpload} from "network/request_ajax";
import EditorBar from "components/common/wangeditor"
export default {
  data(){
    return{
      form:{
        semester: this.$store.state.semester,
        title: "",
        type: "",
        target: "",
        aims: "",
        content: "",
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },

      loading: false,

      rules:{
        title: [
          { required: true, message: '请输入作业标题', trigger: 'blur' },
        ],
        class: [
          {required: true, message: '请选择班级', trigger: 'change' }
        ],
        grade: [
          {required: true, message: '请选择年级', trigger: 'change' }
        ],
        department: [
          {required: true, message: '请选择部门', trigger: 'change' }
        ],
        campus: [
          {required: true, message: '请选择校区', trigger: 'change' }
        ],
        school: [
          {required: true, message: '请选择学校', trigger: 'change' }
        ],
        type: [
          {required: true, message: '请选择通知对象', trigger: 'change' }
        ],
        target: [
          {required: true, message: '请选择通知范围', trigger: 'change' }
        ],
        content: [
          {required: true, message: '请输入作业内容', trigger: 'blur' }
        ],
      },

      fileList: [],
      
      schoolData: [],
      campusData: [],
      departmentData: [],
      gradeData: [],
      classData: [],

      file: [],
    }
  },
  components:{
    EditorBar,
  },
  created(){
    this.getSchool();
    this.getCampus();
    this.getDepartment();
    this.getGrade();
    this.getClass();

  },
  methods:{
    change(val){
      this.form.content = val;
    },
    onExit(){
      location.href = '/management/backstage';
    },
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if(this.form.content == ''){
            this.$message({
              message: "请输入作业内容",
              type: 'warning',
            })  
            return false;
          }
          if(this.form.target == 'campus'){
            this.form.aims = this.$store.state.userCampus;
          }
          if(this.form.aims == ''){
            this.$message({
              message: "请选择通知对象范围",
              type: 'warning',
            })  
            return false;
          }
          this.loading = true;
          this.form.addTime = new Date().getTime();
          let formdata = new FormData();
          formdata.append("entity",JSON.stringify(this.form));
          formdata.append("annex",this.file);
          formdata.append("action","setMessage");
          requestAjax({
            type: 'post',
            url: "/Message/Message.php",
            data: formdata,
            dataType: 'json', // 传递数据的格式
            async: false, // 这是重要的一步，防止重复提交的
            cache: false,  // 设置为false，上传文件不需要缓存。
            contentType: false, // 设置为false,因为是构造的FormData对象,所以这里设置为false。
            processData: false, // 设置为false,因为data值是FormData对象，不需要对数据做处理。
            success:res=>{
              this.loading = false;
              // console.log(res);
              let data = res.data;
              if(data.type == 'success'){
                this.$message({
                  type: 'success',
                  message: "添加成功",
                });
                location.reload();
              }else{
                this.$message.error(data.info);
              }
            },
            error:err=>{
              this.loading = false;
              console.error(err);
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
    getFile(f,fl){
      this.loading = true;
      var file = f.raw;
      //   // 上传类型判断
      let imgName = file.name;
      let idx = imgName.lastIndexOf(".");  
      if (idx != -1){
        var ext = imgName.substr(idx+1).toUpperCase();   
        ext = ext.toLowerCase(); 
        if (ext!='rar' && ext!='zip' && ext!='7z'){
          this.loading = false;
          this.$message({
            message: "请上传有意义的文件！",
            type: "warning",
          });
          this.$refs.upload.clearFiles();
          return false;
        }else{
          this.loading = false;
          this.file = file;
        }   
      }else{
        this.loading = false;
        this.$message({
          message: "请上传有意义的文件！",
          type: "warning",
        });
        this.$refs.upload.clearFiles();
        return false;
      }
    },
    getSchool(){
      this.loading = true;
      requestAjax({
        url: "/base/school.php",
        type: 'get',
        data:{
          type: "sel_school",
        },
        async: true,
        success:(res)=>{
          this.loading = true;
          res = JSON.parse(res);
          this.schoolData = res;
        },
        error:(err)=>{
          this.loading = true;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getCampus(){
      this.loading = true;
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus", 
          sel: "campus_school",
          val: this.$store.state.userSchool,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.campusData = res;
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
    getDepartment(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
    getGrade(){ 
      this.loading = true;
      requestAjax({
        url: "/base/grade.php",
        type: 'get',
        data:{
          type: "sel_grade",
          selobj:{
            campus: this.$store.state.userCampus,
          }
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.gradeData = res;
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
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : null,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : '0',
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          // console.log(this.$store.state.userDepartment);
          this.classData = res;
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
    clearAims(){
      this.form.aims = "";
    },
    clearTarget(){
      this.form.aims = "";
      this.form.target = '';
    }
  }
}
</script>

<style>
  .layout{
    padding: 12px;
  }
</style>