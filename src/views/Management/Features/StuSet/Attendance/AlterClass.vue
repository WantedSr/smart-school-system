<template>
  <div class="subpage"
    v-loading="loading"
    element-loading-text="拼命加载中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">

    <div class="pagehead">
      <h1>教室卫生登记</h1>
    </div>


    
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item>
        <el-select @change="getHealth" size="small" v-model="sel.type" placeholder="隶属模块">
          <el-option v-for="(item,i) in modelData" :key="'model-'+i" :label="item.health_name" :value="item.health_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="getClass" size="small" v-model="sel.department" placeholder="查询部门">
          <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" mn>
        <el-date-picker
          size="small"
          @change="getHealth"
          v-model="sel.time"
          type="date"
          format="yyyy-MM-dd"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
    </el-form>



    <div class="altersel">
      <el-table
        :data="classData"
        stripe
        border
        size="small"
        style="width: 100%">
        <el-table-column type="index" label="序号">
        </el-table-column>
        <el-table-column
          prop="class_name"
          sortable
          label="班级">
        </el-table-column>
        <el-table-column
          prop="score"
          sortable
          :sort-method="toAsc"
          label="分数">
          <template v-slot="scope">
            {{ healthData.filter(item=>item.class == scope.row.class).length > 0 ? healthData.filter(item=>item.class == scope.row.class)[0]['score'] : 0 }}
          </template>
        </el-table-column>
        <el-table-column
          prop="state"
          sortable
          label="状态">
          <template v-slot="scope">
            <el-tag size="small" :type=" healthData.filter(item => item.class == scope.row.class).length > 0 ? 'success' : 'danger'">{{healthData.filter(item => item.class == scope.row.class).length > 0 ? '已提交' : '未提交'}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作">
          <template v-slot="scope">
            <el-link v-if="healthData.filter(item => item.class == scope.row.class).length > 0" :underline="false" @click="see(scope.row.class)" type="primary">查看</el-link>
            <el-link v-else :underline="false" @click="write(scope.row.class)" type="primary">打分</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div class="write">
      <el-dialog
        title="打分"
        width="80%"
        :visible.sync="dialogVisible1">
        
        <div class="patrolhead">
          当前分数-<el-tag size="small">{{score}}</el-tag>
        </div>

        <el-table
          :data="optionData"
          size="small"
          border
          ref="multipleTable"
          tooltip-effect="dark"
          @selection-change="handleSelectionChange"
          v-loading="loading"
          element-loading-text="拼命加载中"
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(0, 0, 0, 0.8)"
          style="width: 100%">
          <el-table-column
            prop="option_name"
            label="评分项"
            width="130">
          </el-table-column>
          <el-table-column
            prop="score"
            label="打分" 
            width="60">
            <template v-slot="scope">
              <el-tag size="mini" type="success">{{scope.row.score == '' || scope.row.score == undefined ? 0 : scope.row.score}}</el-tag>
            </template>
          </el-table-column>
          <el-table-column
            prop="select"
            min-width="570"
            label="选项">
            <template v-slot="scope">
              <el-button style="margin-bottom: 6px" size="mini" @click="setScore(scope.$index,item.score)" v-for="(item,i) in scope.row.children" :key="scope.$index + '-chid-'+i" type="primary" >{{i+1}}.&nbsp;{{item.name}}[{{item.score}}分]</el-button>
            </template>
          </el-table-column>
        </el-table>

        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible1 = false">取 消</el-button>
          <el-button type="primary" @click="onSubmit">确 定</el-button>
        </div>
      </el-dialog>
    </div>

    <div class="see">
      <el-dialog
        title="查看"
        width="80%"
        :visible.sync="dialogVisible2">
        
        <div class="patrolhead">
          班级-<el-tag size="small">{{ classData.filter(item=>item.class == cls).length > 0 ? classData.filter(item=>item.class == cls)[0]['class_name'] : '' }}</el-tag>
        </div>

        <el-table
          :data="optionData2"
          size="small"
          border
          ref="multipleTable"
          tooltip-effect="dark"
          @selection-change="handleSelectionChange"
          v-loading="loading"
          element-loading-text="拼命加载中"
          element-loading-spinner="el-icon-loading"
          element-loading-background="rgba(0, 0, 0, 0.8)"
          style="width: 100%">
          <el-table-column
            prop="option_name"
            label="评分项"
            width="130">
            <template v-slot="scope">
              {{ optionData.filter(item=>item.option_id == scope.row.option_id).length > 0 ? optionData.filter(item=>item.option_id == scope.row.option_id)[0]['option_name'] : "" }}
            </template>
          </el-table-column>
          <el-table-column
            prop="score"
            label="打分">
          </el-table-column>
        </el-table>

        <div slot="footer" class="dialog-footer">
          <el-button @click="dialogVisible2 = false">取 消</el-button>
          <el-button type="primary" @click="dialogVisible2 = false">确 定</el-button>
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
      sel:{
        department: this.$store.state.userDepartment,
        time: new Date().setHours(0,0,0,0),
        type: "",
      },

      tableData:[],
      classData: [],
      
      loading: false,
      cls: '',


      dialogVisible1: false,
      dialogVisible2: false,

      optionData: [],
      healthData: [],
      departmentData: [],

      optionData2: [],
      modelData: [],

    }
  },
  methods:{
    onSubmit(){
      this.$confirm('是否确认提交今日课室卫生登记？', '操作询问 ', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        this.loading = true;
        let arr1 = [];
        for(let i in this.optionData){
          let obj = {
            // option_id: this.optionData[i],
            option_id: this.optionData[i]['option_id'],
            score: this.optionData[i]['score'],
          }
          arr1.push(obj);
        }
        let arr = [
          this.cls,
          this.sel.time,
          this.$store.state.semester,
          this.score,
          JSON.stringify(arr1),
          this.sel.department,
          this.$store.state.userSchool,
          this.$store.state.userCampus,
          this.$store.state.userId,
          new Date().getTime(),
        ];

        requestAjax({
          type: 'post',
          url: "/StuSet/AlterHealth.php",
          data:{
            type: 'add_health',
            arr: arr,
            semester: this.$store.state.semester,
            campus: this.$store.state.userCampus,
            class: this.cls,
            date: this.sel.time,
            model: this.sel.type,
          },
          success:(res)=>{
            res = JSON.parse(res);
            if(res[0]){
              this.$message.error('已存在该记录，无法重复登记！');
            }else if(res[1]){
              this.$message({
                message: '课室卫生记录登记成功！',
                type: 'success'
              });
              
              // 日志写入
              let obj = {
                content: "课室卫生登记 班级："+ this.cls + " 时间：" + this.sel.time,
                type: "添加记录",
                model: "课室卫生登记模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }
            this.getHealth();
            this.loading = false;
            this.dialogVisible1 = false;
          },
          error:(err)=>{
            this.dialogVisible1 = false;
            this.loading = false;
            console.log(err);
            this.$notify.error({
              title: '错误',
              message: '服务器有误！,请稍后再试！'
            });
          }
        });
        this.dialogVisible1 = false;
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消操作'
        });
      });
    },
    toAsc(a,b){
      let val1 = a.deadline
      let val2 = b.deadline
      return val2 - val1
    },
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
          // console.log(classData)
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
    getOption(){
      this.loading = true;
      requestAjax({
        type:  "get",
        url: "/StuSet/AlterHealth.php",
        data:{
          col: "option_id,option_name",
          type: "sel_option",
          model: this.sel.type,
          semester: this.$store.state.semester,
          campus: this.$store.state.userCampus,
          date: this.sel.time,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.loading = false;
          for(let i in res){
            res[i]['children'] = res[i]['children'].sort((a,b)=> b.score - a.score);
            let score = res[i]['children'][0] ? res[i]['children'][0]['score'] : 0;
            res[i]['score'] = score
            this.score += parseInt(score);
          }
          this.optionData = res;
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
      })
    },
    getHealth(){
      this.loading = true;
      requestAjax({
        type:  "get",
        url: "/StuSet/AlterHealth.php",
        data:{
          type: "sel_health",
          model: this.sel.type,
          semester: this.$store.state.semester,
          campus: this.$store.state.userCampus,
          date: this.sel.time,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.loading = false;
          this.healthData = res;
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
      });
      this.getClass();
    },
    write(cls){
      this.cls = cls;
      this.dialogVisible1 = true;
      this.getOption();
    },
    see(cls,index){
      this.cls = cls;
      this.dialogVisible2 = true;
      this.getOption();
      this.optionData2 = JSON.parse(this.healthData.filter(item=>item.class == cls)[0]['content']);
    },
    setScore(index,score){
      this.optionData[index]['score'] = score;
    },
    getModel(selobj=null){
      requestAjax({
        url: "/StuSet/HealthModel.php",
        type: 'get',
        data:{
          type: "sel_model",
          col: "*",
          selobj: selobj
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.modelData = res;
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
    getDepartment(){
      this.loading = true;
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
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
  },
  created(){
    // this.getClass();
    this.getModel();
    // this.getHealth();
    this.getDepartment();
  },
  computed:{
    getDate(){
      return (time)=>{
        let date = new Date();
        date.setTime(time);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        let d = date.getDate();
        return y + "年" + m + '月' + d + "日";
      }
    },
    score(){
      let score = 0;
      for(let i in this.optionData){
        score += parseInt(this.optionData[i]['score']);
      }
      return score;
    }
  },
}
</script>

<style>
  .altersel{
    margin-bottom: 60px;
  }
  .do{
    margin-bottom: 20px;
  }
  .pagination{
    text-align: center;
  }
  .pagination .el-pagination{
    display: inline-block;
  }
  .patrolhead{
    margin-bottom: 36px;
  }
  .patrolhead .el-tag{
    position: relative;;
  }
  .patrolhead>h3{
    color: #666;
    font-weight: 500;
  }
  .patrolhead>h3>span{
    margin: 0 6px;
    position: relative;
    top: -3px;
  }
  .patrol .el-table{
    margin-bottom: 36px; 
  }
</style>