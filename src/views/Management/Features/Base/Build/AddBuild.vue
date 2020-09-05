<template>
  <div class="addBuild">
    <div class="pagehead">
      <h1>添加教学楼信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="100px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="build_name" label="教学楼名称">
          <el-input style="width: 250px" v-model="sel_add.build_name" placeholder="教学楼名称"></el-input>
        </el-form-item>
        <el-form-item prop="build_campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel_add.build_campus" placeholder="所在校区">
            <el-option v-for="(item,i) in campusData" :key="'1-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="build_department" label="所在部门">
          <el-select @change="getBuildId" v-model="sel_add.build_department" placeholder="所在部门">
            <el-option v-for="(item,i) in departmentData" :key="'2-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="build_id" label="教学楼代码">
          <el-input style="width: 250px" v-model="sel_add.build_id" placeholder="教学楼代码"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onAdd('sel_add')">添加</el-button>
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
      sel_add:{
        build_id: "",
        build_name: "",
        build_department: "",
        build_school: this.$store.state.userSchool,
        build_campus: "",
      },
      rules:{
        build_name: [
          { required: true, message: '请输入教学楼名称', trigger: 'blur' },
        ],
        build_id: [
          { required: true, message: '请选择教学楼代码', trigger: 'blur' }
        ],
        build_campus: [
          {required: true, message: '请选择教学楼所在校区', trigger: 'change' }
        ],
        build_department: [
          {required: true, message: '请选择教学楼所在部门', trigger: 'change' }
        ],
      },
    }
  },
  props:{
    campusData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          requestAjax({
            type: "get",
            url: "/base/build.php",
            data: {
              type: "add_build",
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '添加教学楼成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加教学楼信息 "+ this.sel_add.build_id,
                  type: "添加记录",
                  model: "教学楼模块",
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
    selCampus(v){
      this.sel_add.build_department = '';
      this.$emit('getDep',v);
    },
    getBuildId(v){
      requestAjax({
        url: "/base/build.php",
        type: 'get',
        data:{
          type: "sel_unmodify_build",
          col: "COUNT(*)",
          sel: 'build_department',
          val: v,
        },
        async: true,
        success:(res)=>{
          res = parseInt(JSON.parse(res)[0]['COUNT(*)']) + 1;
          res = String(res).length == 1 ? '0'+res : res;
          let id = v+'_B'+res;
          this.sel_add.build_id = id;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
    }
  }
}
</script>

<style>

</style>