<template>
  <div>

    <div class="pagehead">
      <h1>配置审批表单</h1>
    </div>

    <div style="margin-bottom: 24px">
      <el-button size="small" @click="onAdd" type="success">添加</el-button>
      <el-button size="small" type="danger">删除</el-button>
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
        size="mini"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="40">
        </el-table-column>
        <el-table-column
          prop="form_name"
          label="申请名称"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="title"
          label="申请题头"
          min-width="250"
          sortable>
        </el-table-column>
        <el-table-column
          prop="form_num"
          label="审批节点数量"
          min-width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态"
          min-width="80"
          sortable>
          <template slot-scope="scope">
            <el-button size="mini" @click="setState(scope.row.id,scope.row.state)" :type="scope.row.state == '1' ? 'danger' : 'success'">{{scope.row.state == '1' ? '禁用' : '启用'}}</el-button>
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="250"
          sortable>
          <template v-slot="scope">
            <el-button @click="onUpa(scope.row.id)" size="mini" type="primary">编辑</el-button>
            <el-button @click="selProcess(scope.row.form_id)" size="mini" type="warning">选择流程</el-button>
            <el-button @click="setProcess(scope.row.form_id)" size="mini" type="info">设置流程</el-button>
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
      n: this.$route.query.n,
      tableData:[],
      
      multipleTable: [],
      sum: 0,
      page: 1,
      total: 50,
      loading: false,
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
          proId: id,
        }
      });
    },
    
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Form.php",
        data: {
          type: "sel_limit_form",
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
        url: "/Office/Form.php",
        data: {
          type: "sel_form",
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
    setState(id,state){
      this.loading = true;
      requestAjax({
        type: 'post',
        url: "/Office/Form.php",
        data:{
          type: "upa_form",
          obj: {
            state: state == '1' ? '0' : '1',
          },
          sel: 'id',
          val: id,
        },
        success:(res)=>{
          res = JSON.parse(res);
          if(res){
            this.$message({
              message: '恭喜你，修改成功',
              type: 'success'
            });
          
            // 日志写入
            let obj = {
              content: "修改审批表单状态 表单"+ id,
              type: "修改记录",
              model: "审批表单模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);

            this.onSubmit();
          }
          else{
            this.$message.error('修改失败，请稍后再试！');
          }
        }
      })
    },


    selProcess(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "sel_pro",
          proId: id,
        }
      });
    },
    setProcess(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "set_pro",
          proId: id,
        }
      });
    },
  },
  computed:{
  },
  components:{
    Limit,
  }
} 
</script>

<style>

</style>