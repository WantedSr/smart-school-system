<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>宿舍楼列表</h1>
    </div>
    <div style="margin-bottom: 12px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="16" :xs="12">
          <el-button size="small" @click="onAdd" type="success">添加</el-button>
          <el-button size="small" @click="onDel" type="danger">删除</el-button>
        </el-col>
        <el-col :lg="4" :md="6" :sm="8" :xs="12">
          <el-input
            placeholder="输入关键字搜索"
            size="small"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="selBuilTable box">
      <el-table
        :data="tableData.filter(data => !search || data.build_id.toLowerCase().includes(search.toLowerCase()) || data.build_name.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        border
        size="small"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="55">
        </el-table-column>
        <el-table-column
          prop="build_id"
          min-width="100"
          label="宿舍楼代码"
          sortable>
        </el-table-column>
        <el-table-column
          prop="build_name"
          label="宿舍楼名称"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="build_floor_num"
          label="楼层数"
          min-width="80"
          sortable> 
        </el-table-column>
        <el-table-column
          prop="campus"
          label="所在校区"
          sortable> 
        </el-table-column>
        <el-table-column
          prop="department"
          label="所属部门"
          sortable> 
        </el-table-column>
        <el-table-column
          prop="type"
          label="宿舍类型"
          sortable> 
        </el-table-column>
        <el-table-column
          prop="status"
          label="状态"
          sortable>
          <template v-slot="scope">
            <el-tag size="small" :type="scope.row.status == '1' ? 'success' : 'danger'">{{scope.row.status == '1' ? '启用' : "废弃"}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="created_user"
          label="创建人"
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
          min-width="140"
          sortable>
          <template v-slot="scope">
            <el-button type="primary" size="mini" @click="onUpa(scope.row.build_id)">编辑</el-button>
            <el-button :type="scope.row.status == '1' ? 'danger' : 'success'" size="mini" @click="setStatus(scope.row.build_id,scope.row.status)">{{scope.row.status == '1' ? '禁用' : '启用' }}</el-button>
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
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
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
    this.onSubmit();
  },
  methods:{
    onSubmit(){
      let start = (this.page-1)*this.total;
      this.getData("*",start,this.total,{
        'campus': this.$store.state.userCampus,
      });
      this.getDataSum({
        'campus': this.$store.state.userCampus,
      });
    },
    setPage(page){
      this.page = page;
      this.getData()
    },
    onUpa(id){
      this.$router.push({
        query:{
          type: 'upa',
          n: this.n,
          buildId: id,
        }
      })
    },
    onAdd(){
      this.$router.push({
        query:{
          type: 'add',
          n: this.n,
        }
      })
    },
    getData(col="*",start=0,num=15,selobj=null){
      this.loading = true;
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "sel_limit_build",
          col: col,
          start: start,
          num: num,
          selobj: selobj,
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
    getDataSum(selobj){
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          col: "COUNT(*)",
          selobj: selobj,
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
    setStatus(id,status){
      this.loading = true;
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "upa_build",
          sel: 'build_id',
          val: id,
          obj: {
            'status': status == '1' ? '0' : '1',
          }
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          if(res){
            this.$message({
              message: '修改状态成功',
              type: 'success'
            });
            let start = (this.page-1)*this.total;
            this.getData("*",start,this.total,{
              'campus': this.$store.state.userCampus,
            });
          }
          else{
            this.$message.error('修改失败，请稍后再试！');
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
      })
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
            url: "/dormitory/build.php",
            type: 'post',
            data:{
              type: "del_build",
              sel: 'id',
              arr: arr,
            },
            async: true,
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
                  content: "删除宿舍楼信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "宿舍楼模块",
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
  },
  components:{
    Limit,
  },
}
</script>

<style>

</style>