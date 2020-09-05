<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>课程类型管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="16" :xs="12">
          <el-button size="small" @click="onAdd" type="primary">添加</el-button>
          <el-button size="small" @click="onDel" type="danger">删除</el-button>
        </el-col>
        <el-col :lg="4" :md="6" :sm="8" :xs="12">
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
        border
        size="mini"
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
          prop="course_id"
          label="课程类型代码"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_name"
          label="课程类型名称"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="course_score"
          label="课程类型学分"
          align="center"
          width="200"
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
      tableData:[],
      n: this.$route.query.n,
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
    }
  },
  created(){
    this.getData("*",{'campus':this.$store.state.userCampus});
    this.getDataSum("*",{'campus':this.$store.state.userCampus});
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        url: "/base/courseCredit.php",
        type: 'get',
        data:{
          type: "sel_limit_course_credit",
          col: col,
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: selobj,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
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
            url: "/base/courseCredit.php",
            type: 'get',
            data:{
              type: "del_course_credit",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              if(res){
                // console.log(res);
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
                this.getData("*",{'campus':this.$store.state.userCampus});
                this.getDataSum("*",{'campus':this.$store.state.userCampus});
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
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add',
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
    getDataSum(col="*",selobj=null){
      requestAjax({
        url: "/base/courseCredit.php",
        type: 'get',
        data:{
          type: "sel_course_credit",
          col: "COUNT(*)",
          selobj: selobj,
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
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>