<template>
  <div class="selDormRoom">
    <div class="pagehead">
      <h1>宿舍房间列表</h1>
    </div>
    <div class="sel">
      <el-row>
        <el-col style="margin-bottom: 12px" :lg="20" :md="18" :sm="24" :xs="24">
          <el-select style="margin-right: 12px" size="small" v-model="build" placeholder="选择宿舍楼">
            <el-option v-if="buildData.length > 0" label="所有宿舍楼" value="all"></el-option>
            <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
          </el-select>
          <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
          <el-button size="small" type="success" @click="onAdd">添加</el-button>
          <el-button size="small" type="warning" @click="onImp">导入</el-button>
          <el-button size="small" type="danger" @click="onDel">删除</el-button>
        </el-col>
        <el-col style="margin-bottom: 12px" :lg="4" :md="6" :sm="24" :xs="24">
          <el-input
            placeholder="输入关键字搜索"
            size="small"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="selTable box">
      <el-table
        :data="tableData.filter(data => !search || data.dormroom_id.toLowerCase().includes(search.toLowerCase()) || data.dormroom_name.toLowerCase().includes(search.toLowerCase()))"
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
          label="房间号"
          sortable>
        </el-table-column>
        <el-table-column
          prop="dormroom_name"
          label="房间名称"
          sortable>
        </el-table-column>
        <el-table-column
          prop="dormroom_bed_num"
          label="可用床位"
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
          prop="created_user"
          label="创建人"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="校区"
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
            <el-button size="mini" type="primary" @click="onUpa(scope.row.dormroom_id)">编辑</el-button>
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
      build: "all",
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
  props:{
    buildData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    onSubmit(){
      let start = parseInt(this.page-1) * this.total;
      let obj = this.build == 'all' ? {'campus':this.$store.state.userCampus} : {
        'campus': this.$store.state.userCampus,
        'build': this.build,
      }
      this.getData("*",start,this.total,obj);
      this.getDataSum(obj);
    },
    onAdd(){
      this.$router.push({
        query:{
          type: 'add',
          n: this.n,
        }
      })
    },
    onImp(){
      this.$router.push({
        query:{
          type: 'import',
          n: this.n,
        }
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          type: 'upa',
          n: this.n,
          roomId: id,
        }
      })  
    },
    getData(col="*",start=0,num=15,selobj=null){
      this.loading = true;
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_limit_dormroom",
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
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_dormroom",
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
            url: "/dormitory/dormroom.php",
            type: 'post',
            data:{
              type: "del_dormroom",
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
                  content: "删除宿舍信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "宿舍模块",
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
  }
}
</script>

<style>

</style>