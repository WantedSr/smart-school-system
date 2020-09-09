<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>奖惩管理</h1>
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
        <el-select @change="how" size="small" v-model="sel.semester" placeholder="查询学期">
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
        <el-button type="success" size="small" @click="onAdd">添加</el-button>
        <el-button type="warning" size="small" @click="onImp">导入</el-button>
        <el-button type="danger" size="small" @click="onDel">删除</el-button>
      </el-form-item>
    </el-form>

    <div class="seldormroom">
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
        </el-table-column>
        <el-table-column
          prop="username"
          label="姓名"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="类型"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="score"
          label="分值"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="description"
          label="描述"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="日期"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          sortable>
          <template>
            <el-link type="primary">编辑</el-link>
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
        where: "day",
        time: new Date().setHours(0,0,0,0),
        month: "",
        semester: "",
        department: this.$store.state.userDepartment,
        class: "",
        student: "",
      },
      tableData:[],

      n: this.$route.query.n,
      multipleTable: [],

      departmentData: [],
      classData: [],
      stuData: [],
      semesterData: [],

      loading: false,
    }
  },
  created(){

    this.getSemester();
    this.getDepartment();
    this.getClass();
  },
  methods:{
    onSubmit(){
      let obj = {
        type: "sel_more_attendance",
        where: this.sel.where,
        sel_type: this.sel.type,
        semester: this.sel.where == "semester" ? this.sel.semester : this.$store.state.semester,
        campus: this.$store.state.userCampus,
        department: this.sel.department,
      }

      if(this.sel.where == 'day'){
        obj['time'] = this.sel.time;
      }else if(this.sel.where == 'month'){
        obj['month'] = this.sel.month;
      }

      if(this.sel.student != 'all'){
        obj['student'] = this.sel.student;
      }else if(this.sel.class != 'all'){
        obj['class'] = this.sel.class;
      }

      this.tableData = [];
      // this.loading = true;
      // requestAjax({
      //   url: "/Dormitory/getOption.php",
      //   type: "get",
      //   data:obj,
      //   success:(res)=>{
      //     this.loading = false;
      //     res = JSON.parse(res);
      //     // console.log(res);
      //     this.tableData = res;
      //     this.tableData2 = [this.tableData[0]];
      //   },
      //   async: false,
      //   error:(err)=>{
      //     this.loading = false;
      //     console.log(err);
      //     this.$notify.error({
      //       title: '错误',
      //       message: '服务器有误！,请稍后再试！'
      //     });
      //   }
      // })

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
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add',
        },
      })
    },
    onImp(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'imp',
        },
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          recordId: id,
          type: 'upa',
        },
      })
    },
    onDel(){
      let arr = [];
      $.each(this.multipleTable, function (i, v) {
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
            url: "/StuSet/AttendanceOption.php",
            type: 'post',
            data:{
              type: "del_option",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
                let start = (this.page-1)*this.total;
                this.getData("*",start,this.total);
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
  },
}
</script>

<style>

</style>