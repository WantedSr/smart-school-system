<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>系部审核</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-form :inline="true" label-position="left" label-width="80px" :model="form" class="demo-form-inline">
        <el-form-item label="">
          <el-select @change="clearTable" size="small" v-model="form.type" placeholder="选择提报类型">
            <el-option label="班主任提报" value="headteacher"></el-option>
            <el-option label="宿舍提报" value="dormitory"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select size="small" v-model="form.week" placeholder="选择周次">
            <el-option v-for="i in 20" :key="'week-'+i" :label="'第'+i+'周'" :value="i"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
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
        size="small"
        :data="tableData"
        ref="multipleTable"
        show-summary
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
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          :prop=" form.type == 'headteacher' ? 'teacher_name' : 'dormitory_name'"
          :label=" form.type == 'headteacher' ? '教师' : '宿管' "
          align="center"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="should_num"
          :label=" form.type == 'headteacher' ? '应到人数' : '宿舍人数' "
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="real_num"
          label="实到人数"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="thing_num"
          label="请假人数"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="noback_num"
          label="请假人数"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="practice_num"
          label="实习人数"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="leave_num"
          label="退学人数"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          label="状态"
          align="center"
          min-width="90"
          sortable>
          <template v-slot="scope">
            <el-tag size="small" :type="scope.row.dep_state == '1' ? 'success' : scope.row.dep_state == '2' ? 'danger' : 'warning'">
              {{ scope.row.dep_state == '0' ? 
                "审核中" : scope.row.dep_state == '2' ? 
                  '被打回' : scope.row.office_state == '0' || scope.row.office_state == '2' ? 
                    '政教处审核中' : scope.row.leader_state == '0' ? 
                      '领导审核中' : "审核完成" }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
          align="center"
          min-width="220"
          sortable>
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onSee(scope.row.id)">查看</el-button>
            <el-button size="mini" type="success" @click="onCheck(scope.row.id)">提报</el-button>
            <el-button size="mini" type="danger" @click="onBack(scope.row.id)">打回</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div class="see">
      <el-dialog title="收货地址" :visible.sync="dialogFormVisible">
        <el-form ref="form" :model="form2" :rules="rules" label-width="100px">
          <el-form-item :label=" form.type == 'headteacher' ? '班级' : '宿舍' ">
            <el-input size="small" v-text="form.type == 'headteacher' ? form2.class_name : form2.dormroom_name" placeholder="位置"></el-input>
          </el-form-item>
          <el-form-item :label=" form.type == 'headteacher' ? '教师' : '宿管' ">
            <el-input size="small" v-text="form.type == 'headteacher' ? form2.teacher_name : form2.dormitory_name" placeholder="教师"></el-input>
          </el-form-item>
          <el-form-item prop="should_num" :label=" form.type == 'headteacher' ? '应到人数' : '宿舍人数' ">
            <el-input type="number" size="small" v-model="form2.should_num" placeholder="应到人数"></el-input>
          </el-form-item>
          <el-form-item prop="real_num" label="实到人数">
            <el-input type="number" size="small" v-model="form2.real_num" placeholder="实到人数"></el-input>
          </el-form-item>
          <el-form-item prop="thing_num" label="请假人数">
            <el-input type="number" size="small" v-model="form2.thing_num" placeholder="请假人数"></el-input>
          </el-form-item>
          <el-form-item prop="noback_num" label="未返校人数">
            <el-input type="number" size="small" v-model="form2.noback_num" placeholder="未返校人数"></el-input>
          </el-form-item>
          <el-form-item prop="practice_num" label="实习人数">
            <el-input type="number" size="small" v-model="form2.practice_num" placeholder="实习人数"></el-input>
          </el-form-item>
          <el-form-item prop="leave_num" label="退学人数">
            <el-input type="number" size="small" v-model="form2.leave_num" placeholder="退学人数"></el-input>
          </el-form-item>
          <el-form-item label="备注">
            <el-input type="textarea" size="small" v-model="form2.remark" placeholder="备注"></el-input>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogFormVisible = false">取 消</el-button>
          <el-button type="primary" @click="dialogFormVisible = false">确 定</el-button>
        </div>
      </el-dialog>
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

      dormroomData: [],
      buildData: [],
      teacherData: [],

      dialogFormVisible: false,
      form2: {},

      classNum: 0,
      dormroomNum: 0,
    }
  },
  created(){
    this.form.week = this.$store.state.week;
  },
  methods:{
    clearTable(){
      this.tableData = [];
      if(this.form.type == 'headteacher'){
        this.getClass();
      }else if(this.form.type == 'dormitory'){
        this.getDormroom();
      }
    },
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          col: "COUNT(*)",
          department: this.$store.state.userDepartment,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.$store.state.userDepartment,
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
    getDormroom(){
      requestAjax({
        type: "get",
        url: "/Dormitory/dormroom.php",
        data:{
          type: 'sel_more_dormroom',
          col: "COUNT(*)",
          department: this.$store.state.userDepartment,
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
    onSubmit(){
      let obj = {
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        week: this.form.week,
        state: '1',
      };
      // console.log(obj);
      this.getData("*",obj);
      this.getDataSum(obj);
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
    onCheck(id){
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
            type: this.form.type == 'headteacher' ? 'upa_headTeacher' : "upa_dormitory",
            obj:{
              state: '1',
              dep_state: '1',
            },
            sel: 'id',
            val: id,
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
                content: "系部审核填报 部门：" + this.$store.state.userDepartment + " 周次：" + this.form.week + " 类型："+ this.form.type + " 操作人：" + this.$store.state.userId,
                type: "修改记录",
                model: "大周模块-系部审核模块",
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
            type: this.form.type == 'headteacher' ? 'upa_headTeacher' : "upa_dormitory",
            obj:{
              state: '2',
              dep_state: '0',
            },
            sel: 'id',
            val: id,
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
                content: (this.form.type == 'headteacher' ? "班主任填报打回" : "宿舍填报打回") +" id:" + id  + " 操作人：" + this.$store.state.userId,
                type: "修改记录",
                model: "大周模块-系部审核模块",
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
    onSee(id){
      this.loading = true;
      requestAjax({
        url: this.form.type == 'headteacher' ? "/week/headTeacher.php" : "/week/Dormitory.php",
        type: 'get',
        data:{
          col: "*",
          type: this.form.type == 'headteacher' ? 'sel_headTeacher' : "sel_dormitory",
          selobj: {
            id: id,
            campus: this.$store.state.userCampus,
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          this.form2 = res;
          this.dialogFormVisible = true;
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
    }
  },
}
</script>

<style>

</style>