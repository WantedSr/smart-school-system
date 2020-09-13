<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>实习场所管理</h1>
    </div>

    <el-form :inline="true" :model="form" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="form.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
        <el-button type="success" size="small" @click="onAdd">添加</el-button>
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
          prop="place_id"
          label="场地id"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="place_name"
          label="场地名称"
          min-width="100"
          sortable>
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
        department: this.$store.state.userDepartment,
      },

      tableData:[],
      showTable: [],

      n: this.$route.query.n,
      multipleTable: [],

      // departmentData: [],

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  props:{
    departmentData:{
      type: Array,
      default: [],
      request: true,
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
      let obj = {
        campus: this.$store.state.userCampus,
        department: this.form.department,
      }

      this.tableData = [];
      this.showTable = [];
      this.loading = true;
      requestAjax({
        url: "/SecondInspection/PracticePlace.php",
        type: "post",
        data:{
          action: "get",
          request: JSON.stringify(obj),
          created_user: true,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
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
            url: "/SecondInspection/PracticePlace.php",
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