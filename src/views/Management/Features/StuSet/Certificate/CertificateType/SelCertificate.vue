<template>
  <div class="SelCampus">
    <div class="pagehead">
      <h1>学校管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="18" :md="16" :sm="14" :xs="12">
          <el-button size="small" @click="onAdd" type="primary">添加</el-button>
          <el-button size="small" @click="onDel" type="danger">删除</el-button>
        </el-col>
        <el-col :lg="6" :md="8" :sm="10" :xs="12">
          <el-input
            placeholder="输入关键字搜索"
            size="small"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="campusTable box">
      <el-table
        size="small"
        :data="tableData.filter(data => !search || data.certificate_name.toLowerCase().includes(search.toLowerCase()) || data.issue.toLowerCase().includes(search.toLowerCase()))"
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
          width="40">
        </el-table-column>
        <el-table-column
          prop="certificate_name"
          label="证书名称"
          min-width="250"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="类别"
          align="center"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="lever"
          label="等级"
          align="center"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="issue"
          label="颁发部门"
          align="center"
          min-width="200"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.school_id)">编辑</el-button>
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
      total: 15,
      loading: false,
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
      this.onSubmit()
    },
    onSubmit(){
      let start = (this.page-1)*this.total;
      this.getData("*",start,this.total);
      this.getDataSum();
    },
    getData(){
      this.loading = true;
      requestAjax({
        url: "/StuManagement/Certificate/CertificateType.php",
        type: 'get',
        data:{
          type: "sel_limit_type",
          start: (this.page-1)*this.total,
          num: this.total,
          col: "*",
          selobj: {
            campus: this.$store.state.userCampus,
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
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
        url: "/StuManagement/Certificate/CertificateType.php",
        type: 'get',
        data:{
          type: "sel_type",
          col: "COUNT(*)",
          selobj:{
            campus: this.$store.state.userCampus,
          }
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
          typeId: id,
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
          this.loading = true;
          requestAjax({
            url: "/StuManagement/Certificate/CertificateType.php",
            type: 'post',
            data:{
              type: "del_type",
              sel: 'id',
              arr: arr,
            },
            async: true,
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除证书类型 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "专业证书模块",
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
              this.loading = false;
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            }
          });
          // console.log(arr);
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
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>