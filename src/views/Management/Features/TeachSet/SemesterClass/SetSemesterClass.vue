<template>
  <div class="subpage">  
    <div class="pagehead">
      <h1>学期开班</h1>
    </div>
    <div style="margin-bottom: 36px" v-loading="loading" v-if="classData.length > 0">
      <div class="courseItem" v-for="(item,i) in classData" :key="'c-'+i">
        <el-switch
          v-model="semesterClass.filter(item2 => item2.class == item.class_id)[0]['status']"
          :inactive-text="item.class_name"
          active-color="#13ce66"
          inactive-color="#95a5a6"
          active-value="1"
          inactive-value="0">
        </el-switch>
      </div>
    </div>
    <div v-else class="nomore" style="margin-bottom: 36px">
      <el-alert
        title="暂无课程数据"
        show-icon 
        type="info"
        :closable="false">
      </el-alert>
    </div>
    <el-button size="small" @click="onBack">返回</el-button>
    <el-button size="small" type="primary" @click="onSubmit">提交</el-button>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      profession: this.$route.query.pro,
      semester: this.$route.query.sem,
      classData: [],
      value: "",
      loading: false,
      
      semesterClass: [],
    }
  },
  created(){
    this.getClass();
    this.getSemesterClass();
    $.each(this.classData,(i, v)=>{ 
      if(this.semesterClass.filter(item=>item.class == v.class_id).length == 0){
        this.semesterClass.push({
          'class': v.class_id,
          "semester": this.semester,
          "school": this.$store.state.userSchool,
          'campus': this.$store.state.userCampus,
          'department': this.$store.state.userDepartment,
          'status': 0,
          "created_user": this.$store.state.userId,
        })
      }
    });
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/base/class.php",
        type: 'get',
        data:{
          type: "sel_class",
          selobj: {
            class_campus: this.$store.state.userCampus,
          },
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
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
    getSemesterClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class",
          semester: this.semester,
          department: this.$store.state.userDepartment,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterClass = res;
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
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'post',
        data:{
          type: "set_class",
          arr: this.semesterClass,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          $.each(res, (i, v)=>{ 
            if(v){
              return;
            }else{
              this.$message.error(this.semesterClass[i]['class']+' 设置错误!');
            }
          });
          
          this.$message({
            message: '恭喜你，学期班级设置成功！',
            type: 'success'
          });
          
          // 日志写入
          let obj = {
            content: "学期开班设置 学期: " + this.semester,
            type: "修改记录",
            model: "学期开班模块",
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

<style scoped>
  .courseItem{
    margin-bottom: 6px;
  }
</style>