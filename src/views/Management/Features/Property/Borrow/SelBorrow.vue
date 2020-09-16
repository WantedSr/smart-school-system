<template>
  <div class="selBorrow">
    <div class="pagehead">
      <h1>领用借用资产盘点</h1>
    </div>
    <div>
      <el-row>
        <el-col :lg="20" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select size="small" v-model="sel.semester" placeholder="选择学期">
                <el-option v-if="semesterData.length > 0" label="所有学期" value="all"></el-option>
                <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select size="small" v-model="sel.department" placeholder="选择部门">
                <el-option v-if="departmentData.length > 0" label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
              <el-button size="small" type="success" @click="onAdd">添加</el-button>
              <!-- <el-button size="small" type="warning" @click="onImp">导入</el-button> -->
              <el-button size="small" type="danger">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="4" :md="24" :sm="24" :xs="24">
          <el-input
          style="margin-bottom:12px"
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="fixedAssetsTable box">
      <el-table
        :data="tableData.filter(data => !search || data.asset_name.toLowerCase().includes(search.toLowerCase()) || data.asset_id.toLowerCase().includes(search.toLowerCase()))"
        size="small"
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
          prop="asset_id"
          label="资产编码"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="asset_name"
          label="资产名称"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="get_type"
          label="获取类型"
          width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="department"
          label="部门"
          width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="unit"
          width="80"
          label="单位"
          sortable>
        </el-table-column>
        <el-table-column
          prop="borrow_num"
          label="数量"
          width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="登记日期"
          width="110"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="100"
          sortable>
          <template v-slot="scope">
            <el-button size="small" type="primary" @click="onUpa(scope.row.id)" :underline="false">编辑</el-button>
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
        semester:"all",
        department: "all",
      },
      n: this.$route.query.n,
      tableData:[],
      loading: false,
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      semesterData: [],
      departmentData: [],
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
    this.getSemester();
    this.getDep();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getSemester(){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
          col: "*",
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterData = res;
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
    getDep(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: "*",
          sel: "campus",
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
    getData(obj){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/property/borrow.php",
        data:{
          type: "sel_limit_borrow",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: obj,
        },
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
    getDataSum(obj){
      requestAjax({
        type: "get",
        url: "/property/borrow.php",
        data:{
          type: "sel_borrow",
          col: "COUNT(*)",
          selobj: obj,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.sum = parseInt(res[0]['COUNT(*)']);
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
      let obj = this.sel.semester == 'all' ? 
        this.sel.department == 'all' ? 
          {'campus':this.$store.state.userCampus} : {'department':this.sel.department}
        : this.sel.department == 'all' ? 
          {'campus':this.$store.state.userCampus,'semester':this.sel.semester} : {'department':this.sel.department,"semester":this.sel.semester};
      this.getData(obj);
      this.getDataSum(obj); 
    },
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: "add",
        }
      });
    },
    onImp(){
      this.$router.push({
        query:{
          n: this.n,
          type: "imp",
        }
      });
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "upa",
          borId: id,
        }
      });
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
            url: "/property/borrow.php",
            type: 'get',
            data:{
              type: "del_borrow",
              sel: "id",
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
                  content: "删除资产使用记录",
                  type: "删除记录",
                  model: "资产领用模块",
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
  },
}
</script>

<style>

</style>