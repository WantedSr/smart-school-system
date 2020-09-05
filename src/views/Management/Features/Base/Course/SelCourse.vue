<template>
  <div class="SelCourse">
    <div class="pagehead">
      <h1>课程管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select @change="selProfession" v-model="sel.department" placeholder="选择部门">
                <el-option v-if="departmentData.length != ''" label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select v-model="sel.profession" placeholder="选择专业">
                <el-option v-if="professionData.length != ''" label="所有专业" value="all"></el-option>
                <el-option v-for="(item,i) in professionData" :key="'2-'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
              <el-button type="success" size="small" @click="onAdd">添加</el-button>
              <el-button type="warning" size="small" @click="onImp">导入</el-button>
              <el-button type="danger" size="small" @click="onDel">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="4" :md="24" :sm="24" :xs="24">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="semesterTable box">
      <el-table
        :data="tableData.filter(data => !search || data.course_id.toLowerCase().includes(search.toLowerCase()) || data.course_name.toLowerCase().includes(search.toLowerCase()))"
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
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="course_id"
          label="课程代码"
          align="center"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_name"
          label="课程名称"
          align="center"
          width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_type"
          label="课程类型"
          align="center"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_campus"
          align="center"
          label="所在校区"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_department"
          label="所在部门"
          width="110"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_profession"
          label="所在专业"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.course_id)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        department: "all",
        profession: "",
      },
      n: this.$route.query.n,
      tableData: [],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 50,
      loading: false,
    }
  },
  created(){
    this.selProfession('all');
    this.sel.profession = 'all';
  },
  props:{
    departmentData:{
      type: Array,
      require: true,
    },
    professionData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    getData(col="*",start=0,num=15,sel=null,val=null){
      this.loading = true;
      requestAjax({
        url: "/base/course.php",
        type: 'get',
        data:{
          type: "sel_limit_course",
          col: col,
          start: start,
          num: num,
          sel: sel,
          val: val,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
        },
        error:(err)=>{
          console.log(err);
          this.loading = false;
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getDataSum(sel=null,val=null){
      requestAjax({
        url: "/base/course.php",
        type: 'get',
        data:{
          type: "sel_course",
          col: "COUNT(*)",
          sel: sel,
          val: val,
        },
        async: true,
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
    onSubmit(){
      if(this.sel.department != '' && this.sel.profession != ''){
        let start = (this.page-1)*this.total;
        let sel = this.sel.profession == 'all' ? this.sel.department == 'all' ? 'course_campus' : 'course_department' : "course_profession";
        let val = this.sel.profession == 'all' ? this.sel.department == 'all' ? this.$store.state.userCampus : this.sel.department : this.sel.profession;
        this.getData("*",start,this.total,sel,val);
        this.getDataSum(sel,val);
      }
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
          courseId: id,
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
            url: "/base/course.php",
            type: 'get',
            data:{
              type: "del_course",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              console.log(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除课程信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "课程模块",
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
          });
        })
        .catch(action=>{
          this.$message('取消操作');
        })
      }else{
        this.$message.error('请选择选项后在进行操作！');
      }
    },
    selProfession(v){
      this.sel.profession = '';
      this.$emit('getPro',v);
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>