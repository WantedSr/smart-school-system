<template>
  <div class="addClass">
    <div class="pagehead">
      <h1>编辑班级信息</h1>
    </div>
    
    <div class="sel_upa">
      
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="class_name" label="班级名称">
          <el-input style="width: 250px" v-model="sel_upa.class_name" placeholder="班级名称"></el-input>
        </el-form-item>
        <el-form-item prop="class_grade" label="入学年份">
          <el-select @change="getClassId" v-model="sel_upa.class_grade" placeholder="入学年份">
            <el-option v-for="(item,i) in gradeData" :key="'1-'+i" :label="item.grade_name" :value="item.grade_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_campus" label="所在校区">
          <el-select @change="selDep" v-model="sel_upa.class_campus" placeholder="所在校区">
            <el-option v-for="(item,i) in campusData" :key="'2-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_department" label="所在部门">
          <el-select @change="selPro" v-model="sel_upa.class_department" placeholder="所在部门">
            <el-option v-for="(item,i) in departmentData" :key="'3-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_skill" label="所在专业">
          <el-select @change="getClassId" v-model="sel_upa.class_skill" placeholder="所在专业">
            <el-option v-for="(item,i) in professionData" :key="'4-'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="state" label="状态">
          <el-select v-model="sel_upa.state" placeholder="状态">
            <el-option label="在读" value="1"></el-option>
            <el-option label="毕业" value="0"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="class_id" label="班级代码">
          <el-input style="width: 250px" v-model="sel_upa.class_id" placeholder="班级代码"></el-input>
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
  created(){
    this.getData("*","class_id",this.$route.query.classId);
    this.$emit("getPro",this.sel_upa.class_department);
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
    getData(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
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
    selDep(v){
      this.sel_upa.class_department = '';
      this.sel_upa.class_skill = '';
      this.$emit('getDep',v);
    },
    selPro(v){
      this.sel_upa.class_skill = '';
      this.$emit('getPro',v);
    },
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/class.php",
            type: 'get',
            data:{
              type: "upa_class",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改班级信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除班级 "+ JSON.stringify(arr),
                  type: "删除记录",
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
              }else{
                this.$message.error('修改班级信息失败，请稍后再试！');
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
  }
}
</script>

<style>

</style>