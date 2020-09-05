<template>
  <div class="addPlacement">
    <div class="pagehead">
      <h1>编辑学期学生分班</h1>
    </div>
    <el-form ref="form" :rules="rules" :model="form" label-width="100px">
      <el-form-item prop="student" label="学生学号">
        <el-input size="small" v-model="form.student" placeholder="请输入学生学号"></el-input>
      </el-form-item>
      <el-form-item prop="semester" label="所在学期">
        <el-select @change="getClass" size="small" v-model="form.semester" placeholder="选择学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="department" label="所在部门">
        <el-select @change="getClass " size="small" v-model="form.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="class" label="所在班级">
        <el-select size="small" v-model="form.class" placeholder="选择班级">
          <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item size="small" prop="state" label="在校情况">
        <el-select v-model="form.state" placeholder="在校情况">
          <el-option label="在校" value="在校"></el-option>
          <el-option label="校外" value="校外"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="primary" @click="onUpa('form')">修改</el-button>
        <el-button size="small" @click="onBack">取消</el-button>
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
      form:{},
      
      rules:{
        semester: [
          { required: true, message: '请输选择学期', trigger: 'change' },
        ],
        state: [
          { required: true, message: '请选择班级状态', trigger: 'change' }
        ],
        class: [
          { required: true, message: '请选择班级', trigger: 'change' }
        ],
        student: [
          { required: true, message: '请输入学生的学号', trigger: 'blur' }
        ],
        department: [
          { required: true, message: '请选择部门', trigger: 'change' }
        ],
      },

    }
  },
  created(){
    this.getData();
  },
  props:{
    semesterData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    },
    classData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/StuSet/Placement.php",
            type: 'post',
            data:{
              type: "upa_placement",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              if(res[0]){
                this.$message({
                  message: '该生该学期分班记录已存在',
                  type: 'warning'
                });
                return false;
              }else{
                if(res[1]){
                  this.$message({
                    message: '修改学期分班安排成功',
                    type: 'success'
                  });
  
                  // 日志写入
                  let obj = {
                    content: "修改学期分班安排 学期：" + this.form.semester + " 学生：" + this.form.student + " 班级" + this.form.class,
                    type: "修改记录",
                    model: "学期分班模块",
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
    getClass(){
      this.form.class = '';
      this.$emit("getClass",this.form.semester,this.form.department);
    },
    onBack(){
      this.$router.go(-1);
    },
    getData(){
      requestAjax({
        type: "get",
        url: "/StuSet/Placement.php",
        data: {
          col: "*",
          selobj: {
            campus: this.$store.state.userCampus,
            id: this.$route.query.placeId,
          },
          type: "sel_placement",
        },
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
      })
    },
  }
}
</script>

<style>

</style>