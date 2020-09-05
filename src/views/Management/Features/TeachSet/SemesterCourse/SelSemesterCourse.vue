<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>选择学期与专业</h1>
    </div>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select size="small" v-model="sel.profession" placeholder="查询专业">
          <el-option v-for="(item,i) in skillData" :key="'k'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button size="small" type="primary" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        semester: this.$store.state.semester,
        profession: "",
      },
      n: this.$route.query.n,
      loading: false,
      semesterData: [],
      skillData: [],
    }
  },
  created(){
    this.getSemester();
    this.getSkill();
  },
  methods:{
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
    getSkill(){
      this.loading = true;
      requestAjax({
        url: "/base/profession.php",
        type: 'get',
        data:{
          type: "sel_profession",
          sel: this.$store.state.userDepartment != '0' || this.$store.state.userDepartment != '' ? "skill_department" : 'skill_campus',
          val: this.$store.state.userDepartment != '0' || this.$store.state.userDepartment != '' ? this.$store.state.userDepartment : this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.skillData = res;
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
      if(this.sel.semester != '' && this.sel.profession != ''){
        this.$router.push({
          query:{
            n: this.n,
            pro: this.sel.profession,
            sem: this.sel.semester,
          },
        })
      }else{
        this.$message({
          message: '请选择完所有选项',
          type: 'warning'
        });
      }
    },
  }
}
</script>

<style>

</style>