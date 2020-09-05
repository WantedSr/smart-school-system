<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>班主任设置</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="onSubmit" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="onSubmit" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
    </el-form>

    <div class="selEvaluation box" style="margin-bottom:12px">
      <el-table
        :data="classData"
        size="mini"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        border
        style="width: 100%"
        >
        <el-table-column
          prop="department_name"
          label="系部"
          min-width="100"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class_name"
          label="班级"
          min-width="100"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="teacher_name"
          label="班主任"
          min-width="100"
          align="center"
          sortable>
          <template v-slot="scope">
            {{ tableData.length > 0 && tableData.filter(item=>item.class == scope.row.class).length > 0 ? tableData.filter(item=>item.class == scope.row.class)[0]['teacher_name'] : "未设置" }}
          </template>
        </el-table-column>
        <el-table-column
          prop="class_num"
          label="班级人数"
          min-width="100"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          align="center"
          fixed="right">
          <template v-slot="scope">
            <el-button size="mini" @click="onUpa(scope.row.class)" type="primary">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <el-dialog title="设置班主任" :visible.sync="dialogFormVisible">
      <el-form size="small" :model="form" label-width="100px">
        <el-form-item label="班主任">
          <el-select size="small" v-model="teacher" placeholder="教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onSet">确认</el-button>
          <el-button size="small" type="info" @click="dialogFormVisible = false">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{

      sel:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
      },

      semesterData: [],
      departmentData: [],
      teacherData: [],

      n: this.$route.query.n,
      tableData: [],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 50,
      loading: false,

      dialogFormVisible: false,

      form: {},

      classData: [],

      cls: "",
      teacher: "",

    }
  },
  computed:{
    getDate(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        m = String(m).length == 1 ? '0'+m : m;
        let d = date.getDate();
        d = String(d).length == 1 ? '0'+d : d;
        return y+"-"+m+"-"+d;
      }
    }
  },
  created(){
    this.getSemester();
    this.getDepartment();
    this.onSubmit();
  },
  methods:{
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department,
          selobj: {
            'semester': this.sel.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          // console.log(this.$store.state.userDepartment);
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
    onSubmit(){
      if(this.sel.semester == '' || this.sel.department == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }
      this.getClass();
      this.getData();
    },
    getSemester(){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterData = res;
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
    getDepartment(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
    getData(){
      this.loading = true;
      requestAjax({
        type: 'get',
        url: '/teach/HeadTeacher.php',
        data:{
          type: 'sel_headteacher_name',
          selobj: {
            semester: this.sel.semester,
            campus: this.$store.state.userCampus,
            department: this.sel.department,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          // console.log(this.sel);
          this.tableData = res;
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
    },
    onUpa(cls){
      this.cls = cls;
      this.getTeacher();
      this.dialogFormVisible = true;
    },
    onSet(id){
      requestAjax({
        type: 'post',
        url: "/teach/HeadTeacher.php",
        data:{
          type: 'add_headteacher',
          class: this.cls,
          teacher: this.teacher,
          semester: this.sel.semester,
          department: this.sel.department,
          campus: this.$store.state.userCampus,
          arr:[
            this.cls,
            this.teacher,
            this.sel.semester,
            this.sel.department,
            this.$store.state.userCampus,
            this.$store.state.userSchool,
            this.$store.state.userId,
            new Date().getTime()  
          ],
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          if(res){
            this.$message({
              message: '班主任设置成功',
              type: 'success'
            });
          
            // 日志写入
            let obj = {
              content: "设置班主任信息 班级："+ this.teacher + " 教师：" + this.cls + " 学期：" + this.sel.semester + " 部门：" + this.sel.department,
              type: "添加记录",
              model: "班主任设置模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);

            this.dialogFormVisible = false;
            this.onSubmit();
          }
          else{
            this.$message.error('设置失败，请稍后再试！');
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
    },
    getTeacher(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        async: false,
        data:{
          type: "sel_tea",
          col: "id,userid,username",
          selobj: {
            'department':this.sel.department,
            'type': '1',
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
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
  },
}
</script>

<style>

</style>