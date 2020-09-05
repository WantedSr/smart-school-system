<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>第&nbsp;{{ $store.state.week }}&nbsp;周&nbsp;宿舍填报</h1>
    </div>
    <div>
      <el-form ref="form" :model="form" :rules="rules" label-width="100px">
        <el-form-item prop="build" label="宿舍楼">
          <el-select @change="getDormitory" size="small" v-model="form.build" placeholder="选择宿舍楼">
            <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="dormroom" label="宿舍房间">
          <el-select size="small" v-model="form.dormroom" placeholder="选择班级">
            <el-option v-for="(item,i) in dormroomData" :key="'c-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="dormtory" label="宿管">
          <el-select size="small" v-model="form.dormitory" placeholder="选择教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="should_num" label="宿舍人数">
          <el-input type="number" size="small" v-model="form.should_num" placeholder="宿舍人数"></el-input>
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
        dormroom: "",
        build: "",
        dormitory: "",
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
        build: [
          { required: true, message: '请选择宿舍楼', trigger: 'change' },
        ],
        dormroom: [
          { required: true, message: '请选择宿舍房间', trigger: 'change' },
        ],
        dormitory: [
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

      buildData: [],
      dormroomData: [],
      classData: [],
      teacherData: [],
    }
  },
  created(){
    this.getDormBuild();
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
            url: "/week/Dormitory.php",
            data: {
              type: "add_dormitory",
              arr: arr,
              week: this.$store.state.week,
              semester: this.$store.state.semester,
              dormroom: this.form.dormroom,
              campus: this.$store.state.userCampus,
            },
            success:(res)=>{
              res = JSON.parse(res);
              console.log(arr);
              if(res[0]){
                this.$message({
                  message: '该宿舍本周已进行大周填报',
                  type: 'warning'
                });
              }
              else if(res[1]){
                this.$message({
                  message: '本周宿舍填报成功！',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "宿舍填报 第"+ this.$store.state.week + "周； 教师：" + this.form.teacher + "； 宿舍：" + this.form.dormroom,
                  type: "添加记录",
                  model: "大周填报-宿舍填报模块",
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
    getDormBuild(col="*"){
      this.loading = true;
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          col: col,
          selobj: {
            'campus':this.$store.state.userCampus,
            'department': this.$store.state.userDepartment,
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.buildData = res;
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
    getDormitory(){
      let obj = this.form.build == 'all' ? {'campus':this.$store.state.userCampus} : {
        'campus': this.$store.state.userCampus,
        'build': this.form.build,
      }
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_dormroom",
          col: "*",
          selobj: obj
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.dormroomData = res;
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
            'teacher_job': "T3",
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
    onBack(){
      this.$router.go(-1);
    }
  }
}
</script>

<style>
</style>