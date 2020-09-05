<template>
  <div class="selTeacherInfo">
    <div class="pagehead">
      <h1>教师列表</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="18" :md="18" :sm="16" :xs="12">      
          <el-button type="danger" size="small" @click="onDel">删除</el-button>
        </el-col>
        <el-col :lg="6" :md="6" :sm="8" :xs="12">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="teaInfoTable box">
      <el-table
        :data="tableData.filter(data => !search || data.teacher_name.toLowerCase().includes(search.toLowerCase()) || data.userid.toLowerCase().includes(search.toLowerCase()) || data.teacher_sfz.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        size="mini"
        border
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          type="selection">
        </el-table-column>
        <el-table-column
          prop="userid"
          label="ID"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          prop="username"
          min-width="100"
          sortable
          label="姓名">
        </el-table-column>
        <el-table-column
          prop="teacher_sfz"
          sortable
          min-width="150"
          label="身份证">
        </el-table-column>
        <el-table-column
          prop="type"
          sortable
          min-width="95"
          label="在职状态">
          <template slot-scope="scope">
            <el-tag size="small" :type="scope.row.type == '在职' ? 'success' : 'warning'">{{scope.row.type}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="teacher_job"
          sortable
          min-width="100"
          label="类型">
        </el-table-column>
        <el-table-column
          sortable
          min-width="150"
          label="户籍">
          <template slot-scope="scope">
            {{scope.row.house_register_provinces}}-{{scope.row.house_register_city}}-{{scope.row.house_register_area}}
          </template>
        </el-table-column>
        <el-table-column
          prop="created_user"
          sortable
          min-width="95"
          label="创建人">
        </el-table-column>
        <el-table-column
          prop="addTime"
          min-width="100"
          label="创建时间"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          width="120"
          label="操作">
          <template v-slot="scope">
            <el-link style="margin-right:6px" @click="onUpa(scope.row.userid)" :underline="false">编辑</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import Limit from "../../../Limit/main";
export default {
  data(){
    return{
      tableData:[],
      n: this.$route.query.n,
      search: "",
      multipleTable: [],
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
    this.getData();
    this.getDataSum();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(){
      this.search = '';
      let start = parseInt(this.page - 1) * this.total;
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_limit_tea",
          start: start,
          num: this.total,
          selobj: {
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
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
    getDataSum(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "COUNT(*)",
          selobj: {
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          // console.log(res);
          res = JSON.parse(res)[0]["COUNT(*)"];
          // this.loading = false;
          this.sum = parseInt(res);
        },
        error:(err)=>{
          // this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "upa",
          teaId: id,
        }
      });
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>