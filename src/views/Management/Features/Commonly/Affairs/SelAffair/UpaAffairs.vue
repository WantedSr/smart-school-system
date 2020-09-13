<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>修改事务信息</h1>
    </div>
    
    <div class="layout">
      <el-form ref="form" :rules="rules" :model="form" label-width="80px">
        <el-form-item prop="title" label="事务标题">
          <el-input size="small" maxlength="20" placeholder="输入事务的标题，限制20个字" v-model="form.title"></el-input>
        </el-form-item>
        <el-form-item prop="addDate" label="事务日期">
          <el-date-picker size="small" value-format="timestamp" type="date" placeholder="选择日期" v-model="form.addDate" style="width: 100%;"></el-date-picker>
        </el-form-item>
        <el-form-item prop="start_time" label="开始时间">
          <el-time-picker size="small" value-format="timestamp" format="HH:mm" placeholder="选择时间" v-model="form.start_time" style="width: 100%;"></el-time-picker>
        </el-form-item>
        <el-form-item prop="end_time" label="结束时间">
          <el-time-picker size="small" value-format="timestamp" format="HH:mm" placeholder="选择时间" v-model="form.end_time" style="width: 100%;"></el-time-picker>
        </el-form-item>
        <el-form-item prop="type" label="类型">
          <el-radio-group @change="selType" size="small" v-model="form.type">
            <el-radio label="0">教师</el-radio>
            <el-radio label="1">学生</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item v-if="form.type == 1 && form.type != ''" label="范围">
          <el-select @change="selGroup" size="small" v-model="form.scope" placeholder="选择范围">
            <el-option label="部门" value="0"></el-option>
            <el-option label="班级" value="1"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.type == 1 && form.scope != ''" label="班级">
          <el-select @change="selStu" size="small" v-model="cls" placeholder="选择班级">
            <el-option v-if="classData.length > 0 && form.scope != 1 && form.scope != ''" label="所有班级" value="all"></el-option>
            <el-option v-for="(item,i) in classData" :key="'4-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.type == 1 && form.scope != '' && form.scope == 1" label="学生">
          <el-select size="small" v-model="stu" placeholder="选择学生">
            <el-option v-if="stuData.length > 1" label="所有学生" value="all"></el-option>
            <el-option v-for="(item,i) in stuData" :key="'5-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="form.type == 0 && form.type != ''" label="教师">
          <el-select size="small" v-model="tea" placeholder="选择教师">
            <el-option v-for="(item,i) in teacherData" :key="'tea-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="content" label="内容">
          <el-input type="textarea" v-model="form.content" :rows="4" maxlength="500" placeholder="输入事务详细信息，限500个字"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onUpa('form')">修改</el-button>
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
        title: "",
        addDate: new Date().setHours(0,0,0,0),
        start_time: "",
        end_time: "",
        content: "",
        type: "",
        scope: "",
        object: "",
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },

      cls: "",
      stu: "",
      tea: "",

      rules:{
        title: [
          { required: true, message: '请输入作业标题', trigger: 'blur' },
        ],
        addDate: [
          {required: true, message: '请选择事务日期', trigger: 'change' }
        ],
        start_time: [
          {required: true, message: '请选择事务开始时间', trigger: 'change' }
        ],
        end_time: [
          {required: true, message: '请选择事务结束时间', trigger: 'change' }
        ],
        type: [
          {required: true, message: '请选择事务类型', trigger: 'change' }
        ],
        content: [
          {required: true, message: '请输入事务详细内容', trigger: 'blur' }
        ],
      },

      classData: [],
      stuData: [],
      teacherData:[],

      classInfo: {},

      loading: false,
    }
  },
  created(){
    this.getData();
    this.getTeacher();
    this.getClass();
  },
  methods:{
    getData(){
      this.loading = true;
      requestAjax({
        url: "/Affairs/Affairs.php",
        type: "post",
        data:{
          action: "get",
          request: JSON.stringify({
            campus: this.$store.state.userCampus,
            id: this.$route.query.recordId,
          }),
          cu: 0,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          if(res.data.length > 0){
            this.form = res.data[0];
            if(this.form.type == 0){
              this.tea = this.form.object;
            }
            else if(this.form.type == 1){
              if(this.form.scope == 0){
                this.cls = this.form.object;
              }
              else if(this.form.type == 1){
                this.stu = this.form.object;
              }
            }
          }
        },
        async: true,
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
    selType(){
      this.form.scope = '';
    },
    selGroup(){
      this.cls = '';
      this.stu = '';
    },
    onBack(){
      this.$router.go(-1);
    },
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.$store.state.userDepartment,
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
          // console.log(res);
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
    selStu(){
      this.stu = '';
      if(this.form.scope == 1){
        this.loading = true;
        requestAjax({
          type: "get",
          url: "/StuManagement/StuInfo/StuLibrary.php",
          data:{
            type: "sel_stu_name",
            col: "userid,username",
            class: this.cls,
            campus: this.$store.state.userCampus,
          },
          success:(res)=>{
            this.loading = false;
            res = JSON.parse(res);
            this.stuData = res;
            // console.log(res);
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
      }
    },
    getTeacher(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "userid,username,department",
          selobj: {
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
            type: '1',
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.teacherData = res;
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
          if(this.form.type == 0 && this.tea == ''){
            this.$message({
              message: "请选择教师",
              type: 'warning',
            })  
            return false;
          }
          if(this.form.type == 1 && this.form.scope == ""){
            this.$message({
              message: "请选择范围",
              type: 'warning',
            })  
            return false;
          }
          if(this.form.type == 1 && this.form.scope == 0 && this.cls == ""){
            this.$message({
              message: "请选择班级",
              type: 'warning',
            })  
            return false;
          }
          if(this.form.type == 1 && this.form.scope == 1 && this.stu == ''){
            this.$message({
              message: "请选择学生",
              type: 'warning',
            })  
            return false;
          }
          if(this.form.type == 0){
            this.form.scope = '2';
            this.form.object = this.tea;
          }
          else if(this.form.type == 1){
            if(this.form.scope == 0){
              this.form.object = this.cls;
            }
            else if(this.form.scope == 1){
              this.form.object = this.stu;
            }
          }
          this.form.addTime = new Date().getTime();
          let arr = Object.values(this.form);
          // console.log(arr);
          requestAjax({
            type: "post",
            url: "/Affairs/Affairs.php",
            data: {
              action: "up",
              id: this.form.id,
              request: JSON.stringify(this.form),
            },
            success:(res)=>{
              res = JSON.parse(res);
              console.log(res);
              if(res.data){
                this.$message({
                  message: '事务修改成功!',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改事务信息："+ this.form.title + " 日期：" + this.form.addDate,
                  type: "修改记录",
                  model: "事务模块",
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
  .layout{
    padding: 12px;
  }
</style>