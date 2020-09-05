<template>
  <div class="subpage" v-loading="loading">

    <div v-if="Tdefault">
      <sel-stu-arrangement @getDormBuild="getDormBuild" @getClass="getClass" @getDormroom="getDormroom" :semesterData="semesterData" :classData="classData" :departmentData="departmentData" :buildData="buildData" :dormroomData="dormroomData"></sel-stu-arrangement>
    </div>

    <div v-if="Tadd">
      <add-stu-arrangement @getDormBuild="getDormBuild" @getClass="getClass" @getDormroom="getDormroom" :semesterData="semesterData" :classData="classData" :departmentData="departmentData" :buildData="buildData" :dormroomData="dormroomData"></add-stu-arrangement>
    </div>

    <div v-if="Timport">
      <imp-stu-arrangement @getDormBuild="getDormBuild" :semesterData="semesterData" :departmentData="departmentData" :buildData="buildData"></imp-stu-arrangement>
    </div>

    <div v-if="Tupa">
      <upa-stu-arrangement @getDormBuild="getDormBuild" @getClass="getClass" @getDormroom="getDormroom" :semesterData="semesterData" :classData="classData" :departmentData="departmentData" :buildData="buildData" :dormroomData="dormroomData"></upa-stu-arrangement>
    </div> 

  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import AddStuArrangement from "./AddStuArrangement";
import SelStuArrangement from "./SelStuArrangement";
import ImpStuArrangement from "./ImpStuArrangement";
import UpaStuArrangement from "./UpaStuArrangement";
export default {
  data(){
    return{
      n: this.$route.query.n,
      loading: false,
      buildData: [],
      semesterData: [],
      departmentData: [],

      dormroomData: [],
      classData: [],
    }
  },
  created(){
    this.$store.commit("getUser");
    this.getSemester();
    this.getDepartment();

    this.getClass('all','all');

    this.getDormBuild('all');
    this.getDormroom('all');
  },
  components:{
    AddStuArrangement,
    SelStuArrangement,
    UpaStuArrangement,
    ImpStuArrangement,
  },
  methods:{
    getSemester(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
          col: col,
          sel: sel,
          val: val,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.semesterData = res;
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
    getDepartment(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          col: col,
          sel: 'campus',
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.departmentData = res;
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
    getDormBuild(dep){
      this.loading = true;
      let obj = dep == 'all' ? {'campus':this.$store.state.userCampus,type:"学生宿舍"} : {"department":dep,type:"学生宿舍"};
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          col: "*",
          selobj: obj,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.buildData = res;
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
    getClass(semester,department){
      let obj = {};
      if(semester == 'all'){
        if(department == 'all'){
          obj = {
            campus: this.$store.state.userCampus,
          };
        }else{
          obj = {
            campus: this.$store.state.userCampus,
            department: department,
          }
        }
      }else{
        if(department == 'all'){
          obj = {
            campus: this.$store.state.userCampus,
            semester: semester,
          };
        }else{
          obj = {
            campus: this.$store.state.userCampus,
            department: department,
            semester: semester,
          }
        }
      }
      // console.log(obj);
      obj['status'] = '1';
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          selobj: obj,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          // console.log(this.$store.state.userDepartment);
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
    getDormroom(build){
      let obj = {};
      obj = build == 'all' ? {campus: this.$store.state.userCampus} : {campus: this.$store.state.userCampus,build: build};
      requestAjax({
        type: "get",
        url: "/dormitory/dormroom.php",
        data:{
          type: 'sel_dormroom',
          selobj: obj,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.dormroomData = res;
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
  },
  computed:{
    Tdefault(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    Tadd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    Timport(){
      if(this.$route.query.type == 'import'){
        return true;
      }
      return false;
    },
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
  },

}
</script>

<style>
  .upload-demo{
    margin-bottom: 24px;
  }
</style>