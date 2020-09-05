<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>课间操考勤统计</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getCourseRegister" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item> 
      <el-form-item label="">
        <el-date-picker
          @change="getCourseRegister"
          size="small"
          v-model="sel.date"
          type="date"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
    </el-form>

    <div class="classTable" v-if="choose">
      <el-table 
        :data="courseSituationData"
        border
        size="small"
        style="100%">
        <el-table-column label="班级" sortable prop="class_name" width="100"></el-table-column>
        <el-table-column label="学生" sortable prop="student_name" width="90"></el-table-column>
        <el-table-column label="出勤" sortable prop="attendance" min-width="120"></el-table-column>
        <el-table-column label="违纪" sortable prop="discipline" min-width="120">
          <template v-slot="scope">
            {{ scope.row.discipline.join('-') }}
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
    return {
      sel:{
        department: "",
        class: "",
        date: new Date().setHours(0,0,0,0),
      },
      tableData:[],

      departmentData: [],
      classData: [],

      courseSituationData: [],
    }
  },
  created(){
    this.getDepartment();
    this.getClass();
  },
  methods:{
    getCourseRegister(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuSet/OverView.php",
        data:{
          type: "get_between_situation",
          col: "id,attendance,class,date,discipline,student",
          selobj:{
            semester: this.$store.state.semester,
            campus: this.$store.state.userCampus,
            department: this.sel.department,
            class: this.sel.class,
            date: this.sel.date,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.courseSituationData = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        },
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
            'semester': this.$store.state.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
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
  },
  computed:{
    choose(){
      for(let i in this.sel){
        if(this.sel[i] == '' || this.sel[i] == undefined || this.sel[i] == null){
          return false;
        }
      }
      return true;
    }
  }
}
</script>

<style>

</style>