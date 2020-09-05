<template>
  <div>
    <div class="pagehead">
      <h1>权限组列表</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="16" :xs="12">
          <el-button size="small" @click="onAdd" type="success">添加</el-button>
          <el-button size="small" type="danger" @click="onDel">删除</el-button>
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
    <div class="box">
      <el-table
        border 
        :data="tableData.filter(data => !search || data.authority_id.toLowerCase().includes(search.toLowerCase()) || data.authority_name.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        size="small"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="authority_id"
          width="100"
          align="center"
          label="分组ID"
          sortable>
        </el-table-column>
        <el-table-column
          prop="authority_name"
          align="center"
          label="分组名称"
          width="130"
          sortable>
        </el-table-column>
        <el-table-column
          prop="authority_range"
          align="left"
          label="权限"
          sortable>
          <template v-slot="scope">
            {{scope.row.authority_range == '' ? '无' : scope.row.authority_range}}
          </template>
        </el-table-column>
        <el-table-column
          prop="created_user"
          align="center"
          width="100"
          label="创建人"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          align="center"
          label="创建时间"
          width="130"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          align="center"
          label="操作"
          width="100"
          sortable>
          <template v-slot="scope">
            <el-link @click="onUpa(scope.row.authority_id)" :underline="false">编辑</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
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
  created(){
    this.tableData = this.getData("*",{'campus':this.$store.state.userCampus});
  },
  methods:{
    onSubmit(){
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
          authId: id, 
          type: "upa",
        }
      });
    },
    getData(col='*',selobj=null){
      this.loading = true;
      let arr = [];
      requestAjax({
        type: "get",
        url: "/system/authority.php",
        async: false,
        data:{
          type: "sel_limit_authority",
          col: col,
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: selobj,
        },
        success:(res)=>{
          this.loading = false;
          arr = JSON.parse(res);
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
      return arr;
    },
    onDel(){
      this.$message({
        message: '无法删除权限组！',
        type: 'warning'
      });
    },
  },
}
</script>

<style>

</style>