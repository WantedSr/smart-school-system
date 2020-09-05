<template>
  <div class="SelClass">
    <div class="pagehead">
      <h1>班级管理</h1>
    </div>
    
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="20" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select @change="selProfession" v-model="sel.department" placeholder="选择部门">
                <el-option v-if="departmentData.length != ''" label="所有部门" value="all"></el-option>
                <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select v-model="sel.profession" placeholder="选择专业">
                <el-option v-if="professionData.length != ''" label="所有专业" value="all"></el-option>
                <el-option v-for="(item,i) in professionData" :key="'2-'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select v-model="sel.grade" placeholder="选择年级">
                <el-option v-if="gradeData.length != ''" label="所有年级" value="all"></el-option>
                <el-option v-for="(item,i) in gradeData" :key="'3-'+i" :label="item.grade_name" :value="item.grade_id"></el-option>
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

    <div class="ClassTable box">
      <el-table
        :data="tableData.filter(data => !search || data.class_id.toLowerCase().includes(search.toLowerCase()) || data.class_name.toLowerCase().includes(search.toLowerCase()) || data.class_grade.toLowerCase().includes(search.toLowerCase()))"
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
          width="40">
        </el-table-column>
        <el-table-column
          prop="class_id"
          label="班级代码"
          align="center"
          min-width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class_name"
          label="班级名称"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class_campus"
          label="所在校区"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class_department"
          label="所在部门"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class_skill"
          align="center"
          label="所在专业"
          min-width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class_grade"
          label="入学年份"
          min-width="100"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态"
          min-width="70"
          align="center"
          sortable>
          <template v-slot="scope">
            <el-tag :type="scope.row.state == 1 ? 'success' : 'warning'">{{scope.row.state == 1 ? "在读" : "毕业"}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          min-width="100"
          align="center"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="80"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.class_id)">编辑</el-button>
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
        department: 'all',
        profession: "",
        grade: 'all',
      },
      n: this.$route.query.n,
      tableData: [],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
    }
  },
  created(){
    this.selProfession('all');
    this.sel.profession = 'all';
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
    professionData:{
      type: Array,
      require: true,
    },
    gradeData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(col="*",dep,pro,grade,start=0,num=15){
      this.loading = true;
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_limit_class",
          campus: this.$store.state.userCampus,
          department: dep,
          profession: pro,
          grade: grade,
          start: start,
          num: num, 
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
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
          col: "COUNT(*)",
          selobj: selobj,
        },
        async: true,
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
      if(this.sel.department != '' && this.sel.profession != '' && this.sel.grade != ''){
        let start = (this.page-1)*this.total;
        let sel = 
        this.sel.profession != 'all' ? 
          this.sel.grade != 'all' ? {'class_grade':this.sel.grade,'class_skill':this.sel.profession} : {'class_skill':this.sel.profession}
          : this.sel.department != 'all' ? 
            this.sel.grade != 'all' ? 
              {'class_grade':this.sel.grade,'class_department':this.sel.department} : {'class_department':this.sel.department}
            : this.sel.grade != 'all' ? 
              {'class_grade':this.sel.grade,'class_campus':this.$store.state.userCampus} : {"class_campus":this.$store.state.userCampus}
        this.getData("*",this.sel.department,this.sel.profession,this.sel.grade,start,this.total);
        this.getDataSum(sel);
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
    onImp(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'imp',
        },
      })
    },
    selProfession(v){
      this.sel.profession = '';
      this.$emit('getPro',v);
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          classId: id,
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
            url: "/base/class.php",
            type: 'get',
            data:{
              type: "del_class",
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
                  content: "删除班级 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "班级模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.getData("*",this.sel.department,this.sel.profession,this.sel.grade);
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