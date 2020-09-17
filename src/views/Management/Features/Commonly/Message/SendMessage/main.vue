<template>
  <div class="subpage">

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
            <el-option label="学校" value="school"></el-option>
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
        <el-form-item v-if="form.target == 'campus'" prop="" label="选择校区">
          <el-select size="small" v-model="form.aims" placeholder="选择校区">
            <el-option v-for="(item,i) in campusData" :key="'campus-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.target == 'school'" prop="" label="选择学校">
          <el-select size="small" v-model="form.aims" placeholder="选择学校">
            <el-option v-for="(item,i) in schoolData" :key="'s-'+i" :label="item.school_name" :value="item.school_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="" label="内容">
          <editor-bar v-model="form.content" :isClear="isClear" @change="change"></editor-bar>
        </el-form-item>
        <el-form-item prop="" label="附件">
          <input type="file" ref="clearFile" @change="getFile($event)" multiple="multiplt" class="add-file-right-input" style="margin-left:70px;" accept=".rar,.zip,.7z">
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

      addArr: [],
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
    getClassInfo(){
      this.loading = true;
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
          selobj: {
            "class_id":this.form.class,
          },
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          // console.log(res);
          this.classInfo = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
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
          if(this.form.aims == ''){
            this.$message({
              message: "请选择通知对象范围",
              type: 'warning',
            })  
            return false;
          }
          this.form.addTime = new Date().getTime();

          requestAjax({
            type: 'post',
            url: "/Message/MessageControl.php",
            data:{
              action: "setMessage",
              entiry: JSON.stringify(this.form),
            }
          })

        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    getFile(item){
      console.log(event);
      var file = event.target.files;
      for(var i = 0;i<file.length;i++){
        // 上传类型判断
        var imgName = file[i].name;
        var idx = imgName.lastIndexOf(".");  
        if (idx != -1){
          var ext = imgName.substr(idx+1).toUpperCase();   
          ext = ext.toLowerCase( ); 
          if (ext!='rar' && ext!='zip' && ext!='7z'){
            this.$message({
              message: "请上传有意义的文件！",
              type: "warning",
            });
            return false;
          }else{
            this.addArr.push(file[i]);
          }   
        }else{
          this.$message({
            message: "请上传有意义的文件！",
            type: "warning",
          });
          return false;
        }
      }
      console.log(this.addArr);
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