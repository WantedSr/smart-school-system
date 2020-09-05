<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>考勤补充登记</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getDormitory" size="small" v-model="sel.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getStu" size="small" v-model="sel.dormroom" placeholder="查询宿舍">
          <el-option v-for="(item,i) in dormroomData" :key="'dormitory-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker
          size="small"
          @change="getStu"
          value-format="timestamp"
          v-model="sel.date"
          type="date"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
    </el-form>
    
    <div class="altersel">
      <el-table
      :data="stuData"
      stripe
      size="small"
      ref="multipleTable"
      tooltip-effect="dark"
      @selection-change="handleSelectionChange"
      v-loading="loading"
      element-loading-text="拼命加载中"
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
      border
      style="width: 100%">
        <el-table-column 
          type="index" 
          label="序号">
        </el-table-column>
        <el-table-column
          prop="dormroom_name"
          min-width="100"
          label="宿舍">
        </el-table-column>
        <el-table-column
          prop="dormitory_name"
          min-width="100"
          label="宿管">
        </el-table-column>
        <el-table-column
          prop="hour"
          min-width="100"
          label="时间">
        </el-table-column>
        <el-table-column
          prop="attendance"
          min-width="100"
          label="全勤">
          <template v-slot="scope">
            {{ scope.row.attendance > 0 ? '缺勤' : '全勤' }}
          </template>
        </el-table-column>
        <el-table-column
          prop="discipline"
          min-width="100"
          label="违纪">
          <template v-slot="scope">
            {{ scope.row.discipline > 0 ? "有违纪" : "无违纪" }}
          </template>
        </el-table-column>
        <el-table-column
          prop="date"
          min-width="100"
          label="日期">
          <template v-slot="scope">
            {{ getDate(scope.row.reg_date) }}
          </template>
        </el-table-column>
        <el-table-column
          min-width="100"
          label="操作">
          <template v-slot="scope">
            <el-button type="primary" v-if="scope.row.attendance > 0 || scope.row.discipline > 0" @click="toSee(scope.row.hour)" size="mini">查看</el-button>
            <el-tag v-else size="mini">无</el-tag>
          </template>
        </el-table-column>
      </el-table>
    </div>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{

      sel:{
        date: new Date().setHours(0,0,0,0),
        department: "",
        dormroom: "",
      },
      n: this.$route.query.n,
      multipleTable: [],

      tableData:[],

      attendanceData: [],
      disciplineData: [],

      departmentData: [],
      dormroomData: [],
      stuData: [],

      loading: false,

    }
  },
  methods:{
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    getDormitory(){
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_more_dormroom",
          col: "*",
          buildtype: '学生宿舍',
          campus: this.$store.state.userCampus,
          department: this.sel.department,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.dormroomData = res;
          // console.log(res);
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
    getStu(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Dormitory/getOption.php",
        data:{
          type: "sel_day_attendance",
          dormroom: this.sel.dormroom,
          campus: this.$store.state.userCampus,
          semester: this.$store.state.semester,
          department: this.sel.department,
          date: this.sel.date,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.stuData = res;
          // console.log(this.stuData);
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
        async: true,
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
    toSee(hour){
      this.$router.push({
        query:{
          n: this.n,
          sel: this.sel,
          hour: hour,
          type: 'upa',
        },
      })
    }
  },
  created(){
    this.getDepartment();
  },
  computed:{
    getDate(){
      return (time)=>{
        let date = new Date();
        date.setTime(time);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        let d = date.getDate();
        return y + "年" + m + '月' + d + "日";
      }
    },
  },
}
</script>

<style>
  .altersel{
    margin-bottom: 20px;
  }
  .do{
    margin-bottom: 20px;
  }
  .pagination{
    text-align: center;
  }
  .pagination .el-pagination{
    display: inline-block;
  }
</style>