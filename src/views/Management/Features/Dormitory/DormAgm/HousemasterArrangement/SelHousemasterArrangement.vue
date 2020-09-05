<template>
  <div class="subpage">

    <div class="semesterArrangement_sel" style="margin-bottom: 12px">

      <div class="pagehead">
        <h1>宿管管理安排列表</h1>
      </div>

      <div class="sel">
        <el-row>
          <el-col :lg="24" :md="24" :sm="24" :xs="24">
            <el-form :inline="true" :model="sel" class="demo-form-inline">
              <el-form-item label="">
                <el-select size="mini" v-model="sel.semester" placeholder="选择学期">
                  <el-option v-if="semesterData.length > 0" label="所有学期" value="all"></el-option>
                  <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="">
                <el-select @change="selDormbuild" size="mini" v-model="sel.department" placeholder="选择部门">
                  <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
                  <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="">
                <el-select size="mini" v-model="sel.dormbuild" placeholder="选择宿舍楼">
                  <el-option v-if="buildData.length > 0" label="所有宿舍楼" value="all"></el-option>
                  <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="">
                <el-button size="mini" type="primary" @click="onSubmit">查询</el-button>
                <el-button size="mini" type="success" @click="onAdd">添加</el-button>
                <el-button size="mini" type="warning" @click="onImport">导入</el-button>
                <el-button size="mini" type="danger" @click="onDel">删除</el-button>
              </el-form-item>
            </el-form>
          </el-col>
          <el-col style="margin-bottom: 12px" :lg="24" :md="24" :sm="24" :xs="24">
            <el-input
              placeholder="输入关键字搜索"
              size="small"
              v-model="search">
              <i slot="prefix" class="el-input__icon el-icon-search"></i>
            </el-input>
          </el-col>
        </el-row>
      </div>

      <div class="selTable">
        <el-table
          :data="tableData.filter(data => !search || data.housemaster.toLowerCase().includes(search.toLowerCase()) || data.dormroom_id.toLowerCase().includes(search.toLowerCase()))"
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
            prop="dormroom_id"  
            label="宿舍号"
            sortable>
          </el-table-column>
          <el-table-column
            prop="housemaster"
            label="宿管"
            sortable>
          </el-table-column>
          <el-table-column
            prop="build"
            label="宿舍楼"
            sortable>
          </el-table-column>
          <el-table-column
            prop="campus"
            label="校区"
            sortable>
          </el-table-column>
          <el-table-column
            prop="semester"
            label="学期"
            sortable>
          </el-table-column>
          <el-table-column
            prop="created_user"
            label="创建人"
            sortable>
          </el-table-column>
          <el-table-column
            prop="addTime"
            label="创建时间"
            sortable>
            <template v-slot="scope">
              {{getDate(scope.row.addTime)}}
            </template>
          </el-table-column>
          <el-table-column
            prop="do"
            label="操作"
            sortable>
            <template v-slot="scope">
              <el-button type="primary" size="mini" @click="onUpa(scope.row.id)">编辑</el-button>
            </template>
          </el-table-column>
        </el-table>
      </div>
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
      sel:{
        semester: this.$store.state.semester,
        department: "all",
        dormbuild: "all",
      },
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
  props:{
    semesterData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    },
    buildData:{
      type: Array,
      require: true,
    },
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
    this.selDormbuild(this.sel.department);
    this.sel.dormbuild = 'all';
    // console.log(this.buildData);
  },
  methods:{
    onSubmit(){
      this.loading = true;
      let start = parseInt(this.page-1) * this.total;
      if(this.sel.semester == '' || this.sel.department == '' || this.sel.dormbuild == ''){
        this.$message('请选择完所有选项!');
        return false;
      }
      let obj = this.sel.dormbuild == 'all' ? 
        this.sel.department == 'all' ? 
          this.sel.semester == 'all' ? 
            {'campus':this.$store.state.userCampus} : {"campus":this.$store.state.userCampus,"semester":this.sel.semester}
          : this.sel.semester == 'all' ? 
            {'department':this.sel.department} : {"department":this.sel.department,'semester':this.sel.semester}
        : this.sel.semester == 'all' ? 
          {"build":this.sel.dormbuild} : {"build":this.sel.dormbuild,"semester":this.sel.semester};
      let reqType = this.sel.dormbuild == 'all' ? 
        this.sel.department == 'all' ? 
          this.sel.semester == 'all' ? 
            "campus" : "semester" 
          : "department"
        : 'build';
      this.getData("*",start,this.total,obj,reqType);
      this.getSumData(obj,reqType);
    },
    onAdd(){
      this.$router.push({
        query:{
          type: 'add',
          n: this.n,
        }
      })
    },
    onImport(){
      this.$router.push({
        query:{
          type: 'import',
          n: this.n,
        }
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
            url: "/dormitory/housemaster.php",
            type: 'post',
            data:{
              type: "del_housemaster",
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
                  content: "删除学期宿管安排信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "宿管安排模块",
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
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onUpa(id){
      this.$router.push({
        query:{
          type: 'upa',
          n: this.n,
          dormId: id,
        }
      })
    },
    selDormbuild(dep){
      this.sel.dormbuild = "";
      this.$emit("getDormBuild",dep);
    },
    getData(col="*",start=0,num=15,selobj=null,reqType){
      requestAjax({
        type: "get",
        url: "/dormitory/housemaster.php",
        data:{
          reqType: reqType,
          type: 'sel_limit_housemaster',
          selobj: selobj,
          col: "*",
          start: start,
          num: this.total,
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
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getSumData(selobj=null,reqType){
      requestAjax({
        type: "get",
        url: "/dormitory/housemaster.php",
        data:{
          reqType: reqType,
          type: 'sel_housemaster_count',
          selobj: selobj,
          col: "COUNT(*)",
        },
        success:(res)=>{
          res = JSON.parse(res)[0]["COUNT(*)"];
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
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>