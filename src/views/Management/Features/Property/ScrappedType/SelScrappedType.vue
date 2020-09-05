<template>
  <div class="FixedAssets">
    <div class="pagehead">
      <h1>报废类型列表</h1>
    </div>
    <div>
      <el-row>
        <el-col :lg="16" :md="16" :sm="16" :xs="24">
          <el-form :inline="true" :model="sel" class="demo-form-inline">
            <el-form-item>
              <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
              <el-button size="small" type="success" @click="onAdd">添加</el-button>
              <el-button size="small" type="danger">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="8" :md="8" :sm="8" :xs="24">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="fixedAssetsTable box">
      <el-table
        :data="tableData.filter(data => !search || data.type_name.toLowerCase().includes(search.toLowerCase()) || data.type_id.toLowerCase().includes(search.toLowerCase()))"
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
          prop="type_id"
          label="资产类别编号"
          min-width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type_name"
          label="资产类别名称"
          min-width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="created_user"
          label="创建人"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          sortable>
          <template v-slot="scope">
            <el-button @click="onUpa(scope.row.id)" size="small" type="primary" :underline="false">编辑</el-button>
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
      sel:{
        semester: 'all',
      },
      n: this.$route.query.n,
      semesterData: [],
      tableData:[],
      loading: false,
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
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
  },
  methods:{
    onSubmit(){
      let obj = null;
      this.getData(obj);
      this.getDataSum(obj);
    },
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(obj){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/property/scrappedType.php",
        data:{
          type: "sel_limit_scrapped_type",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: obj,
        },
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
    getDataSum(obj){
      requestAjax({
        type: "get",
        url: "/property/scrappedType.php",
        data:{
          type: "sel_scrapped_type",
          col: "COUNT(*)",
          selobj: obj,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.sum = parseInt(res[0]['COUNT(*)']);
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
          typeId: id,
        }
      });
    },
  },
  components:{
    Limit,
  },
}
</script>

<style>

</style>