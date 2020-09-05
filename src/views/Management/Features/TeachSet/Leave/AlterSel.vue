<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>调课查询</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select size="small" v-model="sel.where" placeholder="查询范围">
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
          <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="sel.class" placeholder="选择班级">
          <el-option v-if="classData.length > 0" label="所有班级" value="all"></el-option>
          <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
        <el-button size="small" type="danger" @click="delMore">删除</el-button>
      </el-form-item>
    </el-form>
    <div class="altersel">
      <el-table
        :data="tableData"
        border
        size="small"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        stripe
        style="width: 100%">
        <el-table-column
          type="selection"
          fixed 
          width="55">
        </el-table-column>
        <el-table-column
          prop="course"
          min-width="100"
          sortable
          label="课程">
        </el-table-column>
        <el-table-column
          prop="teacher"
          label="教师"
          sortable
          min-width="90">
        </el-table-column>
        <el-table-column
          prop="department"
          sortable
          min-width="100"
          label="部门">
        </el-table-column>
        <el-table-column
          prop="class"
          sortable
          min-width="100"
          label="班级">
        </el-table-column>
        <el-table-column
          prop="build"
          min-width="100"
          sortable
          label="教学楼">
        </el-table-column>
        <el-table-column
          prop="date"
          sortable
          min-width="100"
          label="日期">
          <template v-slot="scope">
            {{getDate(scope.row.date)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="session"
          sortable
          min-width="80"
          label="节次">
        </el-table-column>
        <el-table-column
          prop="type"
          sortable
          min-width="90"
          label="原因">
        </el-table-column>
        <el-table-column
          prop="remark"
          min-width="120"
          label="备注">
          <template v-slot="scope">
            {{ scope.row.remark == "" ? '无' : scope.row.remark }}
          </template>
        </el-table-column>
        <el-table-column
          prop=""
          fixed="right"
          label="操作">
          <template v-slot="scope">
            <el-link type="danger" @click="onDel(scope.row.id)">删除</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        where: "day",
        semester: "",
        time: new Date().setHours(0,0,0,0),
        month: "",
        department: "all",
        grade: "",
        class: "",
      },

      tableData: [],

      multipleTable: [],
      n: this.$route.query.n,
      tableData:[],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,

      semesterData: [],
      departmentData: [],
      classData: [],
    }
  },
  methods:{
    onSubmit(){
      this.choose = true;
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
      this.sel.class = '';
      this.loading = true;
      let obj = {};
      if(this.sel.department == 'all'){
        obj = {
          'semester': this.sel.where == 'semester' ? this.sel.semester : this.$store.state.semester,
          'campus': this.$store.state.userCampus,
          'status': '1',
        }
      }else{
        obj = {
          'semester': this.sel.where == 'semester' ? this.sel.semester : this.$store.state.semester,
          'department': this.sel.department,
          'status': "1",
        };
      }
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department,
          selobj: obj,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
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
      this.getData();
      this.getDataSum();
    },
    getData(){
      if(this.sel.department == '' || this.sel.class == ''){
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
            selobj: {
              class: this.sel.class,
              date: this.sel.time,
            }
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'day',
            selobj: {
              department: this.sel.department,
              date: this.sel.time,
            },
          }
        }else{
          obj = {
            seltype: 'day',
            selobj: {
              campus: this.$store.state.userCampus,
              date: this.sel.time,
            },
          }
        }
      }else if(this.sel.where == 'month'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'month',
            class: this.sel.class,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000000,
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'month',
            department: this.sel.department,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000000,
          }
        }else{
          obj = {
            seltype: 'month',
            campus: this.$store.state.userCampus,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000000,
          }
        }
      }else if(this.sel.where == 'semester'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'semester',
            selobj: {
              class: this.sel.class,
              semester: this.sel.semester,
            },
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'semester',
            selobj: {
              department: this.sel.department,
              semester: this.sel.semester,
            },
          }
        }else{
          obj = {
            seltype: 'semester',
            selobj: {
              campus: this.$store.state.userCampus,
              semester: this.sel.semester,
            },
          }
        }
      }
      obj['col'] = "*";
      obj['start'] = (this.page-1)*this.total;
      obj['num'] = this.total;
      obj['type'] = 'sel_limit_alter';

      this.loading = true;
      requestAjax({
        url: "/teach/AlterSel.php",
        type: 'get',
        data: obj,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
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
      });
    },
    getDataSum(){
      if(this.sel.department == '' || this.sel.class == ''){
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
            selobj: {
              class: this.sel.class,
              date: this.sel.time,
            }
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'day',
            selobj: {
              department: this.sel.department,
              date: this.sel.time,
            },
          }
        }else{
          obj = {
            seltype: 'day',
            selobj: {
              campus: this.$store.state.userCampus,
              date: this.sel.time,
            },
          }
        }
      }else if(this.sel.where == 'month'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'month',
            class: this.sel.class,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000,
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'month',
            department: this.sel.department,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000,
          }
        }else{
          obj = {
            seltype: 'month',
            campus: this.$store.state.userCampus,
            datestart: this.sel.month,
            dateend: this.sel.month + 2592000,
          }
        }
      }else if(this.sel.where == 'semester'){
        if(this.sel.class != 'all'){
          obj = {
            seltype: 'semester',
            selobj: {
              class: this.sel.class,
              semester: this.sel.semester,
            },
          }
        }else if(this.sel.department != 'all'){
          obj = {
            seltype: 'semester',
            selobj: {
              department: this.sel.department,
              semester: this.sel.semester,
            },
          }
        }else{
          obj = {
            seltype: 'semester',
            selobj: {
              campus: this.$store.state.userCampus,
              semester: this.sel.semester,
            },
          }
        }
      }
      obj['col'] = "COUNT(*)";
      obj['start'] = (this.page-1)*this.total;
      obj['num'] = this.total;
      obj['type'] = 'sel_alter';

      this.loading = true;
      requestAjax({
        url: "/teach/AlterSel.php",
        type: 'get',
        data: obj,
        success:(res)=>{
          this.loading = false;
          // console.log(res);
          res = JSON.parse(res)[0]['COUNT(*)'];
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
      })
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onDel(id){
      this.$confirm('是否确定删除该记录？', '确认删除？', {
        distinguishCancelAndClose: true,
        confirmButtonText: '确定',
        cancelButtonText: '放弃'
      })
      .then(()=>{
        let arr = [id];
        requestAjax({
          url: "/teach/AlterSel.php",
          type: 'post',
          data:{
            type: "del_alter",
            sel: 'id',
            arr: arr,
          },
          async: false,
          success:(res)=>{
            res = JSON.parse(res);
            // console.log(arr);
            if(res){
              this.$message({
                message: '删除成功',
                type: 'success'
              });

              // 日志写入
              let obj = {
                content: "删除调课信息 "+ this.id,
                type: "删除记录",
                model: "课务调整模块",
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
    },
    delMore(){
      let arr = [];
      $.each(this.multipleTable, function (i, v) { 
        arr.push(v.id);
      });
      if(arr.length != 0){
        this.$confirm('是否确定删除记录？', '确认删除？', {
          distinguishCancelAndClose: true,
          confirmButtonText: '确定',
          cancelButtonText: '放弃'
        })
        .then(()=>{
          let arr = [id];
          requestAjax({
            url: "/teach/AlterSel.php",
            type: 'post',
            data:{
              type: "del_alter",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "删除调课信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "课务调整模块",
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
  },
  created(){ 
    this.getSemester();
    this.getDepartment();
    this.getClass();
    this.sel.class = 'all';
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
  }
}
</script>

<style scoped>
  .altersel{
    margin-bottom: 14px;
  }
</style>