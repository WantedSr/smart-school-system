<template>
  <div class="subpage" v-loading='loading'>
    <div class="semesterArrangement_add">
      <div class="pagehead">
        <h1>修改学期宿舍安排</h1>
      </div>
      
      <div class="sel">
        <el-form label-position="left" label-width="100px" :rules="rules" ref="sel" :model="sel" class="demo-form-inline">
          <el-form-item prop="semester" label="选择学期">
            <el-select @change="getClass" size="mini" v-model="sel.semester" placeholder="选择学期">
              <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="department" label="选择部门">
            <el-select @change="selDormbuild" size="mini" v-model="sel.department" placeholder="选择部门">
              <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="class" label="选择班级">
            <el-select @change="getStu" size="mini" v-model="sel.class" placeholder="选择班级">
              <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="student" label="选择学生">
            <el-select size="mini" v-model="sel.student" placeholder="选择学生">
              <el-option v-for="(item,i) in studentData" :key="'stu-'+i" :label="item.username" :value="item.userid"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="build" label="选择宿舍楼">
            <el-select @change="getDormroom" size="mini" v-model="sel.build" placeholder="选择宿舍楼">
              <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="dormroom" label="选择宿舍">
            <el-select size="mini" v-model="sel.dormroom" placeholder="查询宿舍">
              <el-option v-for="(item,i) in dormroomData" :key="'dr-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="">
            <el-button type="success" @click="onUpa('sel')">修改</el-button>
            <el-button @click="onBack">取消</el-button>
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
      sel:{},

      studentData: [],
      
      rules:{
        semester: [
          { required: true, message: '请输选择学期', trigger: 'change' },
        ],
        build: [
          { required: true, message: '请选择宿舍楼', trigger: 'change' }
        ],
        dormroom: [
          { required: true, message: '请选择宿舍房间', trigger: 'change' }
        ],
        class: [
          { required: true, message: '请选择班级', trigger: 'change' }
        ],
        student: [
          { required: true, message: '请选择学生', trigger: 'change' }
        ],
        department: [
          { required: true, message: '请选择部门', trigger: 'change' }
        ],
      },

      loading: false,
    }
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
    buildData:{
      type: Array,
      require: true,
    },
    classData:{
      type: Array,
      require: true,
    },
    dormroomData:{
      type: Array,
      require: true,
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      url: "/dormitory/stuArrangement.php",
      type: 'get',
      data:{
        type: "sel_dormroom",
        selobj:{
          id: this.$route.query.dormId,
        }
      },
      async: false,
      success:(res)=>{
        this.loading = false;
        res = JSON.parse(res)[0];
        this.sel = res;
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
    });
    let c = this.sel.class;
    this.getClass();
    this.sel.class = c;
    let stu = this.sel.student;
    this.getStu(this.sel.class);
    this.sel.student = stu;
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getClass(){
      this.sel.class = '';
      this.$emit("getClass",this.sel.semester,this.sel.department);
    },
    getStu(c){
      this.loading = true;
      requestAjax({
        type: 'get',
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          selobj: {
            campus: this.$store.state.userCampus,
            class: c,
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
    getDormroom(build){
      this.sel.dormroom = "",
      this.$emit('getDormroom',this.sel.build);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/dormitory/stuArrangement.php",
            type: 'get',
            data:{
              type: "upa_dormroom",
              obj: this.sel,
              sel: 'id',
              val: this.sel.id,
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '修改学期宿舍安排成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改学期宿舍安排 学期：" + this.sel.semester + " 学生：" + this.sel.student + " 宿舍" + this.sel.dormroom,
                  type: "修改记录",
                  model: "学期宿舍安排模块",
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
    selDormbuild(dep){
      this.sel.build = "";
      this.sel.class = "";
      this.sel.room = "";
      this.$emit("getDormBuild",this.sel.department);
      this.$emit("getClass",this.sel.semester,this.sel.department);
    },
  }
}
</script>

<style>

</style>