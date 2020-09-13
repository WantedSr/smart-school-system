<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>事务管理</h1>
    </div>

    <el-form :inline="true" :model="form" class="demo-form-inline">
      <el-form-item label="">
        <el-select size="small" v-model="form.type" placeholder="选择类型">
          <el-option label="教师" value="0"></el-option>
          <el-option label="学生" value="1"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker size="small" value-format="timestamp" type="date" placeholder="选择日期" v-model="form.addDate" style="width: 100%;"></el-date-picker>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
        <el-button type="danger" size="small" @click="onDel">删除</el-button>
      </el-form-item>
    </el-form>

    <div class="table">
      <el-table
        :data="showTable"
        size="small"
        v-loading="loading"
        border
        stripe
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50"  
        ></el-table-column>
        <el-table-column 
          prop="title"
          label="事务"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="类型"
          min-width="100"
          sortable>
          <template v-slot="scope">
            <el-tag size="small" :type="scope.row.type == 0 ? 'success' : 'danger'">{{ scope.row.type == 0 ? '教师' : '学生' }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="start_time"
          label="开始时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ getTime(scope.row.start_time) }}
          </template>
        </el-table-column>
        <el-table-column
          prop="end_time"
          label="结束时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ getTime(scope.row.end_time) }}
          </template>
        </el-table-column>
        <el-table-column
          prop="created_user"
          label="创建人"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ getDate(scope.row.addTime) }}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          sortable>
          <template v-slot="scope">
            <el-link @click="onUpa(scope.row.id)" type="primary">编辑</el-link>
          </template>
        </el-table-column>

      </el-table>

    </div>
    
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import Limit from "../../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form:{
        type: "0",
        addDate: new Date().setHours(0,0,0,0),
      },

      tableData:[],
      showTable: [],

      n: this.$route.query.n,
      multipleTable: [],

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  created(){
    this.onSubmit();
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
      for(let i in this.form){
        if(this.form[i] == ''){
          this.$message({
            message: "请选择完所有选项!",
            type: "warning",
          })
          return false;
        }
      }
      let obj = {
        campus: this.$store.state.userCampus,
        department: this.$store.state.department,
        addDate: this.form.addDate,
        type: this.form.type,
      }

      this.tableData = [];
      this.showTable = [];
      this.loading = true;
      requestAjax({
        url: "/Affairs/Affairs.php",
        type: "post",
        data:{
          action: "get",
          request: JSON.stringify(obj),
          cu: 1,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          if(res.data.length > 0){
            this.tableData = res.data;
            this.setPage(1);
          }
        },
        async: true,
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
            url: "/Affairs/Affairs.php",
            type: 'post',
            data:{
              action: "del",
              id: arr,
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              if(res.data){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
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
    handleSelectionChange(val) {
      this.multipleTable = val;
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
    getTime(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let h = date.getHours();
        let m = date.getMinutes() + 1;
        h = String(h).length == 1 ? '0'+h : h;
        m = String(m).length == 1 ? '0'+m : m;
        return h+":"+m;
      }
    },
    sum(){
      return this.tableData.length
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