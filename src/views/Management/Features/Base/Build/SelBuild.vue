<template>
  <div class="selBuild">
    <div class="pagehead">
      <h1>教学楼管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select @change="selCampus" v-model="sel.campus" placeholder="选择校区">
                <el-option v-if="campusData.length != 0" label="所有校区" value="all"></el-option>
                <el-option v-for="(item,i) in campusData" :key="'1-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select v-model="sel.department" placeholder="选择部门">
                <el-option v-if="departmentData.length != 0" label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in departmentData" :key="'2-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
              <el-button type="success" size="small" @click="onAdd">添加</el-button>
              <el-button type="danger" size="small" @click="onDel">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="4" :md="24" :sm="24" :xs="24">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="buildTable box">
      <el-table
        :data="tableData.filter(data => !search || data.build_id.toLowerCase().includes(search.toLowerCase()) || data.build_name.toLowerCase().includes(search.toLowerCase()))"
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
          prop="build_id"
          label="教学楼代码"
          align="center"
          width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="build_name"
          label="教学楼名称"
          align="center"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="build_department"
          label="部门"
          width="110"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="build_campus"
          label="校区"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          width="110"
          align="center"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.build_id)">编辑</el-button>
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
      sel:{
        campus: "all",
        department: "",
      },
      multipleTable: [],
      n: this.$route.query.n,
      tableData:[],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
    }
  },
  created(){
    this.selCampus(this.sel.campus);
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
  props:{
    departmentData:{
      type: Array,
      require: true,
    },
    campusData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    getData(col="*",start=0,num=15,sel=null,val=null){
      this.loading = true;
      requestAjax({
        url: "/base/build.php",
        type: 'get',
        data:{
          type: "sel_limit_build",
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
          this.tableData = res;
          // console.log(res)
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
        url: "/base/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          col: "COUNT(*)",
          sel: sel,
          val: val,
        },
        async: true,
        success:(res)=>{
          // console.log(res);
          res = JSON.parse(res)[0]['COUNT(*)'];
          this.sum = res;
          // console.log(res)
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
    onSubmit(){
      if(this.sel.campus != '' && this.sel.department != ''){
        let start = (this.page-1)*this.total;
        let sel = this.sel.department == 'all' ? this.sel.campus == 'all' ? 'build_school' : 'build_campus' : "build_department";
        let val = this.sel.department == 'all' ? this.sel.campus == 'all' ? this.$store.state.userSchool : this.sel.campus : this.sel.department;
        this.getData("*",start,this.total,sel,val);
        this.getDataSum(sel,val);
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
          buildId: id,
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
            url: "/base/build.php",
            type: 'get',
            data:{
              type: "del_build",
              // sel: "id",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除教学楼信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "教学楼模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.getData("*",this.sel.campus,this.sel.department);
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
    selCampus(v){
      this.sel.department = '';
      this.$emit('getDep',v);
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