<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>{{department}}&nbsp;&nbsp;&nbsp;{{getDate()}}&nbsp;&nbsp;&nbsp;巡堂登记</h1>
    </div>

    <el-form :inline="true" :model="form" class="demo-form-inline">
      <el-form-item label="">
        <el-select v-model="form.session" size="small" placeholder="选择课时">
          <el-option v-for="(item,i) in sessionData" :key="'session-'+i" :label="item.schedule_name" :value="item.schedule_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="form.class" placeholder="查询班级">
          <el-option v-for="(item,i) in classData" :key="'c-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="patrol box" v-if="show">
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
            <el-tag size="mini" type="success">{{scope.row.score}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="select"
          min-width="570"
          label="选项">
          <template slot-scope="scope">
            <el-button style="margin-bottom: 6px" size="mini" @click="setScore(scope.$index,item.score)" v-for="(item,i) in scope.row.children" :key="scope.$index + '-chid-'+i" type="primary" >{{i+1}}.&nbsp;{{item.name}}[{{item.score}}分]</el-button>
          </template>
        </el-table-column>
      </el-table>
      <el-button size="small" @click="onAdd" type="success">提交</el-button>
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
        session: "",
        class: "",
      },
      department: "",
      sessionData: [],
      classData: [],
      show: false,
      loading: false,
      optionData: [],
    }
  },
  created(){
    this.getDepartment();
    this.getSession();
    this.getClass();
  },
  computed:{
    getDate(){
      return (s=new Date().getTime())=>{
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
    score(){
      let score = 0;
      for(let i in this.optionData){
        score += parseInt(this.optionData[i]['score']);
      }
      return score;
    }
  },
  methods:{
    getDepartment(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'department_id',
          val: this.$store.state.userDepartment,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0]['department_name'];
          // console.log(res);
          this.department = res;
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
    getSession(){
      this.loading = true;
      requestAjax({
        url: "/base/schedule.php",
        type: 'get',
        data:{
          type: "sel_schedule",
          selobj: {
            'campus':this.$store.state.userCampus,
            'schedule_type': "teach",
            'status': '1',
          },
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          res.sort((a,b)=> a.schedule_order - b.schedule_order);
          this.sessionData = res;
          // console.log(res);
          this.loading = false;
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
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.$store.state.department,
          selobj: {
            'semester': this.$store.state.semester,
            'department': this.$store.state.department,
            'status': "1",
          },
        },
        async: false,
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
      });
    },
    onSubmit(){
      if(this.form.session == '' || this.form.class == ''){
        this.$message({
          message: '请选择完所有选项！',
          type: 'warning'
        });
        return false;
      }else{
        this.show = true;
        this.loading = true;
        requestAjax({
          type:"get",
          url: "/teach/Option.php",
          data:{
            type: "get_option_list",
            col:  "id,option_id,option_name",
            campus: this.$store.state.userCampus,
          },
          success:(res)=>{
            this.loading = false;
            res = JSON.parse(res);
            for(let i in res){
              res[i]['children'] = res[i]['children'].sort((a,b)=> b.score - a.score);
              let score = res[i]['children'][0] ? res[i]['children'][0]['score'] : 0;
              res[i]['score'] = score
              this.score += parseInt(score);
            }
            this.optionData = res;
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
    setScore(index,score){
      this.optionData[index]['score'] = score;
    },
    onAdd(){
      this.$confirm('是否确认提交今日巡堂情况', '操作询问 ', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
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
          this.form.class,
          this.form.session,
          this.score,
          JSON.stringify(arr1),
          this.$store.state.semester,
          this.$store.state.userDepartment,
          this.$store.state.userCampus,
          this.$store.state.userSchool,
          new Date().setHours(0,0,0,0),
          this.$store.state.userId,
          new Date().getTime(),
        ];
        requestAjax({
          type: 'post',
          url: "/teach/RegisterPatrol.php",
          data:{
            type: 'add_patrol',
            arr: arr,
            session: this.form.session,
            class: this.form.class,
            date: new Date().setHours(0,0,0,0),
          },
          success:(res)=>{
            res = JSON.parse(res);
            if(res[0]){
              this.$message.error('已存在该记录，无法重复登记！');
            }else if(res[1]){
              this.$message({
                message: '记录登记成功！',
                type: 'success'
              });
              
              // 日志写入
              let obj = {
                content: "巡堂课程登记 班级："+ this.form.class + ' 节次：' + this.form.session + " 时间：" + new Date().setHours(0,0,0,0),
                type: "添加记录",
                model: "巡堂查课模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

              this.form.class = this.form.session = '';
              this.show = false;

            }
          }
        })
      }).catch(() => {
        this.$message({
          type: 'info',
          message: '已取消删除'
        });          
      });
    }
  }
}
</script>

<style>
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