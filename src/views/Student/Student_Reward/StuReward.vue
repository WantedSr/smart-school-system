<template>
  <div class="Stureward box por">
    <el-page-header @back="goBack" content="奖惩情况"></el-page-header>

    <div class="moreScore">
      本学期总分：<el-tag size="small" v-if="semester != ''">{{score}}</el-tag>
    </div>
    <div class="selSemester">
      <span>请选择学期</span>
      <el-select @change="onSubmit" size="small" v-model="semester" placeholder="查询学期">
        <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
      </el-select>
    </div>

    <el-table
      v-if="semester != ''"
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
        prop="date"
        label="日期"
        sortable
        width="180"
      >
      </el-table-column>
      <el-table-column
        prop="type"
        label="性质"        
        :filter-method="filterTag" 
        filter-placement="bottom-end"
        width="180">
      </el-table-column>
      <el-table-column
        prop="address"
        label="原因"
        :formatter="formatter">
      </el-table-column>
      <el-table-column
        prop="tag"
        label="分数"
        width="100">
        <template slot-scope="scope">
          <el-tag
            :type="scope.row.tag < 0 ? 'danger' : 'success'"
            disable-transitions>{{scope.row.tag}}</el-tag>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
export default {
  data(){
    return{
      score: 100,
      tableData:[],
      showTable: [],

      semesterData: [],
      semester: "",

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  created(){
    this.getSemester();
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
    getSemester(){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterData = res;
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
    onSubmit(){
      this.loading = true;
      requestAjax({
        type: "post",
        url: "/StuSet/SutScore.php",
        data:{
          action: "getSumScore",
          student: this.$store.state.userId,
          field: JSON.stringify({
            semester: this.$store.state.semester,
          }),
        },
        async: true,
        success:res=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
        },
        error:err=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
      
    },
    goBack() {
      this.$router.go(-1);
    },
  }
}
</script>

<style>
  .Stureward{
    height: 755px;
  }
  .Stureward>.selSemester>span{
    margin-right: 12px;
  }
  .Stureward>.el-page-header{
    margin-bottom: 30px;
  }
  .Stureward>.moreScore{
    margin-bottom: 16px;
  }
  .el-select{
    margin-bottom: 30px;
  }
  .pagination{
    margin: 0 auto;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
  }
</style>