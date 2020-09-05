<template>
  <div>
    <div class="pagehead">
      <h1>审批流程列表</h1>
    </div>
    <div style="margin-bottom:24px">
      <el-button type="success" size="small" @click="onAdd">添加</el-button>
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
          prop="approval_id"
          min-width="110"
          label="编码"
          sortable>
        </el-table-column>
        <el-table-column
          prop="approval_name"
          min-width="120"
          label="审批流程名称"
          sortable>
        </el-table-column>
        <el-table-column
          prop="approval_type"
          min-width="120"
          label="审批人员设置"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="200"
          sortable>
          <template v-slot="scope">
            <el-button type="primary" @click="onUpa(scope.row.id)" size="mini">编辑</el-button>
            <el-button @click="onSet(scope.row.id)" v-if="scope.row.approval_type == '统一设置'" type="info" size="mini">设置审批人员</el-button>
            <el-button @click="toOgn" v-else-if="scope.row.approval_type == '分部设置'" type="danger" size="mini">前往设置</el-button>
            <el-button @click="toForm" v-else-if="scope.row.approval_type == '分类设置'" type="danger" size="mini">前往设置</el-button>
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
      
      
      multipleTable: [],
      sum: 0,
      page: 1,
      total: 50,
      n: this.$route.query.n,
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
          appId: id,
        }
      });
    },
    onSet(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "set",
          appId: id,
        }
      });
    },
    
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Process/Approval.php",
        data: {
          type: "sel_limit_approval",
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
        url: "/Office/Process/Approval.php",
        data: {
          type: "sel_approval",
          col: "COUNT(*)",
          selobj: {
            campus: this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          if(res.length <= 0){
            res = 0;
          }else{
            res = res[0]['COUNT(*)'];
          }
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
            url: "/Office/Process/Approval.php",
            type: 'post',
            data:{
              type: "del_approval",
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


    toOgn(){
      this.$router.push({
        path: "/management/office/process/organization",
        query:{
          n: this.n,
        }
      });
    },
    toForm(){
      this.$router.push({
        path: "/management/office/form",
        query:{
          n: this.n,
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