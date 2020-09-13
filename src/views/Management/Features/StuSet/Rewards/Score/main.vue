<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>积分查询</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getStu" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option label="所有班级" v-if="classData.length > 0" value="all"></el-option>
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="clearTable" size="small" v-model="sel.student" placeholder="查询学生">          
          <el-option label="所有学生" v-if="stuData.length > 0" value="all"></el-option>
          <el-option v-for="(item,i) in stuData" :key="'student-'+i" :label="item.username" :value="item.userid"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="table">
      <el-table
        :data="tableData"
        size="small"
        v-loading="loading"
        border
        stripe
        ref="multipleTable"
        tooltip-effect="dark"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50"  
        ></el-table-column>
        <el-table-column 
          prop="class_name"
          label="班级"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ scope.row.class.class_name }}
          </template>
        </el-table-column>
        <el-table-column
          prop="student"
          label="姓名"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ scope.row.stu.username }}
          </template>
        </el-table-column>
        <el-table-column
          label="分值"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ totalScore(scope.row.total_score) }}
          </template>
        </el-table-column>
        <el-table-column
          v-for="(item,i) in optionData"
          :key="'optionColumn-'+i"
          :label="item.reward_name"
          sortable 
          :prop="item.reward_id"
          min-width="100"
          >
          <template v-slot="scope">
            <p
              :style="scope.row.total_score.filter(r=>r.reward == item.reward_id).length > 0 ? 
                  scope.row.total_score.filter(r=>r.reward == item.reward_id)[0]['SUM(score)'] > 0 ? 'color: #2ecc71' 
                  : scope.row.total_score.filter(r=>r.reward == item.reward_id)[0]['SUM(score)'] < 0 ? 'color : #e74c3c' 
                  : '' : ''">
              {{
                scope.row.total_score.filter(r=>r.reward == item.reward_id).length > 0 ? 
                  scope.row.total_score.filter(r=>r.reward == item.reward_id)[0]['SUM(score)'] : 
                  0
              }}
            </p>
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
import Limit from "../../../Limit/main";
export default {
  data(){
    return{
      sel:{
        where: "day",
        time: new Date().setHours(0,0,0,0),
        month: "",
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        class: "",
        student: "",
      },

      tableData:[],
      showTable: [],

      n: this.$route.query.n,

      departmentData: [],
      classData: [],
      stuData: [],
      semesterData: [],
      optionData: [],

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  created(){

    this.getOption();
    this.getSemester();
    this.getDepartment();
    this.getClass();
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
    onSubmit(){
      if(this.sel.class == "" || this.sel.student == ""){
        this.$message.error("选项不能为空！");
        return false;
      }
      if(this.sel.where == 'month' && this.sel.month == ""){
        this.$message.error("选项不能为空！");
        return false;
      } 
      let obj = {
        semester: this.where == 'semester' ? this.sel.semester : this.$store.state.semester,
        department: this.sel.department,
      }

      this.tableData = [];
      this.showTable = [];
      this.loading = true;
      requestAjax({
        url: "/StuSet/ProfessionFraction.php",
        type: "post",
        data:{
          action: "group",
          semester: this.$store.state.semester,
          department: this.sel.department,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          if(res.data.length > 0){
            let data = res.data;
            if(this.sel.student != 'all'){
              data = data.filter(item=>item.student == this.sel.student);
            }else if(this.sel.class != 'all'){
              data = data.filter(item=>item.class.class_id == this.sel.class);
            }else{
              data = data;
            }

            // console.log(data);
            // console.log(this.tableData);
            this.tableData = data;
            this.setPage(1);
            // console.log(this.showTable);
          }
        },
        async: false,
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
    getDepartment(campus){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: campus ? campus : this.$store.state.userCampus,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.departmentData = res;
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
    getClass(){
      this.sel.class = '';
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department,
          selobj: {
            'semester': this.sel.where == 'semester' ? this.sel.semester : this.$store.state.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: true,
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
    getStu(){
      this.stuData = [];
      this.sel.student = '';
      this.loading = true;
      let obj = {
        campus: this.$store.state.userCampus,
        job: "S1",
        department: this.sel.department,
      }
      if(this.sel.class != 'all'){
        obj['class'] = this.sel.class;
      }
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          col: "userid,username,class,school,campus",
          selobj: obj,
        },
        async: false,
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
    },
    getOption(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/rewardType.php",
        data:{
          type: "sel_reward",
          col: "reward_id,reward_name",
          selobj: {
            'campus':this.$store.state.userCampus,
          }
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.optionData = res;
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
    },
    sum(){
      return this.tableData.length
    },
    totalScore(){
      return (arr)=>{
        let sum = 0;
        $.each(arr,(i, v)=>{ 
          sum += parseInt(v['SUM(score)']);
        });
        return sum;
      }
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>
  .table{
    margin-bottom: 12px;
  }
</style>