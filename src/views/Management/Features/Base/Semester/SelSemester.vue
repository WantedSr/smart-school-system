<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>学期管理</h1>
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
        :data="tableData.filter(data => !search || data.semester.toLowerCase().includes(search.toLowerCase()))"
        border
        size="small"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="semesterId"
          label="学期代码"
          align="center"
          width="110"
          sortable>
        </el-table-column>
        <el-table-column
          prop="semester"
          label="学期名称"
          align="center"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="administrative_start"
          label="行政开始时间"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="administrative_end"
          label="行政结束时间"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="teach_start"
          label="教学开始时间"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="teach_end"
          label="教学结束时间"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="founder"
          align="center"
          width="120"
          label="创建人"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template slot-scope="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.semesterId)">编辑</el-button>
            <el-button size="mini" @click="onNow(scope.row.semesterId)" :type="scope.row.state == '1' ? 'success' : 'info'" v-text="scope.row.state == '1' ? '当前学期' : '设置默认'"></el-button>
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
      tableData:[],
      n: this.$route.query.n,
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 50,
      loading: false,
    }
  },
  created(){
    let start = (this.page-1)*this.total;
    this.getData("*",start,this.total,'campus',this.$store.state.userCampus);
    this.getDataSum();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
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
          semId: id,
          type: 'upa',
        },
      })
    },
    getDate(time){
      let date = new Date();
      date.setTime(time);
      let y = date.getFullYear();
      let m = date.getMonth() + 1;
      if(String(m).length == 1) m = '0' + m;
      let d = date.getDate();
      if(String(d).length == 1) d = '0' + d;
      let arr = [y,m,d];
      return arr.join('-');
    },
    getUser(id){
      let user = '';
      requestAjax({
        type: "get",
        url: "/selUser.php",
        data:{
          id: id,
        },
        async: false,
        success:(res)=>{
          // console.log(res);
          user = JSON.parse(res)[0];
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
      return user.user_name;
    },
    onNow(id){
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "set_semester",
          id: id,
        },
        async: true,
        success:(res)=>{
          if(res){
            this.$message({
              message: '设置成功',
              type: 'success'
            });

            // 日志写入
            let obj = {
              content: "设置当前学期 "+id,
              type: "修改记录",
              model: "学期模块",
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
            this.$message.error('设置失败，请稍后再试！');
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
    },
    getData(col="*",start=0,num=15,sel=null,val=null){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_limit_semester",
          col: col,
          start: start,
          num: num,
          sel: sel,
          val: val,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          $.each(res, (i, row)=>{ 
            row.addTime = this.getDate(row.addTime);
            row.administrative_start = this.getDate(row.administrative_start);
            row.administrative_end = this.getDate(row.administrative_end);
            row.teach_start = this.getDate(row.teach_start);
            row.teach_end = this.getDate(row.teach_end);
            row.founder = this.getUser(row.founder);
          });
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
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
          col: "COUNT(*)",
          selobj: {
            'campus': this.$store.state.userCampus,
          },
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
    onDel(){
      let arr = [];
      $.each(this.multipleTable, (i, v)=>{ 
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
            url: "/base/semester.php",
            type: 'get',
            data:{
              type: "del_semester",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "删除年级记录 "+ String(JSON.stringify(this.multipleTable)),
                  type: "删除记录",
                  model: "年级模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                let start = (this.page-1)*this.total;
                this.getData("*",start,this.total);
              }else{
                console.log(res);
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
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>