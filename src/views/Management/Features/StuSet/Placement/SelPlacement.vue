<template>
  <div class="selPlacement">
    <div class="pagehead">
      <h1>学期学生分班</h1>
    </div>
    <div>
      <el-form :inline="true" :model="sel" class="demo-form-inline">
        <el-form-item label="">
          <el-select @change="getClass" size="mini" v-model="sel.semester" placeholder="选择学期">
            <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="getClass " size="mini" v-model="sel.department" placeholder="选择部门">
            <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select size="mini" v-model="sel.class" placeholder="选择班级">
            <el-option v-if="classData.length > 0" label="所有班级" value="all"></el-option>
            <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
          <el-button size="small" type="success" @click="onAdd">添加</el-button>
          <el-button size="small" type="warning" @click="onImp">导入</el-button>
          <el-button size="small" type="danger" @click="onDel">删除</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="box">
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
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="class_name"
          label="班级"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="student"
          label="学号"
          min-width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="student_name"
          label="姓名"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="department_name"
          label="部门"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="semester_name"
          label="学期"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="位置"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="80">
          <template v-slot="scope">
            <el-link @click="onUpa(scope.row.id)" :underline="false">编辑</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>
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
        semester: this.$store.state.semester,
        department: 'all',
        class: "all",
      },
      multipleTable: [],
      n: this.$route.query.n,
      tableData:[],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
      tableData:[],
    }
  },
  props:{
    semesterData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    },
    classData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onSubmit(){

      for(let i in this.sel){
        if(this.sel[i] == null || this.sel[i] == ""){
          this.$message({
            message:"请选择完所有选项",
            type: 'warning'
          });
          return false;
        }
      }

      let obj = {
        semester: this.sel.semester,
        campus: this.$store.state.userCampus,
      };
      if(this.sel.class != 'all'){
        obj['class'] = this.sel.class;
      }else if(this.sel.department != 'all'){
        obj['department'] = this.sel.department;
      }

      this.getData("*",obj);
      this.getDataSum(obj);

    },
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: "add",
        }
      });
    },
    onImp(){
      this.$router.push({
        query:{
          n: this.n,
          type: "imp",
        }
      });
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "upa",
          placeId: id,
        }
      });
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
            url: "/StuSet/Placement.php",
            type: 'post',
            data:{
              type: "del_placement",
              sel: 'id',
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
                  content: "删除学期分班信息信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "学期分班模块",
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
    getClass(){
      this.sel.class = '';
      this.$emit("getClass",this.sel.semester,this.sel.department);
    },
    getData(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuSet/Placement.php",
        data: {
          col: col,
          selobj: selobj,
          start: (this.page-1)*this.total,
          num: this.total,
          type: "sel_limit_placement",
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.tableData = res;
          this.loading = false;
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
    getDataSum(selobj=null){
      requestAjax({
        type: "get",
        url: "/StuSet/Placement.php",
        data: {
          col: "COUNT(*)",
          selobj: selobj,
          type: "sel_placement",
        },
        success:(res)=>{
          res = JSON.parse(res)[0]['COUNT(*)'];
          this.sum = parseInt(res);
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
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
  }
}
</script>

<style>

</style>