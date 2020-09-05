<template>
  <div class="subpage" v-loading="loading">
    <div class="sellast">
      <div class="pagehead">
        <h1>教师开课 {{classInfo.class_name}}</h1>
      </div>
      <div class="teacherschedule box">
        <el-table
          :data="tableData"
          size="small"
          style="width: 100%;margin-bottom: 24px;">
          <el-table-column
            prop="date"
            label="课程"
            width="180">
            <template v-slot="scope">
              <el-select size="small" v-model="scope.row.course" placeholder="课程">
                <el-option v-for="(item,i) in classCourse" :key="'c-'+i+'-'+scope.row.id" :label="item.course_name" :value="item.course"></el-option>
              </el-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="name"
            label="授课老师"
            width="180">
            <template v-slot="scope">
              <el-select size="small" v-model="scope.row.teacher" placeholder="教师">
                <el-option v-for="(item,i) in teacherData" :key="'t-'+i+'-'+scope.row.id" :label="item.username" :value="item.userid"></el-option>
              </el-select>
            </template>
          </el-table-column>
          <el-table-column
            prop="address"
            label="每周课时">
            <template v-slot="scope">
              <el-input type="number" size="small" v-model="scope.row.class_time"></el-input>
            </template>
          </el-table-column>
          <el-table-column
            prop="address"
            label="学期学分">
            <template v-slot="scope">
              <el-input type="number" size="small" v-model="scope.row.credit"></el-input>
            </template>
          </el-table-column>
          <el-table-column
            prop="address"
            label="操作">
            <template v-slot="scope">
              <el-button size="small" @click="onDel(scope.row.id ? scope.row.id : null , scope.$index)" type="danger">删除</el-button>
            </template>
          </el-table-column>
        </el-table>
        <el-button size="small" type="primary" @click="onAdd">添加</el-button>
      </div>
      <div>
        <el-button size="small" @click="onBack">取消</el-button>
        <el-button size="small" @click="onSubmit" type="success">提交</el-button>
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
      class: this.$route.query.class,
      semester: this.$route.query.sem,
      tableData: [{
        date: '2016-05-02',
        name: '王小虎',
        address: '上海市普陀区金沙江路 1518 弄'
        }],
      tbFrom:[
        {},
      ],
      classInfo: {},
      classCourse: [],
      teacherDate: [],
      loading: false,
    }
  },
  created(){
    this.getClassInfo();
    this.getCourse();
    this.getTeacher();
    this.getTeaSchedule();
  },
  methods:{
    getClassInfo(){
      this.loading = true;
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
          selobj: {
            "class_id":this.class,
          },
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          // console.log(res);
          this.classInfo = res;
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
    getCourse(){
      this.loading = true;
      requestAjax({
        url: "/teach/ClassCourse.php",
        type: 'get',
        data:{
          type: "sel_course_name",
          semester: this.semester,
          class: this.class,
          skill: this.classInfo['class_skill'],
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classCourse = res;
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
    getTeacher(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        async: false,
        data:{
          type: "sel_tea",
          col: "id,userid,username",
          selobj: {
            'department':this.classInfo['class_department'],
            'type': '1',
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.teacherData = res;
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
    getTeaSchedule(){
      this.loading = true;
      requestAjax({
        url: "/teach/TeaSchedule.php",
        type: 'get',
        data:{
          type: "sel_course",
          semester: this.semester,
          class: this.class,
          skill: this.classInfo['class_skill'],
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
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
    onBack(){
      this.$router.go(-1);
    },
    onAdd(){
      this.tableData.push({
        teacher: "",
        course: "", 
        semester: this.semester,
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        department: this.classInfo['class_department'],
        class: this.class,
        class_time: "",
        credit: "",
        created_user: this.$store.state.userId,
      });
    },
    onDel(id,index){
      if(id == '' || id == null){
        this.tableData.splice(index,1);
      }else{
        this.$confirm('此操作将永久删除该记录, 是否继续?', '删除操作', {
          confirmButtonText: '继续',
          cancelButtonText: '取消',
          type: 'warning'
        }).then(() => {
          this.loading = true;
          requestAjax({
            type: 'post',
            url: "/teach/TeaSchedule.php",
            data:{
              'type':"del_course",
              'id':id,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '删除成功！',
                  type: 'success'
                });
                
                // 日志写入
                let obj = {
                  content: "教师排课删除 学期:" + this.semester + " 班级: " + this.class,
                  type: "删除记录",
                  model: "教师排课模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.tableData.splice(index,1);
              }
            },
            error:(err)=>{
              this.loading = false;
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            },
          })
        }).catch(() => {
          this.$message({
            type: 'info',
            message: '取消操作'
          });          
        });
      }
    },
    onSubmit(){
      this.loading = true;
      requestAjax({
        url: "/teach/TeaSchedule.php",
        type: 'post',
        data:{
          type: "set_course",
          arr: this.tableData,
        },
        async: true,
        success:(res)=>{

          this.loading = false;
          res = JSON.parse(res);
          $.each(res, (i, v)=>{ 
            if(v){
              return;
            }else{
              this.$message.error(this.tableData[i]['course']+' ' + this.tableData[i]['teacher'] + '设置错误!');
            }
          });
          
          this.$message({
            message: '恭喜你，教师排课设置成功！',
            type: 'success'
          });

          
          // 日志写入
          let obj = {
            content: "教师排课设置 学期:" + this.semester + " 班级: " + this.class,
            type: "修改记录",
            model: "教师排课模块",
            ip: sessionStorage.getItem('ip'),
            user: this.$store.state.userId,
            area: sessionStorage.getItem("area"),
            brower: sysTool.GetCurrentBrowser(),
            addTime: new Date().getTime(),
          }
          let arr = Object.values(obj);
          this.$store.commit("writeLog",arr);

          this.$router.go(-1);

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
  }
}
</script>

<style>

</style>