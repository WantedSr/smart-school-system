<template>
  <div class="subpage">
    <div v-if="Tdefault">
      <sel-practice :classData="classData" :teacherData="teacherData"></sel-practice>
    </div>
    <div v-if="Tadd">
      <add-practice :classData="classData" :teacherData="teacherData"></add-practice>
    </div>
    <div v-if="Tupa">
      <upa-practice :classData="classData" :teacherData="teacherData"></upa-practice>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import SelPractice from "./SelPractice";
import AddPractice from "./AddPractice";
import UpaPractice from "./UpaPractice";

export default {
  data(){
    return{
      classData: [],
      teacherData: [],
    }
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
    Timp(){
      if(this.$route.query.type == 'imp'){
        return true;
      }
      return false;
    },
    Tupa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  components:{
    SelPractice,
    AddPractice,
    UpaPractice,
  },
  created(){
    this.getClass();
    this.getTeacher();
  },
  methods:{
    getClass(){
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          selobj: {
            semester: this.$store.state.semester,
            campus: this.$store.state.userCampus,
            status: "1",
          },
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
    getTeacher(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          col: "userid,username,department",
          selobj: {
            campus: this.$store.state.userCampus,
            type: '1',
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
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
  }
}
</script>

<style>

</style>