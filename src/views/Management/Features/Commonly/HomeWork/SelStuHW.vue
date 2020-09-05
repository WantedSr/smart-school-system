<template>
  <div class="subpage">
    
    <div class="pagehead">
      <h1>学生查询</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <!-- @change="getStu"  -->
        <el-select 
        size="small" 
        v-model="sel.class" 
        placeholder="查询班级">
          <el-option v-if="classData.length > 0" label="所有班级" value="all"></el-option>
          <el-option 
          v-for="(item,i) in classData" 
          :key="'c-'+i" 
          :label="item.class_name" 
          :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="sellayout">
      <div>
        <el-table
          :data="stuData"
          style="width: 100%"
          stripe
          ref="multipleTable"
          tooltip-effect="dark"
          @selection-change="handleSelectionChange"
          size="small"
          v-loading="loading"
          element-loading-text="拼命加载中"
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(0, 0, 0, 0.8)"
          >
          <el-table-column
            prop="department"
            label="部门"
            sortable
            min-width="70">
          </el-table-column>
          <el-table-column
            prop="class"
            label="班级"
            sortable
            min-width="90">
          </el-table-column>
          <el-table-column
            prop="userid"
            label="学号"
            sortable
            min-width="100">
          </el-table-column>
          <el-table-column
            prop="username"
            label="学生"
            sortable
            min-width="80">
          </el-table-column>
          <el-table-column
            label="按时交"
            prop="student_num"
            min-width="90"
            sortable>
            <template v-slot="scope">
              {{ tableData.filter(item=>item.student == scope.row.userid).length>0 ? tableData.filter(item=>item.student==scope.row.userid)[0]['register_num'] : 0 }}
            </template>
          </el-table-column>
          <el-table-column
            label="缺交"
            prop="register_num"
            min-width="80"
            sortable>
            <template v-slot="scope">
              {{ tableData.filter(item=>item.student == scope.row.userid).length>0 ? tableData.filter(item=>item.student==scope.row.userid)[0]['noregister_num'] : 0 }}
            </template>
          </el-table-column>
          <el-table-column
            label="补交"
            prop="register_num"
            min-width="80"
            sortable>
            <template v-slot="scope">
              {{ tableData.filter(item=>item.student == scope.row.userid).length>0 ? tableData.filter(item=>item.student==scope.row.userid)[0]['report_num'] : 0 }}
            </template>
          </el-table-column>
          <el-table-column
            label="上缴率"
            min-width="100" 
            sortable>
            <template v-slot="scope">
              {{ tableData.filter(item=>item.student == scope.row.userid).length>0 ? (tableData.filter(item=>item.student==scope.row.userid)[0]['register_num'] / tableData.filter(item=>item.student==scope.row.userid)[0]['homework_num'])*100 + '%' : '0%' }}
            </template>
          </el-table-column>
          <el-table-column
            label="缺交率"
            min-width="100" 
            sortable>
            <template v-slot="scope">
              {{ tableData.filter(item=>item.student == scope.row.userid).length>0 ? (tableData.filter(item=>item.student==scope.row.userid)[0]['noregister_num'] / tableData.filter(item=>item.student==scope.row.userid)[0]['homework_num'])*100 + '%' : '0%' }}
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>

    <div class="see">
      <el-dialog title="收货地址" :visible.sync="dialogTableVisible">
        <el-table :data="stuData">
          <el-table-column prop="student_name" label="姓名" min-width="100"></el-table-column>
          <el-table-column prop="state" label="提交状态" min-width="100">
            <template v-slot="scope">
              {{ scope.row.state == '1' ? '已交' : scope.row.state == '3' ? '补交' : '未登记' }}
            </template>
          </el-table-column>
        </el-table>
      </el-dialog>
    </div>
    
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
    
  </div>
</template>

<script>
import Limit from "../../Limit/main";
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        class: "",
      },

      tableData:[],

      departmentData: [],
      classData: [],
      courseData: [],
      semesterData: [],
      homeworkData: [],

      stuData: [],
      dialogTableVisible: false,

      multipleTable: [],
      
      sum: 0,
      page: 1,
      total: 50,
      loading: false,
      n: this.$route.query.n,

    }
  },
  components:{
    Limit,
  },
  created(){
    this.getSemester();
    this.getDepartment();
    this.getSemester();
    this.getClass();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.onSubmit();
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onSubmit(){
      if(this.sel.semester == '' || this.sel.department == '' || this.sel.class == ''){
        this.$message.info("请选择完所有选项")
        return false;
      }
      this.getStu();
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
    getClass(){
      this.classData = [];
      this.sel.class = '';
      let obj = {
        semester: this.sel.semester,
        status: '1',
      }

      if(this.sel.department != 'all'){
        obj['department'] = this.sel.department;
      }else{
        obj['campus'] = this.$store.state.userCampus;
      }
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department != 'all' ? this.sel.department : null,
          selobj: obj
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          // console.log(obj);
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
    getStu(){

      let obj = {
        campus: this.$store.state.userCampus,
      };

      if(this.sel.class != 'all'){
        obj['class'] = this.sel.class;
      }else if(this.sel.department != 'all'){
        obj['department'] = this.sel.department;
      }

      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_limit_stu_name",
          col: "*",
          selobj: obj,
          start: (this.page-1)*this.total,
          num: this.total,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.stuData = res[0];
          this.sum = parseInt(res[1]);
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

      let obj = {
        semester: this.$store.state.semester,
        campus: this.$store.state.userCampus,
      };

      if(this.sel.class != 'all'){
        obj['class'] = this.sel.class;
      }else if(this.sel.department != 'all'){
        obj['department'] = this.sel.department;
      }

      this.loading = true;
      requestAjax({
        url: "/Homework/homework.php",
        type: 'get',
        data: {
          col: "*",
          type: "sel_limit_homework_student",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: obj,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res[0];
          // console.log(res);
          // this.sum = parseInt(res[1]);
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
    onSee(id,cls){
      this.dialogTableVisible = true;
      requestAjax({
        type: 'get',
        url: "/Homework/homework.php",
        data:{
          type: "sel_homework_register",
          selobj:{
            homework: id,
            class: cls,
            campus: this.$store.state.userCampus,
            // state: '1',
            // state: '3',
          },
        },
        success:(res)=>{
          res = JSON.parse(res);
          res = res.filter(item=>item.state != 2);
          this.stuData = res;
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
  }
}
</script>

<style>
  .sellayout{
    margin-bottom: 12px;
  }
</style>