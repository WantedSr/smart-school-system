<template>
  <div class="upaCourse">
    <div class="pagehead">
      <h1>编辑课程信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="course_name" label="课程名称">
          <el-input style="width: 250px" v-model="sel_upa.course_name" placeholder="课程名称"></el-input>
        </el-form-item>
        <el-form-item prop="course_campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel_upa.course_campus" placeholder="所在校区">
            <el-option v-for="(item,i) in campusData" :key="'c-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="course_department" label="所在部门">
          <el-select @change="selDep" v-model="sel_upa.course_department" placeholder="所在部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="course_profession" label="所在专业">
          <el-select v-model="sel_upa.course_profession" placeholder="所在专业">
            <el-option v-for="(item,i) in professionData" :key="'p-'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="course_type" label="课程类型">
          <el-select v-model="sel_upa.course_type" placeholder="课程类型">
            <el-option v-for="(item,i) in courseType" :key="'t-'+i" :label="item.course_name" :value="item.course_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="course_id" label="课程代码">
          <el-input style="width: 250px" v-model="sel_upa.course_id" placeholder="课程代码"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onUpa('sel_upa')">修改</el-button>
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
      sel_upa:'',
      rules:{
        course_name: [
          { required: true, message: '请输入专业名称', trigger: 'blur' },
        ],
        course_id: [
          { required: true, message: '请选择专业代码', trigger: 'blur' }
        ],
        course_campus: [
          {required: true, message: '请选择专业所在校区', trigger: 'change' }
        ],
        course_department: [
          {required: true, message: '请选择专业所在部门', trigger: 'change' }
        ],
        course_profession: [
          {required: true, message: '请选择专业所在部门', trigger: 'change' }
        ],
        course_type: [
          {required: true, message: '请选择专业所在部门', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.getData("*","course_id",this.$route.query.courseId);
    this.$emit("getPro",this.sel_upa.course_department);
    // this.departmentData = [];
    // this.professionData = [];
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
    courseType:{
      type: Array,
      require: true,
    }
  },
  methods:{
    getData(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/course.php",
        type: 'get',
        data:{
          type: "sel_course",
          col: col,
          sel: sel,
          val: val,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.sel_upa = res;
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
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/course.php",
            type: 'get',
            data:{
              type: "upa_course",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改课程信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改课程信息 "+ this.sel_upa.course_id,
                  type: "修改记录",
                  model: "课程模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.$router.go(-1);
              }else{
                this.$message.error('修改课程信息失败，请稍后再试！');
              }
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
    onBack(){
      this.$router.go(-1);
    },
    getSkillId(v){
      requestAjax({
        url: "/base/profession.php",
        type: 'get',
        data:{
          type: "sel_unmodify_profession",
          col: "COUNT(*)",
          sel: 'skill_department',
          val: v,
        },
        async: true,
        success:(res)=>{
          res = parseInt(JSON.parse(res)[0]['COUNT(*)']) + 1;
          res = String(res).length == 1 ? '0'+res : res;
          let id = v+'_'+res;
          this.sel_add.skill_id = id;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
    },
    selCampus(v){
      this.sel_upa.course_department = '';
      this.$emit('getDep',v);
    },
    selDep(v){
      this.sel_upa.course_profession = '';
      this.$emit('getPro',v);
    },
    getCourseId(v){
      requestAjax({
        type: "get",
        url: "/base/course.php",
        data:{
          type: "get_course_id",
          profession: v,
        },
        success:(res)=>{
          if(res == null || res == ''){
            this.sel_add.course_id = v + "_C001";
          }else{
            let num = parseInt(res.substr(1,3)) + 1;
            if(num < 10){
              num = '00'+num;
            }else if(num < 100){
              num = '0'+num;
            }
            this.sel_add.course_id = v + "_C" + num;
          }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    }
  }
}
</script>

<style>

</style>