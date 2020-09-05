<template>
  <div class="SelSemester">
    <div class="pagehead">
      <h1>班主任填报</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-form :inline="true" label-position="left" label-width="80px" :model="sel" class="demo-form-inline">
        <el-form-item label="">
          <el-select size="small" v-model="form.class" placeholder="选择班级">
            <el-option v-if="classData.length > 0" label="所有班级" value="all"></el-option>
            <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-select size="small" v-model="form.week" placeholder="选择周次">
            <el-option v-for="i in 20" :key="'week-'+i" :label="'第'+i+'周'" :value="i"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
          <el-button type="success" size="small" @click="onAdd">添加</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="semesterTable box">
      <el-table
        size="small"
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
          prop="class_name"
          label="班级"
          align="center"
          min-width="130"
          sortable>
        </el-table-column>
        <el-table-column
          prop="week"
          label="周次"
          align="center"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="teacher_name"
          label="教师"
          align="center"
          min-width="80"
          sortable>
        </el-table-column>
        <el-table-column
          prop="should_num"
          label="应到人数"
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
          label="状态"
          align="center"
          min-width="100"
          sortable>
          <template v-slot="scope">
            <el-tag size="small" :type=" scope.row.state == 0 ? 'danger' : scope.row.state == '2' ? 'danger' : scope.row.dep_state == '0' ? 'warning' : scope.row.office_state == '0' ? 'warning' : scope.row.leader_state == '0' ? 'warning' : 'success' ">
               {{ scope.row.state == 0 ? '未提报' : scope.row.state == '2' ? '被打回' : scope.row.dep_state == '0' ? '系部审核中' : scope.row.office_state == '0' ? '政教处审核中' : scope.row.leader_state == '0' ? '领导审核中' : '提报完成' }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column
          label="操作"
          align="center"
          min-width="220"
          sortable>
          <template v-slot="scope">
            <el-button v-if="scope.row.state == '0' || scope.row.state == '2'" size="mini" @click="onUpa(scope.row.id)">编辑</el-button>
            <el-button v-if="scope.row.state == '0' || scope.row.state == '2'" size="mini" type="primary" @click="onCheck(scope.row.id)">提报</el-button>
            <el-button v-if="scope.row.state == '1'" size="mini" type="primary" @click="onSee(scope.row.id)">查看</el-button>
            <el-button size="mini" type="danger" @click="onDel(scope.row.id)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div class="see">
      <el-dialog title="收货地址" :visible.sync="dialogFormVisible">
        <el-form ref="form" :model="form2" :rules="rules" label-width="100px">
          <el-form-item prop="class" label="班级">
            <el-input size="small" v-model="form2.class_name" placeholder="班级"></el-input>
          </el-form-item>
          <el-form-item prop="teacher" label="教师">
            <el-input size="small" v-model="form2.teacher_name" placeholder="教师"></el-input>
          </el-form-item>
          <el-form-item prop="should_num" label="应到人数">
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

      form: {
        class: "all",
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

      classData: [],
      teacherData: [],

      dialogFormVisible: false,
      form2: {},
    }
  },
  created(){
    this.getClass();
    this.getTeacher();
    this.form.week = this.$store.state.week;
  },
  props:{
    schoolData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getTeacher(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "*",
          selobj: {
            'department': this.$store.state.userDepartment,
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          this.loading = false;
          // console.log(res);
          res = JSON.parse(res);
          this.teacherData = res;
        },
        error:(err)=>{
          // this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.$store.state.userDepartment != '' && this.$store.state.userDepartment != '0' ? this.$store.state.userDepartment : null,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.$store.state.userDepartment,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
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
      let obj = {
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
      };
      if(this.form.class != 'all'){
        if(this.form.week != 'all'){
          obj['class'] = this.form.class;
          obj['week'] = this.form.week;
        }else{
          obj['class'] = this.form.class
        }
      }else{
        if(this.form.week != 'all'){
          obj['week'] = this.form.week;
        }
      }
      this.getData("*",obj);
      this.getDataSum(obj);
    },
    getData(col="*",selobj=null){
      this.loading = true;
      requestAjax({
        url: "/week/HeadTeacher.php",
        type: 'get',
        data:{
          col: col,
          type: "sel_headTeacher",
          selobj: selobj,
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
        url: "/week/HeadTeacher.php",
        type: 'get',
        data:{
          type: "sel_headTeacher",
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
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add',
        },
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          htid: id,
          type: 'upa',
        },
      })
    },
    onDel(id){
      this.$confirm('是否确定删除该记录？', '确认删除？', {
        distinguishCancelAndClose: true,
        confirmButtonText: '确定',
        cancelButtonText: '放弃'
      })
      .then(()=>{
        requestAjax({
          url: "/week/HeadTeacher.php",
          type: 'post',
          data:{
            type: "del_headTeacher",
            sel: 'id',
            val: id,
          },
          async: true,
          success:(res)=>{
            res = JSON.parse(res);
            if(res){
              this.$message({
                message: '删除成功',
                type: 'success'
              });
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
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onCheck(id){
        this.$confirm('是否要进行填报提交', '操作询问', {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {

          this.loading = true;
          requestAjax({
            url: "/week/HeadTeacher.php",
            type: 'post',
            data:{
              type: "upa_headTeacher",
              obj:{
                state: '1',
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
                  content: "班主任填报提交 "+ id,
                  type: "修改记录",
                  model: "大周模块-班主任填报模块",
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
    onSee(id){
      this.loading = true;
      requestAjax({
        url: "/week/HeadTeacher.php",
        type: 'get',
        data:{
          col: "*",
          type: "sel_headTeacher",
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
  components:{
    Limit,
  }
}
</script>

<style>

</style>