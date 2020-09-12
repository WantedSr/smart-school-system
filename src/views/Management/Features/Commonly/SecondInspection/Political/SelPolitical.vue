<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>政教处督查</h1>
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
          label="专业数"
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
      title="督查情况"
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
            <ul>
              <li class="table-item">
                <div class="item-title2">
                  <h5>卫生(25分)</h5>
                </div>
                <div class="item-content">
                  <el-input :disabled="true" type="textarea" v-model="item.health" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input :disabled="true" type="number" :max="25" :min="0" v-model="item.health_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input :disabled="true" type="number" :max="-25" :min="0" v-model="item.health_minus" placeholder="扣分" size="mini"></el-input>
                </div>
                <div class="score">
                  得分：{{ 25 + parseInt(item.health_plus) + parseInt(item.health_minus) }}
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>纪律(25分)</h5>
                </div>
                <div class="item-content">
                  <el-input :disabled="true" type="textarea" v-model="item.discipline" placeholder="" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input :disabled="true" type="number" :max="25" :min="0" v-model="item.discipline_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input :disabled="true" type="number" :max="-25" :min="0" v-model="item.discipline_minus" placeholder="扣分" size="mini"></el-input>
                </div>
                <div class="score">
                  得分：{{ 25 + parseInt(item.discipline_plus) + parseInt(item.discipline_minus) }}
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>两操(30分)</h5>
                </div>
                <div class="item-content">
                  <el-input :disabled="true" type="textarea" v-model="item.exercises" placeholder="" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input :disabled="true" type="number" :max="25" :min="0" v-model="item.exercises_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input :disabled="true" type="number" :max="-25" :min="0" v-model="item.exercises_minus" placeholder="扣分" size="mini"></el-input>
                </div>
                <div class="score">
                  得分：{{ 30 + parseInt(item.exercises_plus) + parseInt(item.exercises_minus) }}
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>校级督查(20分)</h5>
                </div>
                <div class="item-content">
                  <el-input :disabled="true" type="textarea" v-model="item.inspection" placeholder="" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input :disabled="true" type="number" :max="25" :min="0" v-model="item.inspection_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input :disabled="true" type="number" :max="-25" :min="0" v-model="item.inspection_minus" placeholder="扣分" size="mini"></el-input>
                </div>
                <div class="score">
                  得分：{{ 20 + parseInt(item.inspection_plus) + parseInt(item.inspection_minus) }}
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>其它</h5>
                </div>
                <div class="item-content">
                  <el-input :disabled="true" type="textarea" v-model="item.other" placeholder="" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input :disabled="true" type="number" :max="25" :min="0" v-model="item.other_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input :disabled="true" type="number" :max="-25" :min="0" v-model="item.other_minus" placeholder="扣分" size="mini"></el-input>
                </div>
                <div class="score">
                  得分：{{ parseInt(item.other_plus) + parseInt(item.other_minus) }}
                </div>
              </li>
            </ul>
          </div>
          <div class="total_score">
            <h3>总分：{{ getScore(item) }}</h3>
          </div>
        </div>
        <div class="more_score">
          <ul>
            <li>部门数量：{{ departmentData.length }}</li>
            <li>总分：{{ TotalScore(departmentData) }}</li>
            <li>平均分：{{ TotalScore(departmentData) / departmentData.length }}</li>
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
        let score = 25 + parseInt(item.health_plus) + parseInt(item.health_minus);
        score += 25 + parseInt(item.discipline_plus) + parseInt(item.discipline_minus);
        score += 30 + parseInt(item.exercises_plus) + parseInt(item.exercises_minus);
        score += 20 + parseInt(item.inspection_plus) + parseInt(item.inspection_minus);
        score += parseInt(item.other_plus) + parseInt(item.other_minus);
        return score;
      }
    },
    TotalScore(){
      return (depData)=>{
        let totalScore = 0;
        for(let i in depData){
          let item = depData[i];
          let score = 25 + parseInt(item.health_plus) + parseInt(item.health_minus);
          score += 25 + parseInt(item.discipline_plus) + parseInt(item.discipline_minus);
          score += 30 + parseInt(item.exercises_plus) + parseInt(item.exercises_minus);
          score += 20 + parseInt(item.inspection_plus) + parseInt(item.inspection_minus);
          score += parseInt(item.other_plus) + parseInt(item.other_minus);
          totalScore += score;
        }
        return totalScore;
      }
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
        url: "/SecondInspection/Political.php",
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
          url: "/SecondInspection/Political.php",
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
          url: "/SecondInspection/Political.php",
          type: "post",
          data:{
            action: "get",
            request: JSON.stringify({
              campus: this.$store.state.userCampus,
              semester: this.$store.state.semester,
              addDate: dt,
            }),
          },
          async: false,
          success:(res)=>{
            res = JSON.parse(res);
            // console.log(res);
            this.loading = false;
            this.departmentData = res.data;
            this.dialogVisible = true;
            this.seeDate = dt;
          },
          error:err=>{
            this.loading = false;
            console.error(err);
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
    width: 50px;
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
</style>