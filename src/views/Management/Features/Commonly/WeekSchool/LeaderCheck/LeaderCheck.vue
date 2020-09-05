<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>领导审核</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-form :inline="true" label-position="left" label-width="80px" :model="form" class="demo-form-inline">
        <el-form-item label="">
          <el-select @change="onSubmit" size="small" v-model="form.department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'2-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="clearTable" size="small" v-model="form.type" placeholder="选择提报类型">
            <el-option label="班主任提报" value="headteacher"></el-option>
            <el-option label="宿舍提报" value="dormitory"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select @change="onSubmit" size="small" v-model="form.week" placeholder="选择周次">
            <el-option v-for="i in 20" :key="'week-'+i" :label="'第'+i+'周'" :value="i"></el-option>
          </el-select>
        </el-form-item>
      </el-form>
    </div>
    <div class="semesterTable box">
      <div>
        <p v-if="form.type == 'headteacher'" style="color:#999;font-size:14px">共&nbsp;{{ classNum }}&nbsp;个班级，共提交了&nbsp;{{ tableData.length }}&nbsp;条数据</p>
        <p v-else-if="form.type == 'dormitory'" style="color:#999;font-size:14px">共&nbsp;{{ dormroomNum }}&nbsp;个宿舍，共提交了&nbsp;{{ tableData.length }}&nbsp;条数据</p>
        <br>
      </div>
      <el-table
        size="mini"
        show-summary
        :summary-method="getSummaries"
        :data="tableData"
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
          :prop=" form.type == 'headteacher' ? 'class_name' : 'dormroom_name'"
          :label=" form.type == 'headteacher' ? '班级' : '宿舍' "
          align="center"
          min-width="90"
          sortable>
        </el-table-column>
        <el-table-column
          :prop=" form.type == 'headteacher' ? 'teacher_name' : 'dormitory_name'"
          :label=" form.type == 'headteacher' ? '教师' : '宿管' "
          align="center"
          min-width="70">
        </el-table-column>
        <el-table-column
          prop="should_num"
          :label=" form.type == 'headteacher' ? '应到人数' : '宿舍人数' "
          align="center"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          prop="real_num"
          label="实到人数"
          align="center"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          prop="thing_num"
          label="请假人数"
          align="center"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          prop="noback_num"
          label="请假人数"
          align="center"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          prop="practice_num"
          label="实习人数"
          align="center"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          prop="leave_num"
          label="退学人数"
          align="center"
          min-width="95"
          sortable>
        </el-table-column>
        <el-table-column
          label="状态"
          align="center"
          min-width="90"
          sortable>
          <template v-slot="scope">
            <el-tag size="small" :type="scope.row.leader_state == '1' ? 'success' : 'danger'">{{ scope.row.leader_state == '1' ? "审核完成" : '审核中' }}</el-tag>
          </template>
        </el-table-column>
      </el-table>
      <div v-if="tableData.length > 0">
        <br>
        <el-button size="mini" type="success" @click="onCheck()">提报</el-button>
        <el-button size="mini" type="danger" @click="onBack()">打回</el-button>
      </div>
    </div>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{

      form: {
        department: "",
        type: 'headteacher',
        week: "",
      },

      tableData: [],
      n: this.$route.query.n,
      multipleTable: [],
      school: "all",
      search: "",
      sum: 0,
      page: 1,
      total: 50,
      loading: false,

      departmentData: [],
      classNum: 0,
      dormroomNum: 0,

      dialogFormVisible: false,
      form2: {},
    }
  },
  created(){
    this.form.department = '';
    this.getDepartment();
    this.form.week = this.$store.state.week;
  },
  
  methods:{
    clearTable(){
      this.tableData = [];
      this.onSubmit();
    },
    onSubmit(){
      if(this.form.type == 'headteacher'){
        this.getClass();
      }else if(this.form.type == 'dormitory'){
        this.getDormroom();
      }
      let obj = {
        semester: this.$store.state.semester,
        department: this.form.department,
        campus: this.$store.state.userCampus,
        week: this.form.week,
        state: '1',
        dep_state: '1',
        office_state: "1",
      };
      // console.log(obj);
      this.getData("*",obj);
      this.getDataSum(obj);
    },
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          col: "COUNT(*)",
          department: this.form.department,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.form.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0]['COUNT(*)'];
          this.classNum = parseInt(res);
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
    getDormroom(build){
      requestAjax({
        type: "get",
        url: "/Dormitory/dormroom.php",
        data:{
          type: 'sel_more_dormroom',
          col: "COUNT(*)",
          department: this.form.department,
          campus: this.$store.state.userCampus,
          buildtype: '学生宿舍',
        },
        success:(res)=>{
          res = JSON.parse(res);
          let sum = 0;
          for(let i in res){
            sum += parseInt(res[i]['COUNT(*)']);
          }
          this.dormroomNum = sum;
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
    getData(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        url: this.form.type == 'headteacher' ? "/week/headTeacher.php" : "/week/Dormitory.php",
        type: 'get',
        data:{
          col: col,
          type: this.form.type == 'headteacher' ? 'sel_headTeacher' : "sel_dormitory",
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
    getDataSum(selobj=null){
      requestAjax({
        url: this.form.type == 'headteacher' ? "/week/headTeacher.php" : "/week/Dormitory.php",
        type: 'get',
        data:{
          type: this.form.type == 'headteacher' ? 'sel_headTeacher' : "sel_dormitory",
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
    onCheck(){
      this.$confirm('是否要进行填报提交', '操作询问', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {

        this.loading = true;
        requestAjax({
          url: this.form.type == 'headteacher' ? "/week/headTeacher.php" : "/week/Dormitory.php",
          type: 'post',
          data:{
            type: "leader_check",
            week: this.form.week,
            department: this.form.department,
            campus: this.$store.state.userCampus,
            semester: this.$store.state.semester,
          },
          async: true,
          success:(res)=>{
            this.loading = false;
            res = JSON.parse(res);
            if(res){
              this.$message({
                message: '提报成功！',
                type: 'success'
              });
            
              // 日志写入
              let obj = {
                content: "领导审核 部门：" + this.form.department + " 周次：" + this.form.week + " 类型：" + this.form.type  + " 操作人：" + this.$store.state.userId,
                type: "修改记录",
                model: "大周模块-领导审核模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

              this.onSubmit();

            }
            else{
              this.$message.error('提报失败，请稍后再试！');
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

      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        });          
      });
    },
    onBack(id){
      this.$confirm('是否要进行填报打回', '操作询问', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {

        this.loading = true;
        requestAjax({
          url: this.form.type == 'headteacher' ? "/week/headTeacher.php" : "/week/Dormitory.php",
          type: 'post',
          data:{
            type: 'leader_back',
            week: this.form.week,
            department: this.form.department,
            campus: this.$store.state.userCampus,
            semester: this.$store.state.semester,
          },
          async: false,
          success:(res)=>{
            this.loading = false;
            res = JSON.parse(res);
            if(res){
              this.$message({
                message: '打回成功！',
                type: 'success'
              });
            
              // 日志写入
              let obj = {
                content: "政教处审核 部门：" + this.form.department + " 周次：" + this.form.week + " 类型：" + this.form.type  + " 操作人：" + this.$store.state.userId,
                type: "修改记录",
                model: "大周模块-领导审核模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

              this.onSubmit();

            }
            else{
              this.$message.error('打回失败，请稍后再试！');
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
        });

      }).catch(() => {
        this.loading = false;
        this.$message({
          type: 'info',
          message: '已取消删除'
        });          
      });
    },
    getDepartment(){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.departmentData = res;
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
    getSummaries(param) {
      const { columns, data } = param;
      const sums = [];
      columns.forEach((column, index) => {
        if (index === 0) {
          sums[index] = '合计';
          return;
        }
        const values = data.map(item => Number(item[column.property]));
        if (!values.every(value => isNaN(value))) {
          sums[index] = values.reduce((prev, curr) => {
            const value = Number(curr);
            if (!isNaN(value)) {
              return prev + curr;
            } else {
              return prev;
            }
          }, 0);
          sums[index] += "人";
        } else {
          sums[index] = '';
        }
      });

      return sums;
    }
  },
}
</script>

<style>

</style>