<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>公开课任务列表</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getTeacher" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
        <el-button size="small" type="danger">删除</el-button>
      </el-form-item>
    </el-form>
    <div class="selEvaluation" style="margin-bottom:12px">
      <el-table
        :data="tableData"
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
          type="selection"
          fixed
          align="center"
          width="40">
        </el-table-column>
        <el-table-column
          prop="title"
          label="课题"
          min-width="160"
          sortable>
        </el-table-column>
        <el-table-column
          prop="teacher"
          label="教师"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="类型"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="lever"
          label="级别"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="date"
          label="时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.date)}}
          </template>
        </el-table-column>
        <el-table-column
          prop=""
          label="填报人"
          min-width="90"
          sortable>
          <template v-slot="scope">
            {{scope.row.informant == '' ? '无' : scope.row.informant}}
          </template>
        </el-table-column>
        <el-table-column
          prop="organizer"
          label="组织者"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态"
          min-width="80"
          sortable>
          <template v-slot="scope">
            {{scope.row.informant == '' || scope.row.informant == 0 || scope.row.informant == undefined ? '未填写' : '已填写'}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          fixed="right"
          align="center"
          min-width="90"
          sortable>
          <template v-slot="scope">
            <el-button size="mini" @click="onWrite(scope.row.id)" type="warning">填写</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
import Limit from "../../Limit/main";
export default {
  data(){
    return{

      sel:{
        semester: this.$store.state.semester,
        department: "",
      },

      tableData:[],

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
  },
  methods:{
    onSubmit(){
      if(this.sel.semester == '' || this.sel.department == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }
      this.getData();
      this.getDataSum();
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
        url: '/teach/Evaluation.php',
        data:{
          type: 'sel_limit_evaluation',
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: {
            semester: this.sel.semester,
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
    getDataSum(){
      this.loading = true;
      requestAjax({
        type: 'get',
        url: '/teach/Evaluation.php',
        data:{
          type: 'sel_evaluation',
          col: "COUNT(*)",
          selobj: {
            semester: this.sel.semester,
            department: this.sel.department,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0]["COUNT(*)"];
          // console.log(res);
          // console.log(this.sel);
          this.sum = parseInt(res);
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
    onSee(id){
      requestAjax({
        type: 'get',
        url: "/teach/Evaluation.php",
        data:{
          type: 'sel_limit_evaluation',
          selobj: {
            id: id,
          },
        },
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.form = res;
          this.dialogFormVisible = true;
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
    onWrite(id){
      this.$router.push({
        query:{
          n: this.n,
          type: 'upa',
          evaId: id,
        }
      })
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>