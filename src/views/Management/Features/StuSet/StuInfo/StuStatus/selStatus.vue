<template>
  <div class="selstuinfo">
    <div class="pagehead">
      <h1>学生学籍状态列表</h1>
    </div>
    <el-row style="margin-bottom: 12px">
      <el-col :lg="18" :md="16" :sm="14" :xs="16">
        <el-button size="small" type="success" @click="onAdd">添加</el-button>
        <el-button size="small" type="danger">删除</el-button>
      </el-col>
      <el-col :lg="6" :md="8" :sm="10" :xs="8">
        <el-input
          placeholder="输入关键字搜索"
          size="mini"
          v-model="search">
          <i slot="prefix" class="el-input__icon el-icon-search"></i>
        </el-input>
      </el-col>
    </el-row>
    <div class="">
      <el-table
        border
        size="mini"
        :data="tableData.filter(data => !search || data.status_id.toLowerCase().includes(search.toLowerCase()) || data.status_name.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%;margin-bottom:12px"
        :default-sort = "{prop: 'date', order: 'descending'}"
        >
        <el-table-column
          type="selection"
          width="55">
        </el-table-column>
        <el-table-column
          prop="status_id"
          label="编号"
          sortable>
        </el-table-column>
        <el-table-column
          prop="status_name"
          label="奖惩名称"
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
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          sortable>
          <template v-slot="scope">
            <el-button type="primary" size="mini" @click="onUpa(scope.row.status_id)">编辑</el-button>
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
      n: this.$route.query.n,
      tableData:[],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 25,
      loading: false,
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      type: "get",
      url: "/StuManagement/StuInfo/schoolStatus.php",
      data:{
        type: "sel_limit_status",
        start: parseInt(this.page-1)*this.total,
        num: this.total,
        selobj: {
          'campus':this.$store.state.userCampus,
        }
      },
      success:(res)=>{
        this.loading = false;
        res = JSON.parse(res);
        // console.log(res);
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
    requestAjax({
      type: "get",
      url: "/StuManagement/StuInfo/schoolStatus.php",
      data:{
        type: "sel_status",
        col: "COUNT(*)",
      },
      success:(res)=>{
        this.loading = false;
        res = JSON.parse(res)[0]["COUNT(*)"];
        // console.log(res);
        this.sum = parseInt(res);
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
  props:{
  },
  components:{
    Limit,
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
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
          statusId: id,
          type: 'upa',
        },
      })
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    filterTag(value, row) {
      return row.tag === value;
    },
  }
}
</script>

<style>

</style>