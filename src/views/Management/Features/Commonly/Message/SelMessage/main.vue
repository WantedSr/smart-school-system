<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>通知发布查询</h1>
    </div>

    <div>
      <el-form :inline="true" :model="sel" class="demo-form-inline">
        <el-form-item label="">
          <el-select size="small" v-model="form.semester" placeholder="查询学期">
            <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
        </el-form-item>
      </el-form>
    </div>

    <div class="table">
      <el-table
        :data="showTable"
        size="small"
        v-loading="loading"
        border
        stripe
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%;">
        <el-table-column
          prop="title"
          min-width="130"
          sortable
          align="center"
          label="通知标题">
        </el-table-column>
        <el-table-column
          prop="type"
          min-width="100"
          sortable
          align="center"
          label="通知类型">
          <template v-slot="scope">
            {{ scope.row.type == 'student' ? '学生' : '教师' }}
          </template>
        </el-table-column>
        <el-table-column
          prop="target"
          sortable
          min-width="110"
          align="center"
          label="通知范围">
          <template v-slot="scope">
            {{ scope.row.target == 'class' ? 
              '班级' : scope.row.target == 'grade' ? 
                '年级' : scope.row.target == 'department' ? 
                  '部门' : scope.row.target == 'campus' ? 
                    '校区' : "无" }}
          </template>
        </el-table-column>
        <el-table-column
          prop="aims"
          sortable
          align="center"
          min-width="100"
          label="通知对象">
          <template v-slot="scope">
            {{ scope.row.target == 'class' ? 
              scope.row.aims.class_name : scope.row.target == 'grade' ? 
                scope.row.aims.grade_name : scope.row.target == 'department' ? 
                  scope.row.aims.department_name : scope.row.target == 'campus' ? 
                    scope.row.aims : "无" }}
          </template>
        </el-table-column>
        <el-table-column
          prop="created_user"
          sortable
          align="center"
          min-width="100"
          label="创建人">
        </el-table-column>
        <el-table-column
          prop="addTime"
          sortable
          align="center"
          min-width="100"
          label="创建时间">
          <template v-slot="scope">
            {{ getDate(scope.row.addTime) }}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          sortable
          align="center"
          min-width="100"
          label="操作">
          <template v-slot="scope">
            <el-button v-if="scope.row.show == 1" type="danger" @click="onDel(scope.row.id)" size="small">撤回</el-button>
            <el-tag v-else-if="scope.row.show == 0" type="danger" size="small">已删除</el-tag>
          </template>
        </el-table-column>
      </el-table>
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
    return {
      form:{
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
      },
      tableData:[],
      showTable: [],

      semesterData: [],

      loading: false,
      
      page: 1,
      total: 50,
    }
  },
  created(){
    this.getSemester();
    this.onSubmit();
  },
  methods:{
    setPage(page){
      this.page = page;
      let start = ((this.page-1)*this.total);
      let end;
      if(start + this.total >= this.tableData.length){
        end = this.tableData.length;
      }else{
        end = start + this.total;
      }
      this.showTable = [];
      for(let i = start;i < end ; i++){
        this.showTable.push(this.tableData[i]);
      }
    },
    getSemester(){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
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
    onSubmit(){
      this.tableData = [];
      this.showTable = [];
      this.loading = true;
      requestAjax({
        url: "/Message/Message.php",
        type: "post",
        data:{
          action: "getMessage",
          request: JSON.stringify({
            semester: this.form.semester,
            campus: this.$store.state.userCampus,
          }),
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          if(res.data.length > 0){
            let data = res.data;
            data.sort((a,b)=>b.addTime - a.addTime);
            this.tableData = data;
            this.setPage(1);
          }
        },
        async: true,
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
    onDel(id){
      if(id != ''){
        this.$confirm('是否确定删除该记录?删除的通知无法挽回。', '确认删除？', {
          distinguishCancelAndClose: true,
          confirmButtonText: '确定',
          cancelButtonText: '放弃'
        })
        .then(()=>{
          this.loading = true;
          requestAjax({
            url: "/Message/Message.php",
            type: 'post',
            data:{
              action: "delMessage",
              campus: this.$store.state.userCampus,
              id: id,
            },
            async: true,
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              console.log(res);
              if(res.data){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
                
                // 日志写入
                let obj = {
                  content: "删除通知记录",
                  type: "删除记录",
                  model: "通知模块",
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
              this.loading = false;
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
  },
  computed:{
    sum(){
      return this.tableData.length;
    },
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
    },
  },
  components: {
    Limit,
  }
}
</script>

<style>
  .table{
    margin-bottom: 24px;
  }
</style>