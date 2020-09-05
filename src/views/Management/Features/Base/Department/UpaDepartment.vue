<template>
  <div class="upaDepartment">
    <div class="pagehead">
      <h1>编辑部门信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="department_id" label="部门代码">
          <el-input style="width: 250px" v-model="sel_upa.department_id" placeholder="部门代码"></el-input>
        </el-form-item>
        <el-form-item prop="department_name" label="部门名称">
          <el-input style="width: 250px" v-model="sel_upa.department_name" placeholder="部门名称"></el-input>
        </el-form-item>
        <el-form-item prop="campus" label="所在校区">
          <el-select v-model="sel_upa.campus" placeholder="请选择校区">
            <el-option v-for="(item,i) in campusData" :key="i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onUpa('sel_upa')">修改</el-button>
          <el-button @click="onBack">取消</el-button>
        </el-form-item>
      </el-form>
    </div>

  </div>  
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel_upa:{
        department_id: "",
        department_name: "",
        school: "",
        campus: "",
        state: "",
      },
      rules:{
        department_name: [
          { required: true, message: '请输入部门名称', trigger: 'blur' },
        ],
        department_id: [
          { required: true, message: '请选择部门代码', trigger: 'blur' }
        ],
        campus: [
          {required: true, message: '请选择所在校区', trigger: 'change' }
        ],
      },
    }
  },
  props:{
    campusData:{
      type: Array,
      require: true,
    }
  },
  created(){
    requestAjax({
      url: "/base/department.php",
      type: 'get',
      data:{
        type: "set_department", 
        sel: 'department_id',
        val: this.$route.query.departmentId,
      },
      async: true,
      success:(res)=>{
        res = JSON.parse(res)[0];
        this.sel_upa = res;
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
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            type: "get",
            url: "/base/department.php",
            data: {
              type: "upa_department",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改部门信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改部门信息 "+ this.sel_upa.department_id,
                  type: "修改记录",
                  model: "部门模块",
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
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  }
}
</script>

<style>

</style>