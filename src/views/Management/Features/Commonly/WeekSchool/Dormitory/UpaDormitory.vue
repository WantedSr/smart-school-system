<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>编辑第{{ $store.state.week }}周&nbsp;宿舍填报</h1>
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
          <el-button size="small" type="primary" @click="onSubmit('form')">编辑</el-button>
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
      form:{},

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

      loading: false,
    }
  },
  created(){
    this.getData();
    this.getDormBuild();
    this.getDormitory();
    this.getTeacher();
  },
  components:{
  },
  methods:{ 
    getData(){
      requestAjax({
        url: "/week/Dormitory.php",
        type: 'get',
        data:{
          type: "sel_dormitory",
          selobj: {
            department: this.$store.state.userDepartment,
            campus: this.$store.state.userCampus,
            id: this.$route.query.htid,
          },
        },
        async: true,
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
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/week/Dormitory.php",
            data: {
              type: "upa_dormitory",
              obj: {
                dormroom: this.form.dormroom,
                build: this.form.build,
                dormitory: this.form.dormitory,
                should_num: this.form.should_num,
                real_num: this.form.real_num,
                thing_num: this.form.thing_num,
                noback_num: this.form.noback_num,
                practice_num: this.form.practice_num,
                leave_num: this.form.leave_num,
                remark: this.form.remark,
              },
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '修改成功！',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改班主任填报 第"+ this.$store.state.week + "周； 教师：" + this.form.teacher + "； 班级：" + this.form.class,
                  type: "修改记录",
                  model: "大周模块-班主任填报模块",
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
              this.loading = false;
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