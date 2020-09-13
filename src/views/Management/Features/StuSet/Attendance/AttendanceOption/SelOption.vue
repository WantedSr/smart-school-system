<template>
  <div class="SelCampus">
    <div class="pagehead">
      <h1>考勤项目管理</h1>
    </div>

    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="16" :xs="12">
          <el-form :inline="true">
            <el-form-item>
              <el-select @change="onSubmit" size="small" v-model="model" placeholder="隶属模块">
                <el-option label="所有项目" value="all"></el-option>
                <el-option label="课堂登记" value="课堂登记"></el-option>
                <el-option label="早段登记" value="早段登记"></el-option>
                <el-option label="课间操登记" value="课间操登记"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button size="small" @click="onAdd" type="primary">添加</el-button>
              <el-button size="small" @click="onDel" type="danger">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="4" :md="6" :sm="8" :xs="12">
          <el-input
          v-model="search"
          size="small"
          placeholder="输入关键字名称搜索"/>
        </el-col>
      </el-row>
    </div>

    <div class="AddOptionTable box">
      <el-table
        size="small"
        :data="tableData.filter(data => !search || data.option_id.toLowerCase().includes(search.toLowerCase()) || data.option_name.toLowerCase().includes(search.toLowerCase()))"
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
          prop="option_id"
          label="ID"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="model"
          label="模块"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="option_name"
          label="项目名称"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="类型"
          align="center"
          sortable>
          <template v-slot="scope">
            <el-tag size="small" :type="scope.row.type == 'attendance' ? 'success' : 'primary'">{{scope.row.type == 'attendance' ? '出勤状况' : "违纪情况"}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="score"
          label="分值"  
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.option_id)">编辑</el-button>
            <el-button size="mini" :type="scope.row.state == '1' ? 'danger' : 'success'" @click="open(scope.row.option_id,scope.row.state)">{{ scope.row.state == '1' ? '禁用' : '启用' }}</el-button>
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
      search: "",
      sum: 0,
      page: 1,
      total: 50,
      loading: false,

      model: 'all',

    }
  },
  created(){
    this.onSubmit();
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
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    onSubmit(){

      let obj = {};
      if(this.model == 'all'){
        obj = {
          'campus': this.$store.state.userCampus,
          'status': 1,
        }
      }else{
        obj = {
          'campus': this.$store.state.userCampus,
          'status': 1,
          'model': this.model,
        }
      }

      this.getData("*",obj);
      this.getDataSum(obj);
    },
    getData(col='*',selobj=null){
      this.loading = true;
      requestAjax({
        url: "/StuSet/AttendanceOption.php",
        type: 'get',
        data:{
          type: "sel_limit_option",
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
    getDataSum(selobj=null){
      requestAjax({
        url: "/StuSet/AttendanceOption.php",
        type: 'get',
        data:{
          type: "sel_option",
          col: "COUNT(*)",
          selobj: selobj
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
          optionId: id,
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
            url: "/StuSet/AttendanceOption.php",
            type: 'post',
            data:{
              type: "del_option",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
                let start = (this.page-1)*this.total;
                this.getData("*",start,this.total);
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
    open(id,state){
      this.loading = true;
      requestAjax({
        url: "/StuSet/AttendanceOption.php",
        type: 'post',
        data:{
          type: "upa_option",
          obj:{
            'state': state == 1 ? 0 : 1,
          },
          sel: 'option_id',
          val: id,
        },
        async: true,
        success:(res)=>{
          this.loading = false;

          res = JSON.parse(res);
          if(res){
            // console.log(res);
            this.$message({
              message: (state == 1 ? '禁用' : "启用" ) + '考勤选项成功',
              type: 'success'
            });
          
            // 日志写入
            let obj = {
              content: (state == 1 ? '禁用' : "启用" ) + "考勤选项 "+ id,
              type: "修改记录",
              model: "考勤选项模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);
          }
          else{
            this.$message.error('添加失败，请稍后再试！');
          }
          
          this.onSubmit();
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
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>