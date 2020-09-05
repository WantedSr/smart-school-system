<template>
  <div class="subpage">
    <div v-if="toDef">
      <sel-placement @getClass="getClass" :semesterData="semesterData" :classData="classData" :departmentData="departmentData"></sel-placement>
    </div>
    <div v-else-if="toAdd">
      <add-placement @getClass="getClass" :semesterData="semesterData" :classData="classData" :departmentData="departmentData"></add-placement>
    </div>
    <div v-else-if="toImp">
      <imp-placement></imp-placement>
    </div>
    <div v-else-if="toUpa">
      <upa-placement @getClass="getClass" :semesterData="semesterData" :classData="classData" :departmentData="departmentData"></upa-placement>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelPlacement from "./SelPlacement";
import AddPlacement from "./AddPlacement";
import ImpPlacement from "./ImpPlacement";
import UpaPlacement from "./UpaPlacement";
export default {
  data(){
    return{
      n: this.$route.query.n,
      semesterData: [],
      departmentData: [], 
      classData: [],
    }
  },
  computed:{
    toDef(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    toAdd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    toImp(){
      if(this.$route.query.type == 'imp'){
        return true;
      }
      return false;
    },
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    },
  },
  components:{
    SelPlacement,
    AddPlacement,
    ImpPlacement,
    UpaPlacement,
  },
  created(){
    this.getSemester();
    this.getDepartment();
    this.getClass();
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
  },
}
</script>

<style>

</style>