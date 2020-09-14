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
          width="55">
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
          label="审核状态"
          min-width="80"
          sortable>
          <template v-slot="scope">
            {{scope.row.status == '' || scope.row.status == 0 || scope.row.status == undefined ? '未审核' : '已审核'}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          fixed="right"
          align="center"
          min-width="150"
          sortable>
          <template v-slot="scope">
            <el-button size="mini" @click="onRight(scope.row.id)" :disabled="scope.row.status == '1'" :type="scope.row.status == '0' ? 'success' : 'info'">审核</el-button>
            <el-button size="mini" @click="onSee(scope.row.id)" type="primary">查看</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
    
    <el-dialog title="收货地址" :visible.sync="dialogFormVisible">
      <el-form size="small" :model="form" label-width="100px">
        <el-form-item label="课题名称">
          <el-input v-model="form.title" readonly autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="授课教师">          
          <el-select disabled size="small" v-model="form.teacher" placeholder="教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="组织者">          
          <el-select disabled size="small" v-model="form.organizer" placeholder="教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="填报人">          
          <el-select disabled size="small" v-model="form.informant" placeholder="教师">
            <el-option v-for="(item,i) in teacherData" :key="'t-'+i" :label="item.username" :value="item.userid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="部门">
          <el-select disabled size="small" v-model="form.department" placeholder="查询部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="学期">          
          <el-select disabled size="small" v-model="form.semester" placeholder="查询学期">
            <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="类型">          
          <el-input v-model="form.type" readonly autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="级别">          
          <el-input v-model="form.lever" readonly autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="时间">          
          <el-date-picker
            v-model="form.date"
            readonly 
            type="date"
            placeholder="选择日期"
            format="yyyy 年 MM 月 dd 日"
            value-format="timestamp">
          </el-date-picker>
        </el-form-item>
        <el-form-item label="节次">          
          <el-input v-model="form.session" readonly autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="班级">          
          <el-input v-model="form.class" readonly autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="课程">          
          <el-input v-model="form.course" readonly autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="课程内容">          
          <el-input type="textarea" :autosize="{ minRows: 4,}" readonly v-model="form2.content" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="课程亮点">          
          <el-input type="textarea" :autosize="{ minRows: 4,}" readonly v-model="form2.highlight" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="听课教师">
          <el-input type="textarea" :autosize="{ minRows: 8,}" readonly v-model="form2.teacher"></el-input>
        </el-form-item>
      </el-form>
    </el-dialog>
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
      form2: {},

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
          type: 'sel_evaluation_more',
          selobj: {
            id: id,
          },
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          console.log(res);
          this.form = res[0];
          this.form2 = res[1];
          if(this.form2 == ""){
            this.form2 = {
              content: "尚未评价",
              highlight: "尚未评价",
              teacher: "尚未评价",
            }
          }
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
    onRight(id){
      this.$confirm('是否要通过该公开课审核?', '询问', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        requestAjax({
          type: "post",
          url: "/teach/evaluation.php",
          data:{
            type: "upa_evaluation",
            obj:{
              'status': '1',
            },
            sel: 'id',
            val: id,
          },
          success:(res)=>{
            res = JSON.parse(res);
            // console.log(res);
            if(res){
              this.$message({
                message: '公开课审核成功',
                type: 'success'
              });

              // 日志写入
              let obj = {
                content: "审核公开课信息 "+ id,
                type: '修改记录',
                model: "公开课模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }

              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);
              
              this.onSubmit();
            }
            else{
              this.$message.error('填写失败，请稍后再试！');
            }
          },
          error:(err)=>{

          }
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '取消操作'
        });          
      });
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
  components:{
    Limit,
  }
}
</script>

<style>

</style>