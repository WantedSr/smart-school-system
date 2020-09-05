<template>
  <div>

    <div class="pagehead">
      <h1>支出类型列表</h1>
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
          width="50"
          align="center"
          type="selection">
        </el-table-column>
        <el-table-column
          prop="type_id"
          min-width="100"
          label="支出类型编码"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type_name"
          label="支出类型名称"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          sortable>
          <template v-slot="scope">
            <el-button type="primary" @click="onUpa(scope.row.type_id)" size="mini">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      tableData:[],
      n: this.$route.query.n,
      loading: false,
      multipleTable: [],
    }
  },
  created(){
    this.onSubmit();
  },
  methods:{
    onSubmit(){
      this.getData();
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
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
            url: "/Office/Process/ExpenditureType.php",
            type: 'post',
            data:{
              type: "del_expenditure_type",
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
                  content: "删除支出类型信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "支出类型模块",
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
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Process/ExpenditureType.php",
        data: {
          type: "sel_expenditure_type",
          selobj:{
            campus: this.$store.state.userCampus,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
          // console.log(res);
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
    }
  },
}
</script>

<style>

</style>