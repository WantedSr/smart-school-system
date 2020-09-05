<template>
  <div class="addBuild">
    <div class="pagehead">
      <h1>编辑教学楼信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="build_name" label="教学楼名称">
          <el-input style="width: 250px" v-model="sel_upa.build_name" placeholder="教学楼名称"></el-input>
        </el-form-item>
        <el-form-item prop="build_campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel_upa.build_campus" placeholder="所在校区">
            <el-option v-for="(item,i) in campusData" :key="'1-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="build_department" label="所在部门">
          <el-select @change="getBuildId" v-model="sel_upa.build_department" placeholder="所在部门">
            <el-option v-for="(item,i) in departmentData" :key="'2-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="build_id" label="教学楼代码">
          <el-input style="width: 250px" v-model="sel_upa.build_id" placeholder="教学楼代码"></el-input>
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
      sel_upa:'',
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
      department: '',
    }
  },
  created(){
    this.getData();
    this.selCampus(this.sel_upa.build_campus);
    this.sel_upa.build_department = this.department;
  },
  props:{
    campusData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/build.php",
            type: 'get',
            data:{
              type: "upa_build",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '修改教学楼信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改教学楼信息 "+ this.sel_add.build_id,
                  type: "修改记录",
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
              }else{
                this.$message.error('修改教学楼信息失败，请稍后再试！');
              }
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    getData(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/build.php",
        type: 'get',
        data:{
          type: "sel_build",
          sel: 'build_id',
          val: this.$route.query.buildId,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.department = res['build_department'];
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
    onBack(){
      this.$router.go(-1);
    },
    selCampus(v){
      this.sel_upa.build_department = '';
      this.$emit('getDep',v);
    },
  }
}
</script>

<style>

</style>