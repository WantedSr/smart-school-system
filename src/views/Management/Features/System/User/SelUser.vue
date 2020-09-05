<template>
  <div>
    <div class="pagehead">
      <h1>用户列表</h1>
    </div>
    <div>
      <el-row>
        <el-col :lg="18" :md="16" :sm="24" :xs="24">
          <el-form :inline="true" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select v-model="sel.department" placeholder="选择部门">
                <el-option label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in depData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select v-model="sel.type" placeholder="选择用户类型">
                <el-option label="所有用户类型" value="all"></el-option>
                <el-option v-for="(item,i) in authData" :key="'2-'+i" :label="item.authority_name" :value="item.authority_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
              <el-button size="small" @click="onAdd" type="success">添加</el-button>
              <el-button size="small" type="danger">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="6" :md="8" :sm="24" :xs="24">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="box">
      <el-table
        :data="tableData.filter(data => !search || data.user_id.toLowerCase().includes(search.toLowerCase()) || data.user_name.toLowerCase().includes(search.toLowerCase()) || data.user_phone.toLowerCase().includes(search.toLowerCase()))"
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
          prop="user_id"
          label="ID"
          sortable>
        </el-table-column>
        <el-table-column
          prop="user_name"
          label="用户"
          width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="password"
          label="密码"
          sortable>
          <template>******</template>
        </el-table-column>
        <el-table-column
          prop="user_phone"
          label="手机号"
          sortable>
        </el-table-column>
        <el-table-column
          prop="user_group"
          label="组别"
          sortable>
        </el-table-column>
        <el-table-column
          prop="created_user"
          label="创建人"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          sortable>
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.user_id)">编辑</el-button>
            <el-button size="mini" type="info" @click="onUpaPass(scope.row.user_id)">重置密码</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      n: this.$route.query.n,
      sel:{
        department:"all",
        type: "all",
      },
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
    depData:{
      type: Array,
      require: true,
    },
    authData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onSubmit(){
      if(this.sel.department != '' && this.sel.type != ''){
        let selobj = {};
        if(this.sel.department != 'all'){
          if(this.sel.type != 'all'){
            selobj={
              'user_dpmentid': this.sel.department,
              'user_group': this.sel.type,
            };
          }else{
            selobj={'user_dpmentid': this.sel.department,};
          }
        }else{
          if(this.sel.type != 'all'){
            selobj={
              'user_campus': this.$store.state.userCampus,
              'user_group': this.sel.type,
            };
          }else{
            selobj={'user_campus': this.$store.state.userCampus,};
          }
        }
        let start = parseInt((this.page-1))*this.total;
        requestAjax({
          type: "get",
          url: "/system/user.php",
          data:{
            type: "sel_limit_user",
            start: start,
            num: this.total,
            selobj: selobj,
          },
          async: false,
          success:(res)=>{
            // console.log(res);
            res = JSON.parse(res);
            this.tableData = res; 
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
        this.$message('请先选择必选项!');
      }
    },
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: "add",
        }
      });
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "upa",
          userId: id,
        }
      });
    },
    onUpaPass(id){
      this.$confirm('此操作有危险性，是否继续操作！', '确认信息', {
        distinguishCancelAndClose: true,
        confirmButtonText: '重置密码',
        cancelButtonText: '放弃'
      })
      .then(() => {
        requestAjax({
          type: 'get',
          url: "/system/user.php",
          data:{
            type: 'upa_user_pass',
            sel: 'user_id',
            val: id,
          },
          success:(res)=>{
            // console.log(res);
            if(res){
              this.$message({
                message: '重置密码成功',
                type: 'success'
              });
            }else{
              this.$message.error('重置失败，请稍后再试！');
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
      .catch(action => {
        this.$message({
          type: 'info',
          message: '取消操作'
        })
      });
    }
  },
}
</script>

<style>

</style>