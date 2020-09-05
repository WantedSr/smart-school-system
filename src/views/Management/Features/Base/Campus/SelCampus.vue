<template>
  <div class="SelSemester">
    
    <div class="pagehead">
      <h1>校区管理</h1>
    </div>

    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="24" :xs="24">
          <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select v-model="school" placeholder="选择学校">
                <el-option label="所有学校" value="all"></el-option>
                <el-option v-for="(item,i) in schoolData" :key="i" :label="item.school_name" :value="item.school_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
              <el-button type="success" size="small" @click="onAdd">添加</el-button>
              <el-button type="danger" size="small" @click="onDel">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="4" :md="6" :sm="24" :xs="24">
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
        size="small"
        :data="tableData.filter(data => !search || data.campus_name.toLowerCase().includes(search.toLowerCase()) || data.campus_school.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        border
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="campus_id"
          label="校区编码"
          align="center"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="campus_name"
          label="校区名称"
          align="center"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="campus_position"
          label="校区地址"
          align="center"
          width="200"
          sortable>
        </el-table-column>
        <el-table-column
          prop="campus_school"
          label="所在学校"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.campus_id)">编辑</el-button>
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
      tableData: [],
      n: this.$route.query.n,
      multipleTable: [],
      school: "all",
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
    }
  },
  created(){
    let start = (this.page-1)*this.total;
    let sel = this.school == 'all' ? null : "campus_school";
    let val = this.school == 'all' ? null : this.school;
    this.getDataSum(sel,val);
    this.getData("*",start,this.total,sel,val);
  },
  props:{
    schoolData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    onSubmit(v){
      let start = (this.page-1)*this.total;
      let sel = this.school == 'all' ? null : "campus_school";
      let val = this.school == 'all' ? null : this.school;
      this.getData("*",start,this.total,sel,val);
      this.getDataSum(sel,val);
    },
    getData(col="*",start=0,num=15,sel=null,val=null){
      this.loading = true;
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_limit_campus", 
          start: start,
          num: num,
          sel: sel,
          val: val,
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
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus",
          col: "COUNT(*)",
          sel: sel,
          val: val,
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
          campusId: id,
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
            url: "/base/campus.php",
            type: 'get',
            data:{
              type: "del_campus",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              if(res){
                console.log(res);
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "删除校区信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "校区模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.getData();
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
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>