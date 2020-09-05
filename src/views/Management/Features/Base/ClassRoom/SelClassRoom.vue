<template>
  <div class="SelClassRoom">
    <div class="pagehead">
      <h1>教室信息管理</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select @change="selCampus" v-model="sel.campus" placeholder="选择校区">
                <el-option v-if="campusData.length != 0" label="所有校区" value="all"></el-option>
                <el-option v-for="(item,i) in campusData" :key="'c-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select @change="selBuild" v-model="sel.department" placeholder="选择部门">
                <el-option v-if="departmentData.length != 0" label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select v-model="sel.build" placeholder="选择教学楼">
                <el-option v-if="buildData.length != 0" label="所有教学楼" value="all"></el-option>
                <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
              <el-button type="success" size="small" @click="onAdd">添加</el-button>
              <el-button type="warning" size="small" @click="onImp">导入</el-button>
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
    <div class="classroomTable box">
      <el-table
        :data="tableData.filter(data => !search || data.room_id.toLowerCase().includes(search.toLowerCase()) || data.room_name.toLowerCase().includes(search.toLowerCase()))"
        border
        size="small"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="room_id"
          label="教室代码"
          align="center"
          width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="room_name"
          label="门牌号"
          align="center"
          width="130"
          sortable>
        </el-table-column>
        <el-table-column
          prop="room_build"
          label="教学楼"
          align="center"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="room_floor"
          label="楼层"
          align="center"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="room_accommodate"
          label="容纳人数"
          align="center"
          width="140"
          sortable>
        </el-table-column>
        <el-table-column
          prop="room_campus"
          align="center"
          label="所在校区"
          sortable>
        </el-table-column>
        <el-table-column
          prop="room_department"
          label="所在部门"
          width="110"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          width="120"
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
            <el-button size="mini" type="primary" @click="onUpa(scope.row.room_id)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        campus: "",
        department: "",
        build: "",
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
  created(){
    this.sel.campus = 'all';
    this.selCampus('all');
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
    campusData:{
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
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(col="*",start=0,num=15,sel=null,val=null){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/base/classroom.php",
        data:{
          type: "sel_limit_classroom",
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
    getDataSum(sel,val){
      requestAjax({
        type: "get",
        url: "/base/classroom.php",
        data:{
          type: "sel_classroom",
          col: "COUNT(*)",
          sel: sel,
          val: val,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0]['COUNT(*)'];
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
    onSubmit(){
      for(var i in this.sel){
        if(this.sel[i] == ""){
          this.$message.error('请选择选项！');
          return false;
        }
      }
      let sel = this.sel.build != 'all' ? 
        'room_build' : this.sel.department != 'all' ? 
          "room_department" : this.sel.campus != 'all' ? 
            'room_campus' : 'room_school';
      let val = this.sel.build != 'all' ? 
        this.sel.build : this.sel.department != 'all' ? 
          this.sel.department : this.sel.campus != 'all' ? 
            this.sel.campus : this.$store.state.userSchool;
      let start = (this.page-1)*this.total;
      this.getData("*",start,this.total,sel,val);
      this.getDataSum(sel,val);
    },
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add',
        },
      })
    },
    onImp(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'imp',
        },
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          roomId: id,
          type: 'upa',
        },
      })
    },
    selCampus(v){
      this.sel.department = '';
      this.sel.build = '';
      this.$emit('getDep',v);
    },
    selBuild(v){
      this.sel.build = '';
      this.$emit('getBud',v,this.sel.campus);
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
            url: "/base/classroom.php",
            type: 'get',
            data:{
              type: "del_classroom",
              arr: arr,
            },
            async: false,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
                this.getData(this.sel.campus,this.sel.department,this.sel.build,this.$store.state.userSchool);
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