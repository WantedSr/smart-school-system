<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>年级管理</h1>
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
        size="small"
        :data="tableData.filter(data => !search || data.grade_id.toLowerCase().includes(search.toLowerCase()) || data.grade_name.toLowerCase().includes(search.toLowerCase()))"
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
          prop="grade_id"
          label="年级代码"
          align="center"
          width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="grade_name"
          label="年级名称"
          align="center"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态"
          align="center"
          sortable>
          <template v-slot="scope">
            <el-tag :type="scope.row.state == 1 ? 'success' : 'primary'">
              {{getState(scope.row.state)}}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          align="center"
          sortable>
          <template v-slot="scope">
              {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.grade_id)">编辑</el-button>
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
    this.getDataSum({'campus':this.$store.state.userCampus});
  },
  computed:{
    getState(){
      return (type)=>{
        switch(type){
          case "1":
            return "在读";
            break;
          case "0":
            return "毕业";
            break;
        }
      }
    },
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
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getDataSum(selobj=null){
      requestAjax({
        url: "/base/grade.php",
        type: 'get',
        data:{
          type: "sel_grade",
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
    getData(col='*',selobj=null){
      this.loading = true;
      requestAjax({
        url: "/base/grade.php",
        type: 'get',
        data:{
          type: "sel_limit_grade",
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
          gradeId: id,
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
            url: "/base/grade.php",
            type: 'get',
            data:{
              type: "del_grade",
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

                // 日志写入
                let obj = {
                  content: "删除年级记录 "+ String(JSON.stringify(this.multipleTable)),
                  type: "删除记录",
                  model: "年级模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.getData("*",{'campus':this.$store.state.userCampus});
                this.getDataSum({'campus':this.$store.state.userCampus});
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