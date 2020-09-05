<template>
  <div class="SelCampus">
    <div class="pagehead">
      <h1>卫生项目选项</h1>
    </div>

    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="18" :sm="16" :xs="12">
          <el-button size="small" @click="onAdd" type="primary">添加</el-button>
          <el-button size="small" @click="onDel" type="danger">删除</el-button>
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
          label="项目"
          align="center"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          prop="name"
          label="项目名称"
          align="center"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          prop="score"
          label="选项数量"
          min-width="90"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.id)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import Limit from "../../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      model: "",

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
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add_list',
          optionId: this.$route.query.optionId,
        },
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          listId: id,
          listId: id,
          optionId: this.$route.query.optionId,
          type: 'upa_list',
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
          // requestAjax({
          //   url: "/base/school.php",
          //   type: 'get',
          //   data:{
          //     type: "del_school",
          //     arr: arr,
          //   },
          //   async: false,
          //   success:(res)=>{
          //     if(res){
          //       this.$message({
          //         message: '删除成功',
          //         type: 'success'
          //       });
          //       let start = (this.page-1)*this.total;
          //       this.getData("*",start,this.total);
          //     }else{
          //       this.$message.error('删除失败，请稍后再试！');
          //     }
          //   },
          //   error:(err)=>{
          //     console.log(err);
          //     this.$notify.error({
          //       title: '错误',
          //       message: '服务器有误！,请稍后再试！'
          //     });
          //   }
          // })
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
    onSubmit(){
      this.getData();
      this.getDataSum();
    },
    getData(col='*',selobj=null){
      this.loading = true;
      requestAjax({
        url: "/StuSet/HealthOption.php",
        type: 'get',
        data:{
          type: "sel_limit_option_list",
          col: col,
          start: (this.page-1)*this.total,
          num: this.total,
          selobj: {
            campus: this.$store.state.userCampus,
            option_id: this.$route.query.optionId,
          },
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
        url: "/StuSet/HealthOption.php",
        type: 'get',
        data:{
          type: "sel_option_list",
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
    setOption(id){
      this.$router.push({
        query:{
          n: this.n,
          optionId: id,
          type: 'list',
        },
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