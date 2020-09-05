<template>
  <div>
    <div class="pagehead">
      <h1>组织架构列表</h1>
    </div>
    
    <div style="margin-bottom:24px">
      <el-button type="success" size="small" @click="onAdd">添加</el-button>
      <el-button type="info" size="small" @click="onSet">OA权限设置</el-button>
      <el-button type="danger" size="small" @click="onDel">删除</el-button>  
    </div>
    
    <div class="box">
      <el-table
        :data="tableData"
        border 
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
          width="40"
          type="selection">
        </el-table-column>
        <el-table-column
          prop="organization_id"
          label="编码"
          align="center"
          min-width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="organization_name"
          label="名称"
          align="center"
          min-width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="department_name"
          label="部门"
          align="center"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="510"
          sortable>
          <template v-slot="scope">
            <el-button type="primary" @click="onUpa(scope.row.id)" size="mini">编辑</el-button>
            <el-button type="warning" size="mini" @click="onResponsible(scope.row.id,scope.row.department,'sponsor')">设置发起人</el-button>
            <el-button type="info" size="mini" @click="onResponsible(scope.row.id,scope.row.department,'department')">设置部门负责人意见</el-button>
            <el-button type="info" size="mini" @click="onResponsible(scope.row.id,scope.row.department,'course')">设置部门课程负责人意见</el-button>
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
      tableData:[],
      n: this.$route.query.n,
      
      multipleTable: [],
      sum: 0,
      page: 1,
      total: 50,
    }
  },
  created(){
    this.onSubmit();
  },
  methods:{
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
          ognId: id,
        }
      });
    },
    onResponsible(id,dep,type){
      this.$router.push({
        query:{
          n: this.n,
          type: "responsible",
          form: type,
          dep: dep,
          ognId: id,
        }
      });
    },
    onSet(){
      this.$router.push({
        query:{
          n: this.n,
          type: "set",
        }
      });
    },
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Process/Organization.php",
        data: {
          type: "sel_limit_organization",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: {
            campus: this.$store.state.userCampus,
          }
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
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    getDataSum(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Process/Organization.php",
        data: {
          type: "sel_organization",
          col: "COUNT(*)",
          selobj: {
            campus: this.$store.state.userCampus
          }
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0]['COUNT(*)'];
          this.sum = parseInt(res);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    onSubmit(){
      this.getData();
      this.getDataSum();
    },
    setPage(page){  
      this.page = page;
      this.onSubmit()
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
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
            url: "/Office/Process/Organization.php",
            type: 'post',
            data:{
              type: "del_organization",
              arr: arr,
              sel: 'id',
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              // console.log(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除组织架构信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "组织架构模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);
                
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
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>