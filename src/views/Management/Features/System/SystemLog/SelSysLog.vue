<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>系统日志</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
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
        :data="tableData.filter(data => !search || data.log_content.toLowerCase().includes(search.toLowerCase()) || data.log_type.toLowerCase().includes(search.toLowerCase()) || data.log_ip.toLowerCase().includes(search.toLowerCase()))"
        border
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        size="small"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          prop="id"
          label="日志代码"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_content"
          label="日志内容"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_type"
          label="日志类型"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_model"
          label="所属模块"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_ip"
          label="所在IP"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_user"
          label="操作用户"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_area"
          align="center"
          label="操作地址"
          sortable>
        </el-table-column>
        <el-table-column
          prop="log_brower"
          label="操作工具"
          align="center"
          sortable>
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
      total: 30,
      loading: false,
    }
  },
  created(){
    this.getData("*",this.total);
    this.getDataSum();
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
        let h = date.getHours();
        h = String(h).length == 1 ? '0'+h : h;
        let i = date.getMinutes();
        i = String(i).length == 1 ? '0'+i : i;
        let se = date.getSeconds();
        se = String(se).length == 1 ? '0'+se : se;
        return y+"-"+m+"-"+d+" "+h+":"+i+":"+se;
      }
    }
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(col="*",num=15,selobj=null){
      this.loading = true;
      requestAjax({
        url: "/system/systemLog.php",
        type: 'get',
        data:{
          type: "sel_limit_log",
          col: col,
          start: (this.page-1)*this.total,
          num: num,
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
    getDataSum(){
      requestAjax({
        url: "/system/systemLog.php",
        type: 'get',
        data:{
          type: "sel_log",
          col: "COUNT(*)",
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
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>