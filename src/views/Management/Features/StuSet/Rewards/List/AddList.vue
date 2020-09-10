<template>
  <div class="subpage" v-loading='loading'>
    <div class="add_record">
      <div class="pagehead">
        <h1>添加奖惩信息</h1>
      </div>
      
      <div class="sel">
        <el-form label-position="left" label-width="100px" :rules="rules" ref="form" :model="form" class="demo-form-inline">
          <el-form-item prop="class" label="选择班级">
            <el-select @change="getStu" size="mini" v-model="form.class" placeholder="选择班级">
              <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="student" label="选择学生">
            <el-select size="mini" v-model="form.student" placeholder="选择学生">
              <el-option v-for="(item,i) in studentData" :key="'stu-'+i" :label="item.username" :value="item.userid"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="type" label="类型">
            <el-select @change="getOption" size="mini" v-model="form.type" placeholder="选择类型">
              <el-option label="奖励" value="0"></el-option>
              <el-option label="惩罚" value="1"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="reward" label="奖惩项目">
            <el-select @change="getScore" size="mini" v-model="form.reward" placeholder="选择奖惩项目">
              <el-option v-for="(item,i) in rewardData" :key="'dr-'+i" :label="item.reward_name" :value="item.reward_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="addTime" label="日期">
            <el-date-picker
              size="small"
              v-model="form.addTime"
              type="date"
              format="yyyy-MM-dd"
              value-format="timestamp"
              placeholder="选择日期">
            </el-date-picker>
          </el-form-item>
          <el-form-item label="">
            <el-button size="small" type="success" @click="onAdd('form')">添加</el-button>
            <el-button size="small" @click="onBack">取消</el-button>
          </el-form-item>
        </el-form>
      </div>

    </div>  
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      form:{
        student: "",
        type: "",
        reward: "",
        score: "",
        semester: this.$store.state.semester,
        class: "",
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: new Date().getTime(),
      },

      studentData: [],
      classData: [],
      rewardData: [],
      
      rules:{
        class: [
          { required: true, message: '请选择班级', trigger: 'change' }
        ],
        student: [
          { required: true, message: '请选择学生', trigger: 'change' }
        ],
        type: [
          { required: true, message: '请选择类型', trigger: 'change' }
        ],
        reward: [
          { required: true, message: '请选择奖惩项目', trigger: 'change' }
        ],
        addTime: [
          { required: true, message: '请选择日期', trigger: 'change' }
        ],
      },

      loading: false,
    }
  },
  created(){
    this.getClass();
    this.getOption();
  },
  methods:{
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
    getStu(c){
      this.loading = true;
      this.form.student = '';
      requestAjax({
        type: 'get',
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          selobj: {
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
            class: this.form.class,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.studentData = res;
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
    getScore(v){
      this.form.score = this.rewardData.filter(item=>item.reward_id == v)[0].reward_score;
    },
    getOption(){
      this.form.reward = '';
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/rewardType.php",
        data:{
          type: "sel_reward",
          col: "reward_id,reward_name,reward_score",
          selobj: {
            'campus':this.$store.state.userCampus,
            'type': this.form.type,
          }
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.rewardData = res;
          console.log(res);
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.form);
          this.loading = true;
          requestAjax({
            url: "/StuSet/ProfessionFraction.php",
            type: "post",
            data:{
              action: "add",
              arr: JSON.stringify(arr),
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              console.log(res);
              if(res.data){
                this.$message({
                  message: '添加奖惩信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加奖惩信息 学期：" + this.form.semester + " 学生：" + this.form.student + " 选项" + this.form.reward,
                  type: "添加记录",
                  model: "奖惩管理模块",
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
              this.loading = false;
            },
            error:(err)=>{
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
  }
}
</script>

<style>

</style>