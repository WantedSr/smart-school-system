<template>
  <div class="selstuinfo">
    <div class="pagehead">
      <h1>学生信息列表</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'semester-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="选择部门">
          <el-option v-for="(item,i) in depData" :key="'dep-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="sel.class" placeholder="选择班级">
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="">
      <el-table
        border
        size="mini"
        :data="showTable"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%;margin-bottom:12px"
        :default-sort = "{prop: 'date', order: 'descending'}"
        >
        <el-table-column
          prop="userid"
          label="学号"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="username"
          label="姓名"
          sortable>
        </el-table-column>
        <el-table-column
          prop="score"
          label="分数"
          min-width="90"
          sortable>
          <template v-slot="scope">
            {{ 100 + scope.row.sum }}
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import Limit from "../../../Limit/main";
export default {
  data(){
    return{
      n: this.$route.query.n,

      sel:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        class: "",
      },

      tableData:[],
      showTable: [],

      multipleTable: [],

      page: 1,
      total: 50,

      loading: false,

      semesterData: [],
      depData: [],
      classData: [],
    }
  },
  created(){
    this.getSemester();
    this.getDep();
    this.getClass();
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
  components:{
    Limit,
  },
  methods:{
    setPage(page){
      this.page = page;
      let start = ((this.page-1)*this.total);
      let end;
      if(start + this.total >= this.tableData.length){
        end = this.tableData.length;
      }else{
        end = start + this.total;
      }
      this.showTable = [];
      for(let i = start;i < end ; i++){
        this.showTable.push(this.tableData[i]);
      }
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
    getDep(){
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
          this.depData = res;
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
      this.sel.class = "";
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
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
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
    },
    onSubmit(){
      for(let i in this.sel){
        if(this.sel[i] == ""){
          this.$message({
            type: "warning",
            message: "请选择完所有选项"
          });
          return false;
        }
      }
      this.loading = true;
      this.tableData = [];
      this.showTable = [];
      requestAjax({
        type: "post",
        url: "/StuSet/StuScore.php",
        data:{
          action: "getALLStuScore",
          field: JSON.stringify({
            semester: this.sel.semester,
          }),
          filter: JSON.stringify({
            department: this.sel.department,
            class: this.sel.class,
          })
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          if(res.data.length > 0){
            this.tableData = res.data;
            this.setPage(1);
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
    },
    selSkill(v){
      this.sel.profession = '',
      this.$emit('selSkill',v);
    },
    selClass(v){
      this.sel.class = '';
      if(this.sel.skill == "" || this.sel.grade == '') return;
      this.$emit('selClass',this.sel.department,this.sel.skill,this.sel.grade);
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    }
  }
}
</script>

<style>

</style>