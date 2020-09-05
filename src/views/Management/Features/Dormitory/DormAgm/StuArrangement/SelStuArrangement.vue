<template>
  <div class="subpage">

    <div class="semesterArrangement_sel">
      <div class="pagehead">
        <h1>学生宿舍安排</h1>
      </div>

      <div class="sel">
        <el-row>
          <el-col :lg="24" :md="24" :sm="24" :xs="24">
            <el-form :inline="true" :model="sel" class="demo-form-inline">
              <el-form-item label="">
                <el-select @change="getClass" size="mini" v-model="sel.semester" placeholder="选择学期">
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
                <el-select size="mini" v-model="sel.class" placeholder="选择班级">
                  <el-option v-if="classData.length > 0" label="所有班级" value="all"></el-option>
                  <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="">
                <el-select @change="getDormroom" size="mini" v-model="sel.build" placeholder="选择宿舍楼">
                  <el-option v-if="buildData.length > 0" label="所有宿舍楼" value="all"></el-option>
                  <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
                </el-select>
              </el-form-item>
              <el-form-item label="">
                <el-select size="mini" v-model="sel.room" placeholder="查询宿舍">
                  <el-option v-if="dormroomData.length > 0" label="所有宿舍" value="all"></el-option>
                  <el-option v-for="(item,i) in dormroomData" :key="'dr-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
                </el-select>
              </el-form-item>
              <div>
                <el-form-item label="">
                  <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
                  <el-button size="small" type="success" @click="onAdd">添加</el-button>
                  <el-button size="small" type="warning" @click="onImport">导入</el-button>
                  <el-button size="small" type="danger" @click="onDel">删除</el-button>
                </el-form-item>
              </div>
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
      <div class="selTable"  style="margin-bottom: 12px">
        <el-table
          :data="tableData.filter(data => !search || data.student_name.toLowerCase().includes(search.toLowerCase()) || data.dormroom.toLowerCase().includes(search.toLowerCase()))"
          ref="multipleTable"
          tooltip-effect="dark"
          @selection-change="handleSelectionChange"
          v-loading="loading"
          element-loading-text="拼命加载中"
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(0, 0, 0, 0.8)"
          border
          size="mini"
          style="width: 100%">
          <el-table-column
            type="selection"
            width="55">
          </el-table-column>
          <el-table-column
            prop="student_name"
            min-width="80"
            label="姓名"
            sortable>
          </el-table-column>
          <el-table-column
            prop="class"
            min-width="100"
            label="班级"
            sortable>
          </el-table-column>
          <el-table-column
            prop="dormroom"
            min-width="100" 
            label="宿舍号"
            sortable>
          </el-table-column>
          <el-table-column
            prop="build"
            label="宿舍楼"
            min-width="100"
            sortable>
          </el-table-column>
          <el-table-column
            prop="department"
            label="部门"
            sortable>
          </el-table-column>
          <el-table-column
            prop="semester"
            min-width="140"
            label="学期"
            sortable>
          </el-table-column>
          <el-table-column
            prop="do"
            label="操作"
            sortable>
            <template v-slot="scope">
              <el-button size="mini" type="primary" @click="onUpa(scope.row.id)">编辑</el-button>
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
        semester: "all",
        department:"all",
        build: "all",
        class: "all",
        room: "all",
      },
      n: this.$route.query.n,
      tableData:[],
      
      classData: [],
      
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
    classData:{
      type: Array,
      require: true,
    },
    dormroomData:{
      type: Array,
      require: true,
    }
  },
  created(){
    this.selDormbuild(this.sel.department);
    this.sel.class = 'all';
    this.sel.build = 'all';
    this.getDormroom(this.sel.build);
    this.sel.room = 'all';
  },
  methods:{
    onSubmit(){

      this.search = '';

      for(let i in this.sel){
        if(this.sel[i] == null || this.sel[i] == "" || this.sel[i] == undefined){
          this.$message({
            message: '请选择完所有选项!',
            type: 'warning'
          });
          return false;
        } 
      }

      let obj = {};
      if(this.sel.room != 'all'){
        if(this.sel.class != 'all'){
          obj = {campus: this.$store.state.userCampus , dormroom: this.sel.room , class: this.sel.class};
        }else if(this.sel.department != 'all'){
          obj = {dormroom: this.sel.room,campus: this.$store.state.userCampus , department: this.sel.department};
        }else if(this.sel.semester != 'all'){
          obj = {dormroom: this.sel.room,campus: this.$store.state.userCampus , semester: this.sel.semester};
        }else{
          obj = {dormroom: this.sel.room,campus: this.$store.state.userCampus};
        }
      }else if(this.sel.build != 'all'){
        if(this.sel.class != 'all'){
          obj = {campus: this.$store.state.userCampus , build: this.sel.build , class: this.sel.class};
        }else if(this.sel.department != 'all'){
          obj = {build: this.sel.build,campus: this.$store.state.userCampus , department: this.sel.department};
        }else if(this.sel.semester != 'all'){
          obj = {build: this.sel.build,campus: this.$store.state.userCampus , semester: this.sel.semester};
        }else{
          obj = {build: this.sel.build,campus: this.$store.state.userCampus};
        }
      }else{
        if(this.sel.class != 'all'){
          obj = {campus: this.$store.state.userCampus , class: this.sel.class};
        }else if(this.sel.department != 'all'){
          obj = {campus: this.$store.state.userCampus , department: this.sel.department};
        }else if(this.sel.semester != 'all'){
          obj = {campus: this.$store.state.userCampus , semester: this.sel.semester};
        }else{
          obj = {campus: this.$store.state.userCampus};
        }
      }

      // console.log(obj);

      this.getData("*",obj);
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
            url: '/dormitory/stuArrangement.php',
            type: 'post',
            data:{
              type: "del_dormroom",
              sel: 'id',
              arr: arr,
            },
            async: true,
            success:(res)=>{
              
              // console.log(res);
              res = JSON.parse(res);

              if(res){

                this.$message({
                  message: '删除成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "删除学期宿舍安排信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "学期宿舍安排模块",
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
          })
        })
        .catch(action=>{
          this.$message('取消操作');
        })
      }else{
        this.$message.error('请选择选项后在进行操作！');
      }
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
    getClass(){
      this.sel.class = '';
      this.$emit("getClass",this.sel.semester,this.sel.department);
    },
    getData(col="*",selobj=null){
      this.loading = true;
      requestAjax(({
        type: 'get',
        url: '/dormitory/stuArrangement.php',
        data:{
          type: 'sel_limit_dormroom',
          start: parseInt(this.page-1) * this.total,
          num: this.total,
          selobj: selobj,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
          // console.log(res);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      }))
    },
    getDataSum(selobj){
      this.loading = true;
      requestAjax(({
        type: 'get',
        url: '/dormitory/stuArrangement.php',
        data:{
          type: 'sel_dormroom',
          col: "COUNT(*)",
          selobj: selobj,
        },
        success:(res)=>{
          res = JSON.parse(res)[0]['COUNT(*)'];
          this.sum = parseInt(res);
          // console.log(res);
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      }))
    },
    selDormbuild(dep){
      this.sel.build = "";
      this.sel.class = "";
      this.sel.room = "";
      this.$emit("getDormBuild",dep);
      this.$emit("getClass",this.sel.semester,this.sel.department);
    },
    getDormroom(build){
      this.sel.room = "",
      this.$emit('getDormroom',this.sel.build);
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