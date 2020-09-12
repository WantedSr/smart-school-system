<template>
  <div class="subpage"
    v-loading="loading"
    element-loading-text="拼命加载中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">
    <div class="add_record">
      <div class="pagehead">
        <h1>{{ getDate(new Date()) }} 填写政教处督查</h1>
      </div>
      
      <div class="list">
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
                  <el-input type="textarea" v-model="item.health" placeholder="输入督查情况" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input type="number" :max="25" :min="0" v-model="item.health_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input type="number" :max="-25" :min="0" v-model="item.health_minus" placeholder="扣分" size="mini"></el-input>
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>纪律(25分)</h5>
                </div>
                <div class="item-content">
                  <el-input type="textarea" v-model="item.discipline" placeholder="输入督查情况" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input type="number" :max="25" :min="0" v-model="item.discipline_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input type="number" :max="-25" :min="0" v-model="item.discipline_minus" placeholder="扣分" size="mini"></el-input>
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>两操(30分)</h5>
                </div>
                <div class="item-content">
                  <el-input type="textarea" v-model="item.exercises" placeholder="输入督查情况" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input type="number" :max="25" :min="0" v-model="item.exercises_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input type="number" :max="-25" :min="0" v-model="item.exercises_minus" placeholder="扣分" size="mini"></el-input>
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>校级督查(20分)</h5>
                </div>
                <div class="item-content">
                  <el-input type="textarea" v-model="item.inspection" placeholder="输入督查情况" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input type="number" :max="25" :min="0" v-model="item.inspection_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input type="number" :max="-25" :min="0" v-model="item.inspection_minus" placeholder="扣分" size="mini"></el-input>
                </div>
              </li>
              <li class="table-item">
                <div class="item-title2">
                  <h5>其它</h5>
                </div>
                <div class="item-content">
                  <el-input type="textarea" v-model="item.other" placeholder="输入督查情况" :row="2" size="mini"></el-input>
                </div>
                <div class="item-plus">
                  <el-input type="number" :max="25" :min="0" v-model="item.other_plus" placeholder="加分" size="mini"></el-input>
                </div>
                <div class="item-minus">
                  <el-input type="number" :max="-25" :min="0" v-model="item.other_minus" placeholder="扣分" size="mini"></el-input>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="submit">
        <el-button type="primary" size="small" @click="onSubmit">提交</el-button>
        <el-button size="small" @click="onBack">返回</el-button>
      </div>

    </div>  
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {

      departmentData: [],

      loading: false,
    }
  },
  created(){
    this.loading = true;
    requestAjax({
        url: "/SecondInspection/Political.php",
        type: "post",
        data:{
          action: "get",
          request: JSON.stringify({
            campus: this.$store.state.userCampus,
            semester: this.$store.state.semester,
            addDate: new Date().setHours(0,0,0,0),
          }),
          col: "COUNT(*)",
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          this.loading = false;
          // console.log(res);
          if(res.data[0]['COUNT(*)'] > 0){
            this.$alert('今日的督查已填写过', '重复填写', {
              confirmButtonText: '确定',
              callback: action => {
                this.$router.go(-1);
              }
            });
          }else{
            this.getDepartment();
            return;
          }
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
    // this.getDepartment();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getDepartment(){
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
            let row = res[i];
            res[i].health = "";
            res[i].health_plus = "";
            res[i].health_minus = "";
            res[i].discipline = "";
            res[i].discipline_plus = "";
            res[i].discipline_minus = "";
            res[i].exercises = "";
            res[i].exercises_plus = "";
            res[i].exercises_minus = "";
            res[i].inspection = "";
            res[i].inspection_plus = "";
            res[i].inspection_minus = "";
            res[i].other = "";
            res[i].other_plus = "";
            res[i].other_minus = "";
            res[i].addDate = new Date().setHours(0,0,0,0);
            res[i].semester = this.$store.state.semester;
            res[i].campus = this.$store.state.userCampus;
            res[i].school = this.$store.state.userSchool;
            res[i].created_user = this.$store.state.userId;
          }
          this.departmentData = res;
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
    },
    onSubmit(){
      this.loading = true;
      let arr = this.departmentData;
      $.each(arr, (i,v)=>{ 
        v.health_plus = v.health_plus == '' ? 0 : v.health_plus;
        v.health_minus = v.health_minus == '' ? 0 : v.health_minus;
        v.discipline_plus = v.discipline_plus == '' ? 0 : v.discipline_plus;
        v.discipline_minus = v.discipline_minus == '' ? 0 : v.discipline_minus;
        v.exercises_plus = v.exercises_plus == '' ? 0 : v.exercises_plus;
        v.exercises_minus = v.exercises_minus == '' ? 0 : v.exercises_minus;
        v.inspection_plus = v.inspection_plus == '' ? 0 : v.inspection_plus;
        v.inspection_minus = v.inspection_minus == '' ? 0 : v.inspection_minus;
        v.other_plus = v.other_plus == '' ? 0 : v.other_plus;
        v.other_minus = v.other_minus == '' ? 0 : v.other_minus;
      });
      // console.log(arr);
      requestAjax({
        url: "/SecondInspection/Political.php",
        type: "post",
        data:{
          action: "add",
          arr: JSON.stringify(arr),
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          let errarr = [];
          for(let i=0;i<res.data.length;i++){
            if(!res.data[i]){
              errarr.push(i);
            }
          }
          this.loading = false;
          if(errarr.length <= 0){
            this.$message({
              message: '政教处督查填写成功',
              type: 'success'
            });

            // 日志写入
            let obj = {
              content: "政教处督查填写 学期：" + this.$store.state.semester + " 日期：" + new Date().getTime(),
              type: "添加记录",
              model: "政教处督查",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);

            this.$router.go(-1);
          }
          else if(errarr.length > 0 && errarr.length < res.data.length){
            this.$message({
              message: '部分数据填写失败，请在PC端控制台 F12->console 处查看',
              type: 'warning'
            });
            for(let i in errarr){
              console.log(arr[i].department_name + " 登记有误！");
            }
            // 日志写入
            let obj = {
              content: "政教处督查填写 学期：" + this.$store.state.semester + " 日期：" + new Date().getTime(),
              type: "添加记录",
              model: "政教处督查",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);
            this.$router.go(-1);
          }
          else{
            this.$message.error('添加失败，请稍后再试！');
          }
          this.loading = false;
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
  },
}
</script>

<style scoped>
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
    width: 80px;
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

</style>