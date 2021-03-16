<template>
  <div class="selstuinfo">
    <div class="pagehead">
      <h1>学生证书列表</h1>
    </div>

      <el-row>
        <el-col :lg="24" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select @change="selClass" size="small" v-model="sel.department" placeholder="选择部门">
                <el-option v-for="(item,i) in depData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select @change="selStu" size="small" v-model="sel.class" placeholder="选择学生">
                <el-option v-if="classData.length > 0" label="所有班级" value="all"></el-option>
                <el-option v-for="(item,i) in classData" :key="'4-'+i" :label="item.class_name" :value="item.class_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="">
              <el-select size="small" v-model="sel.student" placeholder="选择学生">
                <el-option v-if="stuData.length > 0" label="所有学生" value="all"></el-option>
                <el-option v-for="(item,i) in stuData" :key="'5-'+i" :label="item.username" :value="item.userid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
              <el-button size="small" type="success" @click="onAdd">添加</el-button>
              <el-button size="small" type="warning" @click="onImp">导入</el-button>
              <el-button size="small" type="danger">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>

    <div class="">
      <el-table
        border
        size="mini"
        :data="tableData.filter(data => !search || data.userid.toLowerCase().includes(search.toLowerCase()) || data.username.toLowerCase().includes(search.toLowerCase()) || data.class.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%;margin-bottom:12px"
        :default-sort = "{prop: 'date', order: 'descending'}"
        >
        <el-table-column
          type="selection"
          width="55">
        </el-table-column>
        <el-table-column
          prop="userid"
          label="学号"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="username"
          label="姓名"
          sortable>
        </el-table-column>
        <el-table-column
          prop="sex"
          label="性别"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="class"
          label="班级"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          prop="grade"
          label="年级"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="type"
          label="状态"
          sortable>
        </el-table-column>
        <el-table-column
          prop="department"
          label="专业部"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          prop="skill"
          label="专业"
          min-width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="created_user"
          label="创建人"
          min-width="90"
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
          width="100">
          <template v-slot="scope">
            <el-button type="primary" size="mini" @click="onUpa(scope.row.userid)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import Limit from "../../../Limit/main";
export default {
  data(){
    return{
      n: this.$route.query.n,
      sel:{
        department:"all",
        skill: "all",
        grade: "all",
        class: "all",
        student: "all",
      },
      tableData:[],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 15,
      loading: false,

      stuData: [],
    }
  },
  created(){
    this.selSkill(this.sel.department);
    this.selClass();
    this.sel.class = 'all'
    this.selStu();
    this.sel.student = 'all';
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
    depData:{
      type: Array,
      require: true,
    },
    skillData:{
      type: Array,
      require: true,
    },
    gradeData:{
      type: Array,
      require: true,
    },
    classData:{
      type: Array,
      require: true,
    }
  },
  components:{
    Limit,
  },
  methods:{
    setPage(page){
      this.page = page;
      this.onSubmit();
    },
    onSubmit(){

    },
    selClass(v){
      this.sel.class = '';
      if(this.sel.skill == "" || this.sel.grade == '') return;
      this.$emit('selClass',this.sel.department,this.sel.skill,this.sel.grade);
    },
    selStu(){
      this.sel.student = '';
      let start = parseInt(this.page - 1) * this.total;
      if(this.sel.department == '' || this.sel.skill == '' || this.sel.grade == '' || this.sel.class == ''){
        this.$message('请选择完所有选项!');
        return
      };
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu_name",
          col: "userid,username",
          start: start,
          num: this.total,
          department: this.sel.department,
          skill: this.sel.skill,
          grade: this.sel.grade,
          class: this.sel.class,
          campus: this.$store.state.userCampus,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.stuData = res;
        },
        error:(err)=>{
          this.loading = false;
          console.error(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: "add",
        }
      });
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          stuId: id,
          type: 'upa',
        },
      })
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    }
  }
}
</script>

<style>

</style>