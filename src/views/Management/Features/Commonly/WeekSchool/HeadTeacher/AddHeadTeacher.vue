<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>第&nbsp;{{ $store.state.week }}&nbsp;周&nbsp;班主任填报</h1>
    </div>
    <div>
      <el-form ref="form" :model="form" :rules="rules" label-width="100px">
        <el-form-item prop="class" label="班级">
          <el-select size="small" v-model="form.class" placeholder="选择班级">
            <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="teacher" label="教师">
          <el-select size="small" v-model="form.teacher" placeholder="选择教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="should_num" label="应到人数">
          <el-input type="number" size="small" v-model="form.should_num" placeholder="应到人数"></el-input>
        </el-form-item>
        <el-form-item prop="real_num" label="实到人数">
          <el-input type="number" size="small" v-model="form.real_num" placeholder="实到人数"></el-input>
        </el-form-item>
        <el-form-item prop="thing_num" label="请假人数">
          <el-input type="number" size="small" v-model="form.thing_num" placeholder="请假人数"></el-input>
        </el-form-item>
        <el-form-item prop="noback_num" label="未返校人数">
          <el-input type="number" size="small" v-model="form.noback_num" placeholder="未返校人数"></el-input>
        </el-form-item>
        <el-form-item prop="practice_num" label="实习人数">
          <el-input type="number" size="small" v-model="form.practice_num" placeholder="实习人数"></el-input>
        </el-form-item>
        <el-form-item prop="leave_num" label="退学人数">
          <el-input type="number" size="small" v-model="form.leave_num" placeholder="退学人数"></el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input type="textarea" size="small" v-model="form.remark" placeholder="备注"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onSubmit('form')">立即创建</el-button>
          <el-button size="small" @click="onBack">取消</el-button>
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
        class: "",
        teacher: "",
        week: this.$store.state.week,
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        should_num: "",
        real_num: "",
        thing_num: "",
        noback_num: "",
        practice_num: "",
        leave_num: "",
        state: '0',
        dep_state: '0',
        office_state: '0',
        leader_state: '0',
        remark: "",
        created_user: this.$store.state.userId,
      },

      rules:{
        class: [
          { required: true, message: '请选择班级', trigger: 'change' },
        ],
        teacher: [
          { required: true, message: '请选择教师', trigger: 'change' }
        ],
        should_num: [
          {required: true, message: '请输入应到人数', trigger: 'blur' }
        ],
        real_num: [
          {required: true, message: '请输入实到人数', trigger: 'blur' }
        ],
        thing_num: [
          {required: true, message: '请输入请假人数', trigger: 'blur' }
        ],
        noback_num: [
          {required: true, message: '请输入未返校人数', trigger: 'blur' }
        ],
        practice_num: [
          {required: true, message: '请输入实习人数', trigger: 'blur' }
        ],
        leave_num: [
          {required: true, message: '请输入退学人数', trigger: 'blur' }
        ],
      },

      
      classData: [],
      teacherData: [],
    }
  },
  created(){
    this.getClass();
    this.getTeacher();
  },
  components:{
  },
  methods:{
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.form);
          arr.push(new Date().getTime());
          requestAjax({
            type: "post",
            url: "/week/HeadTeacher.php",
            data: {
              type: "add_headTeacher",
              arr: arr,
              week: this.$store.state.week,
              semester: this.$store.state.semester,
              class: this.form.class,
              campus: this.$store.state.userCampus,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res[0]){
                this.$message({
                  message: '该班级本周已进行大周填报',
                  type: 'warning'
                });
              }
              else if(res[1]){
                this.$message({
                  message: '本周班主任填报成功！',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "班主任填报 第"+ this.$store.state.week + "周； 教师：" + this.form.teacher + "； 班级：" + this.form.class,
                  type: "添加记录",
                  model: "大周填报-班主任填报模块",
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
    getTeacher(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "*",
          selobj: {
            'department': this.$store.state.userDepartment,
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          this.loading = false;
          // console.log(res);
          res = JSON.parse(res);
          this.teacherData = res;
        },
        error:(err)=>{
          // this.loading = false;
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
            'department': this.$store.state.userDepartment,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
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
    onBack(){
      this.$router.go(-1);
    }
  }
}
</script>

<style>
</style>