<template>
  <div class="addClass">
    <div class="pagehead">
      <h1>添加班级信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="100px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="class_name" label="班级名称">
          <el-input style="width: 250px" v-model="sel_add.class_name" placeholder="班级名称"></el-input>
        </el-form-item>
        <el-form-item prop="class_grade" label="入学年份">
          <el-select @change="getClassId" v-model="sel_add.class_grade" placeholder="入学年份">
            <el-option v-for="(item,i) in gradeData" :key="'1-'+i" :label="item.grade_name" :value="item.grade_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_campus" label="所在校区">
          <el-select @change="selDep" v-model="sel_add.class_campus" placeholder="所在校区">
            <el-option v-for="(item,i) in campusData" :key="'2-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_department" label="所在部门">
          <el-select @change="selPro" v-model="sel_add.class_department" placeholder="所在部门">
            <el-option v-for="(item,i) in departmentData" :key="'3-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_skill" label="所在专业">
          <el-select @change="getClassId" v-model="sel_add.class_skill" placeholder="所在专业">
            <el-option v-for="(item,i) in professionData" :key="'4-'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="state" label="状态">
          <el-select v-model="sel_add.state" placeholder="状态">
            <el-option label="在读" value="1"></el-option>
            <el-option label="毕业" value="0"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_id" label="班级代码">
          <el-input style="width: 250px" v-model="sel_add.class_id" placeholder="班级代码"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onAdd('sel_add')">添加</el-button>
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
      sel_add:{
        class_id: "",
        class_name: "",
        class_grade: "",
        class_skill: "",
        class_department: "",
        class_school: this.$store.state.userSchool,
        class_campus: this.$store.state.userCampus,
        addTime: "",
        state: "1",
        status: "1",
      },
      rules:{
        class_name: [
          { required: true, message: '请输入班级名称', trigger: 'blur' },
        ],
        class_id: [
          { required: true, message: '请选择班级代码', trigger: 'blur' }
        ],
        class_grade: [
          {required: true, message: '请选择班级入学年份', trigger: 'change'}
        ],
        class_skill: [
          {required: true, message: '请选择班级所在专业', trigger: 'change' }
        ],
        class_department: [
          {required: true, message: '请选择班级所在部门', trigger: 'change' }
        ],
        class_campus: [
          {required: true, message: '请选择班级所在校区', trigger: 'change' }
        ],
        state: [
          {required: true, message: '请选择班级状态', trigger: 'change' }
        ],
      },
    }
  },
  props:{
    campusData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    },
    professionData:{
      type: Array,
      require: true,
    },
    gradeData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    selDep(v){
      this.sel_add.class_department = '';
      this.sel_add.class_skill = '';
      this.$emit('getDep',v);
    },
    selPro(v){
      this.sel_add.class_skill = '';
      this.$emit('getPro',v);
    },
    getClassId(v){
      if(this.sel_add.class_grade != '' && this.sel_add.class_skill != ''){
        requestAjax({
          type: "get",
          url: "/base/class.php",
          data:{
            type: "get_class_id",
            profession: this.sel_add.class_skill,
            grade: this.sel_add.class_grade,
            campus: this.$store.state.userCampus,
          },
          success:(res)=>{
            if(res == '' || res == null){
              let $grade = this.sel_add.class_grade.substr(2,2);
              this.sel_add.class_id = this.sel_add.class_skill + "_" + $grade + "01";
            }else{
              let $id = parseInt(res.substr(2,2)) + 1;
              $id = String($id).length == 1 ? '0'+$id : $id;
              let $grade = this.sel_add.class_grade.substr(2,2);
              this.sel_add.class_id = this.sel_add.class_skill + "_" + $grade + $id;
            }
          },
          error:(err)=>{
            console.log(err);
            this.$notify.error({
              title: '错误',
              message: '服务器错误，请稍后再试!'
            });
          }
        })
      }
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.sel_add.addTime = new Date().getTime();
          let arr = Object.values(this.sel_add);
          requestAjax({
            type: "get",
            url: "/base/class.php",
            data: {
              type: "add_class",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '添加班级成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加班级 "+ this.sel_add.class_id,
                  type: "添加记录",
                  model: "班级模块",
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