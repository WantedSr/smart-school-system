<template>
  <div class="subpage"
    v-loading="loading"
    element-loading-text="拼命加载中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">
    <div class="add_record">
      <div class="pagehead">
        <h1>{{ getDate(this.$route.query.dt) }} 填写实习处督查</h1>
      </div>
      
      <div class="list">
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
            </el-table>
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
      classData: [],
      teacherData: [],

      loading: false,
    }
  },
  created(){
    this.loading = true;
    this.getDepartment();
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
    onBack(){
      this.$router.go(-1);
    },
    getDepartment(){
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
                  addDate: this.$route.query.dt
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
          console.log(res);
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
      console.log(arr);
      requestAjax({
        url: "/SecondInspection/Practice.php",
        type: "post",
        data:{
          action: "up",
          arr: JSON.stringify(arr),
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          let data = res.data;
          let errarr = [];
          for(let i=0;i<data.length;i++){
            for(let r=0;r<data[i]['child'].length;r++){
              if(!data[i]['child'][r]){
                errarr.push({
                  department: data[i]['department_name'],
                  place: arr.filter(item=>item.department_id == res.data[i]['department_id'])[0]['child'][r].place_name,
                });
              }
            }
          }
          if(errarr.length <= 0){
            this.$message({
              message: '实习处督查信息修改成功',
              type: 'success'
            });

            // 日志写入
            let obj = {
              content: "实习处督查修改 学期：" + this.$store.state.semester + " 日期：" + new Date().getTime(),
              type: "修改记录",
              model: "实习处督查",
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
              message: '部分数据修改失败，请在PC端控制台 F12->console 处查看',
              type: 'warning'
            });
            for(let i in errarr){
              console.log(errarr[i].department + " 的 " + errarr[i].place + " 修改有误！");
            }
            // 日志写入
            let obj = {
              content: "实习处督查修改 学期：" + this.$store.state.semester + " 日期：" + new Date().getTime(),
              type: "修改记录",
              model: "实习处督查",
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
            this.$message.error('修改失败，请稍后再试！');
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
    width: 90px;
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