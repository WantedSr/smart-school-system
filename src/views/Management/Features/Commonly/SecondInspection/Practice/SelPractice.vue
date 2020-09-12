<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>实习处督查</h1>
    </div>

    
    <el-form :inline="true" :model="form" class="demo-form-inline">
      <el-form-item label="">
        <el-select size="small" v-model="form.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
        <el-button type="success" size="small" @click="onAdd">填写</el-button>
      </el-form-item>
    </el-form>
    
    <div class="AddOptionTable box">
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
          prop="addDate"
          label="日期"
          align="center"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ getDate(scope.row.addDate) }}
          </template>
        </el-table-column>
        <el-table-column
          prop="COUNT(department)"
          label="场所数"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="created_user"
          label="填写人"
          align="center"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="230"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.addDate)">编辑</el-button>
            <el-button size="mini" type="warning" @click="onSee(scope.row.addDate)">查看</el-button>
            <el-button size="mini" type="danger" @click="onDel(scope.row.addDate)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>

    <el-dialog
      title="提示"
      :visible.sync="dialogVisible"
      width="80%"
      :before-close="handleClose">
      <div class="list">
        <div class="pagehead">
          <h1>{{ getDate(seeDate) }} 督查情况</h1>
        </div>
          
        <div class="item" v-for="(item,i) in departmentData" :key="'dep-'+i">
          <div class="item-title">
            <h3>{{ item.department_name }}</h3>
          </div>
          <div class="item-table">
            <el-table
              size="mini"
              :data="item.child"
              ref="multipleTable"
              tooltip-effect="dark"
              border
              style="width: 100%">
              <el-table-column
                prop="place_name"
                label="名称"
                align="center"
                min-width="150"
                sortable>
              </el-table-column>
              <el-table-column
                prop="class"
                label="班级"
                align="center"
                min-width="150"
                sortable>
                <template v-slot="scope">
                  <el-select size="small" v-model="scope.row.class" placeholder="选择班级">
                    <el-option v-for="(item2,i2) in (classData.filter(item3=>item3.department === item.department_id))" :key="'c-'+i2" :label="item2.class_name" :value="item2.class"></el-option>
                  </el-select>
                </template>
              </el-table-column>
              <el-table-column
                prop="teacher"
                label="教师"
                align="center"
                min-width="150"
                sortable>
                <template v-slot="scope">
                  <el-select size="small" v-model="scope.row.teacher" placeholder="选择教师">
                    <el-option v-for="(item2,i2) in (teacherData.filter(item3=>item3.department === item.department_id))" :key="'t-'+i2" :label="item2.username" :value="item2.userid"></el-option>
                  </el-select>
                </template>
              </el-table-column>
              <el-table-column
                prop="reason"
                label="扣分说明"
                align="center"
                min-width="150"
                sortable>
                <template v-slot="scope">
                  <el-input type="textarea" :rows="2" placeholder="扣分说明" v-model="scope.row.reason" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="attendance"
                label="学生出勤(10分)"
                align="center"
                min-width="130"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.attendance" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="orderly"
                label="实训秩序(30分)"
                align="center"
                min-width="130"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.orderly" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="attendance"
                label="手机使用(10分)"
                align="center"
                min-width="130"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.phone" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="health"
                label="实训室卫生(20分)"
                align="center"
                min-width="150"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.health" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="toilet"
                label="厕所卫生(10分)"
                align="center"
                min-width="130"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.toilet" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="inspection"
                label="校级督查(20分)"
                align="center"
                min-width="130"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.inspection" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                prop="other"
                label="其它"
                align="center"
                min-width="100"
                sortable>
                <template v-slot="scope">
                  <el-input type="number" placeholder="分数" min="0" max="10" v-model="scope.row.other" size="mini"></el-input>
                </template>
              </el-table-column>
              <el-table-column
                fixed="right"
                prop="totalScore"
                label="总分"
                align="center"
                min-width="100"
                sortable>
                <template v-slot="scope">
                  {{ getScore(scope.row) ? getScore(scope.row) : 0 }}
                </template>
              </el-table-column>
            </el-table>
            <div class="info">
              <ul>
                <li>数量：{{ item.child.length }}</li>
                <li>平均分：{{ TotalScore(item.child) / parseInt(item.child.length) }}</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="more_score">
          <ul>
            <li>部门数量：{{ departmentData.length }}</li>
            <li>实训场所数量：{{ getNum(departmentData) }}</li>
            <li>学生考勤平均分：{{ getAveScore(departmentData,"attendance") }}</li>
            <li>实训秩序平均分：{{ getAveScore(departmentData,"orderly") }}</li>
            <li>手机使用平均分：{{ getAveScore(departmentData,"phone") }}</li>
            <li>实训室卫生平均分：{{ getAveScore(departmentData,"health") }}</li>
            <li>厕所卫生平均分：{{ getAveScore(departmentData,"toilet") }}</li>
            <li>校级督查平均分：{{ getAveScore(departmentData,"inspection") }}</li>
            <li>其它平均分：{{ getAveScore(departmentData,"other") }}</li>
            <li>总分：{{ getTotalScore(departmentData) }}</li>
            <li>总平均分：{{ (getTotalScore(departmentData) / getNum(departmentData)).toFixed(1) }}</li>
          </ul>
        </div>
      </div>
    </el-dialog>
    
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import Limit from "../../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{

      form:{
        semester: this.$store.state.semester,
      },

      tableData:[],
      showTable: [],

      n: this.$route.query.n,
      
      multipleTable: [],

      loading: false,

      semesterData: [],
      departmentData: [],

      page: 1,
      total: 50,

      departmentData: [],
      dialogVisible: false,
      seeDate: "",

    }
  },
  created(){
    this.onSubmit();
    this.getSemester();
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
    },
    sum(){
      return this.tableData.length;
    },
    getScore(){
      return (item)=>{
        let score = 0;
        score += parseInt(item.attendance);
        score += parseInt(item.orderly);
        score += parseInt(item.phone);
        score += parseInt(item.health);
        score += parseInt(item.toilet);
        score += parseInt(item.inspection);
        score += parseInt(item.other);
        return score;
      }
    },
    TotalScore(){
      return (depData)=>{
        let totalScore = 0;
        for(let i in depData){
          let item = depData[i];
          // console.log(item);
          let score = 0;
          score += parseInt(item.attendance);
          score += parseInt(item.orderly);
          score += parseInt(item.phone);
          score += parseInt(item.health);
          score += parseInt(item.toilet);
          score += parseInt(item.inspection);
          score += parseInt(item.other);
          totalScore += score;
        }
        return totalScore;
      }
    },
    getAveScore(){
      return (depData,type)=>{
        let total = 0,
        num = 0
        for(let i in depData){
          let child = depData[i]['child'];
          child.filter(item=>{
            total += parseInt(item[type]);
            num ++;
            return false;
          });
        }
        return (total / num).toFixed(1);
      }
    },
    getNum(){
      return depData=>{
        let num = 0;
        for(let i in depData){
          num += depData[i].child.length;
        }
        return num;
      }
    },
    getTotalScore(){
      return (depData)=>{
        let score = 0,
        num = 0
        for(let i in depData){
          let child = depData[i]['child'];
          child.filter(item=>{
            score += parseInt(item.attendance);
            score += parseInt(item.orderly);
            score += parseInt(item.phone);
            score += parseInt(item.health);
            score += parseInt(item.toilet);
            score += parseInt(item.inspection);
            score += parseInt(item.other);
            return false;
          });
        }
        return score;
      }
    }
  },
  props:{
    classData:{
      type: Array,
      required: true,
      default: [],
    },
    teacherData:{
      type: Array,
      required: true,
      default: [],
    }
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
    onSubmit(){
      this.tableData = [];
      this.loading = true;
      requestAjax({
        url: "/SecondInspection/Practice.php",
        type: "post",
        data:{
          action: "group",
          campus: this.$store.state.userCampus,
          semester: this.form.semester,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          if(res.data.length > 0){

            let data = res.data;

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
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add',
        },
      })
    },
    onUpa(dt){
      this.$router.push({
        query:{
          n: this.n,
          dt: dt,
          type: 'upa',
        },
      })
    },
    onDel(dt){
      this.$confirm('是否确定删除该记录？', '确认删除？', {
        distinguishCancelAndClose: true,
        confirmButtonText: '确定',
        cancelButtonText: '放弃'
      })
      .then(()=>{
        requestAjax({
          url: "/SecondInspection/Practice.php",
          type: 'post',
          data:{
            action: "del",
            date: dt,
            campus: this.$store.state.userCampus,
          },
          async: false,
          success:(res)=>{
            res = JSON.parse(res);
            if(res.data){
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
    onSee(dt){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          col: "department_id,department_name",
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          for(let i in res){
            let depId = res[i]['department_id'];
            requestAjax({
              url: "/SecondInspection/Practice.php",
              type: "post",
              data:{
                action: "get",
                request: JSON.stringify({
                  campus: this.$store.state.userCampus,
                  department: depId,
                  addDate: dt,
                }),
              },
              success:(res2)=>{
                this.loading = false;
                res2 = JSON.parse(res2);
                let data = res2.data;
                res[i]['child'] = data;
              },
              async: false,
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
          this.departmentData = res;
          this.dialogVisible = true;
          this.seeDate = dt;
          // console.log(res);
        },
        error:(err)=>{
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
  .item{
    margin-bottom: 24px;
  }
  .item-title{
    margin-bottom: 12px;
  }
  .item-title h3{
    color: #2c3e50;
    font-weight: 400;
    font-size: 20px;
  }
  .item-table{
    width: 100%;
  }
  .table-item{
    width: 100%;
    height: auto;
    display: flex;
    align-content: center;
    justify-content: center;
    padding: 6px;
    border: 1px solid #ccc;
  }
  .table-item:nth-child(n+2){
    border-top: 1px solid transparent;
  }
  .table-item>div{
    margin: 0 6px;
  }
  .table-item>.item-content{
    padding: 6px 0;
    flex-grow: 8;
  }
  .table-item>.item-title2{
    width: 70px;
    flex-grow: 1;
    line-height: 52px;
  }
  .table-item>.item-title2 h5{
    color: #95a5a6;
    font-weight: 500;
  }
  .table-item>.item-plus , .table-item>.item-minus{
    width: 100px;
    flex-grow: 1;
    padding: 6px 0;
    line-height: 41px;
  }
  .table-item>.score{
    width: 50px;
    flex-grow: 1;
    line-height: 52px;
  }
  .total_score{
    margin: 6px 0 24px;
  }
  .total_score h3{
    font-size: 16px;
    font-weight: 500;
  }
  .more_score ul li{
    font-size: 18px;
    color: #000;
    margin-bottom: 6px;
  }
  .item-table>.info{
    margin: 6px 0 30px;
    font-size: 14px;
  }
</style>