<template>
  <div class="SelDepartment">
    <div class="pagehead">
      <h1>部门管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="18" :md="16" :sm="24" :xs="24">
          <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select v-model="sel.campus" placeholder="选择校区">
                <el-option label="所有校区" value="all"></el-option>
                <el-option v-for="(item,i) in campusData" :key="i" :label="item.campus_name" :value="item.campus_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
              <el-button type="success" size="small" @click="onAdd">添加</el-button>
              <el-button type="danger" size="small" @click="onDel">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="6" :md="8" :sm="24" :xs="24">
          <el-input
            placeholder="输入部门名称搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="departmentTable box">
      <el-table
        :data="tableData.filter(data => !search || data.department_id.toLowerCase().includes(search.toLowerCase()) || data.department_name.toLowerCase().includes(search.toLowerCase()))"
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
          prop="department_id"
          label="部门代码"
          align="center"
          width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="department_name"
          label="部门名称"
          align="center"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="campus"
          align="center"
          label="所在校区"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态"
          align="center"
          sortable>
          <template slot-scope="scope">
            <el-tag size="small" :type="scope.row.department_state == '1' ? 'success' : 'danger'">{{scope.row.department_state == '1' ? '启用' : '禁用'}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.department_id)">编辑</el-button>
            <el-button size="mini" @click="onForbid(scope.row.department_id,scope.row.department_state)" :type="scope.row.department_state == '1' ? 'danger' : 'success'" v-text="scope.row.department_state == '1' ? '禁用' : '启用'"></el-button>
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
        campus: "all",
      },
      n: this.$route.query.n,
      tableData:[],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
    }
  },
  props:{
    campusData:{
      type: Array,
      require: true,
    }
  },
  created(){
    this.$store.commit("getUser");
    let start = (this.page-1)*this.total;
    this.getData("*",start,this.total);
    this.getDataSum();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(col='*',start=0,num=15){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_limit_department",
          col: col,
          start: start,
          num: num,
          sel: this.sel.campus == 'all' ? 'school' : 'campus',
          val: this.sel.campus == 'all' ? this.$store.state.userSchool : this.sel.campus,
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
    getDataSum(sel=null,val=null){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          col: "COUNT(*)",
          sel: this.sel.campus == 'all' ? 'school' : 'campus',
          val: this.sel.campus == 'all' ? this.$store.state.userSchool : this.sel.campus,
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
      let start = (this.page-1)*this.total;
      this.getData("*",start,this.total);
      this.getDataSum();
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
          departmentId: id,
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
        requestAjax({
          url: "/base/department.php",
          type: 'get',
          data:{
            type: "del_department",
            arr: arr,
          },
          async: false,
          success:(res)=>{
            // console.log(arr);
            if(res){
              this.$message({
                message: '删除成功',
                type: 'success'
              });
            return this.getData('*','school',this.$store.state.userSchool);
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
      }else{
        this.$message.error('请选择选项后在进行操作！');
        return;
      }
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onForbid(id,now){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "upa_department",
          obj:{
            department_state: now == 1 ? 0 : 1,
          },
          sel: "department_id",
          val: id,
        },
        async: true,
        success:(res)=>{
          if(res){
            this.$message({
              message: '修改部门状态成功',
              type: 'success'
            });
            return this.getData();
          }
          else{
            this.$message.error('修改失败，请稍后再试！');
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
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>