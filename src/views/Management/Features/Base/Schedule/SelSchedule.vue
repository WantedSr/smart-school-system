<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>作息管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="16" :xs="12">
        <el-button size="small" @click="onAdd" type="primary">添加</el-button>
        <el-button size="small" @click="onDel" type="danger">删除</el-button>
      </el-col>
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
        :data="tableData.filter(data => !search || data.schedule_name.toLowerCase().includes(search.toLowerCase()) || data.schedule_id.toLowerCase().includes(search.toLowerCase()))"
        border
        size="small"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        :default-sort="{prop:'schedule_order',order:'ascending'}"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="schedule_id"
          label="作息代码"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="schedule_name"
          label="作息名称"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="schedule_order"
          label="作息顺序"
          align="center"
          min-width="100"
          :sort-method="toAsc"
          :sortable="true">
        </el-table-column>
        <el-table-column
          prop="schedule_start"
          label="作息开始时间"
          align="center"
          min-width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="schedule_end"
          label="作息结束时间"
          align="center"
          min-width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="schedule_type"
          label="作息类型"
          min-width="100"
          align="center"
          sortable>
          <template v-slot="scope">
            <el-tag :type="scope.row.schedule_type == 'teach' ? 'success' : 'primary'">{{getType(scope.row.schedule_type)}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.schedule_id)">编辑</el-button>
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
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
    }
  },
  created(){
    this.getData('*',{'campus':this.$store.state.userCampus,'status':'1'});
    this.getDataSum();
  },
  computed:{
    getType(){
      return (type)=>{
        switch(type){
          case "teach":
            return "教学课程"
            break;
          case "rest":
            return "休息时间";
            break;
          case "between_classes":
            return "课间活动";
            break;
        }
      }
    }
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    toAsc(a,b){
      let val1 = a.deadline
      let val2 = b.deadline
      return val2 - val1
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
            url: "/base/schedule.php",
            type: 'get',
            data:{
              type: "del_schedule",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              console.log(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除作息信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "作息模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);
                
                this.getData('*',{'campus':this.$store.state.userCampus,'status':'1'});
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
          scheId: id,
          type: 'upa',
        },
      })
    },
    getData(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        url: "/base/schedule.php",
        type: 'get',
        data:{
          type: "sel_limit_schedule",
          col: col,
          start: (this.page-1)*this.total,
          num: this.total,
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
        url: "/base/schedule.php",
        type: 'get',
        data:{
          type: "sel_schedule",
          col: "COUNT(*)",
          selobj: {
            'campus':this.$store.state.userCampus,
            'status': '1',
          },
        },
        async: true,
        success:(res)=>{
          res = parseInt(JSON.parse(res)[0]['COUNT(*)']);
          this.sum = res;
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