<template>
  <div class="subpage">
    
    <div class="pagehead">
      <h1>布置作业查询</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="setValue" size="small" v-model="sel.where" placeholder="查询范围">
          <el-option label="日" value="day"></el-option>
          <el-option label="月" value="month"></el-option>
          <el-option label="学期" value="semester"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="sel.where == 'semester'" label="">
        <el-select @change="getClass" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'day'">
        <el-date-picker
          size="small"
          v-model="sel.time"
          type="date"
          format="yyyy-MM-dd"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'month'">
        <el-date-picker
          size="small"
          v-model="sel.month"
          format="yyyy-MM"
          value-format="timestamp"
          type="month"
          placeholder="选择月份">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option label="所有部门" v-if="departmentData.length > 0" value="all"></el-option>
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select 
        @change="getCourse" 
        size="small" 
        v-model="sel.class" 
        placeholder="查询班级">
          <el-option 
          label="所有班级" 
          v-if="classData.length > 0"
          value="all"></el-option>
          <el-option 
          v-for="(item,i) in classData" 
          :key="'c-'+i" 
          :label="item.class_name" 
          :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
        <el-button type="danger" size="small" @click="onDel">删除</el-button>
      </el-form-item>
    </el-form>

    <div class="sellayout">
      <div>
        <el-table
          :data="tableData"
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
            type="selection"
            width="50">
          </el-table-column>
          <el-table-column
            prop="title"
            label="作业"
            sortable
            min-width="100">
          </el-table-column>
          <el-table-column
            prop="class_name"
            label="班级"
            sortable
            min-width="100">
          </el-table-column>
          <el-table-column
            prop="course_name"
            label="科目"
            sortable
            min-width="100">
          </el-table-column>
          <el-table-column
            prop="teacher_name"
            label="教师"
            sortable>
          </el-table-column>
          <el-table-column
            prop="duration"
            label="作业时长"
            min-width="100"
            sortable>
          </el-table-column>
          <el-table-column
            prop="type"
            label="作业类型"
            min-width="100" 
            sortable>
          </el-table-column>
          <el-table-column
            label="操作">
            <template v-slot="scope">
              <el-button type="primary" size="small" @click="onUpa(scope.row.id)">编辑</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
    </div>
    
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
    
  </div>
</template>

<script>
import Limit from "../../../Limit/main";
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        where: "day",
        time: new Date().setHours(0,0,0,0),
        month: "",
        semester: "",
        department: this.$store.state.userDepartment,
        class: "",
      },

      tableData:[],

      departmentData: [],
      classData: [],
      courseData: [],
      semesterData: [],

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
      this.getData();
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
    getData(){

      if(this.sel.department == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'day' && this.sel.time == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'day' && this.sel.class == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'month' && this.sel.month == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }else if(this.sel.where == 'semester' && this.sel.semester == ''){
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
        return false;
      }
      
      let obj = {};
      if(this.sel.where == 'day'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'day',
            class: this.sel.class,
            date: this.sel.time,
            semester: this.$store.state.semester,
          }
        }
        else if(this.sel.department != 'all'){
          obj = {
            seltype: 'day',
            department: this.sel.department,
            date: this.sel.time,
            semester: this.$store.state.semester,
          }
        }
        else{
          obj = {
            seltype: 'day',
            campus: this.$store.state.userCampus,
            date: this.sel.time,
            semester: this.$store.state.semester,
          }
        }
      }else if(this.sel.where == 'month'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'month',
            class: this.sel.class,
            date_start: this.sel.month,
            date_end: this.sel.month + 2592000000,
            semester: this.$store.state.semester,
          }
        }
        else if(this.sel.department != 'all'){
          obj = {
            seltype: 'month',
            department: this.sel.department,
            date_start: this.sel.month,
            date_end: this.sel.month + 2592000000,
            semester: this.$store.state.semester,
          }
        }
        else{
          obj = {
            seltype: 'month',
            campus: this.$store.state.userCampus,
            date_start: this.sel.month,
            date_end: this.sel.month + 2592000000,
            semester: this.$store.state.semester,
          }
        }
      }else if(this.sel.where == 'semester'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'semester',
            class: this.sel.class,
            semester: this.sel.semester,
          }
        }
        else if(this.sel.department != 'all'){
          obj = {
            seltype: 'semester',
            department: this.sel.department,
            semester: this.sel.semester,
          }
        }
        else{
          obj = {
            seltype: 'semester',
            campus: this.$store.state.userCampus,
            semester: this.sel.semester,
          }
        }
      }
      obj['col'] = "*";
      obj['campus'] = this.$store.state.userCampus;
      obj['start'] = (this.page-1)*this.total;
      obj['num'] = this.total;
      obj['type'] = 'sel_limit_homework';

      // console.log(obj);
      this.loading = true;
      requestAjax({
        url: "/Homework/homework.php",
        type: 'get',
        data: obj,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res[0];
          // console.log(res);
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
      });
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          homeId: id,
          type: 'upa',
        },
      })
    },
    onDel(){
      let arr = [];
      $.each(this.multipleTable, (i, v)=>{ 
        arr.push(v.id);
      });
      if(arr.length != 0){
        this.$confirm('是否确定删除该记录？', '确认删除？', {
          distinguishCancelAndClose: true,
          confirmButtonText: '确定',
          cancelButtonText: '放弃'
        })
        .then(()=>{
          requestAjax({
            url: "/Homework/homework.php",
            type: 'post',
            data:{
              type: "del_homework",
              arr: arr,
              campus: this.$store.state.userCampus,
            },
            async: false,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除作业 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "作业模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.onSubmit();
              }else{
                this.$message.error('删除失败，请稍后再试！');
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
        })
        .catch(action=>{
          this.$message('取消操作');
        })
      }else{
        this.$message.error('请选择选项后在进行操作！');
      }
    },
  }
}
</script>

<style>
  .sellayout{
    margin-bottom: 12px;
  }
</style>