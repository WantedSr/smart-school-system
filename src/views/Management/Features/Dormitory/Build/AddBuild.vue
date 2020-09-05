<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>添加宿舍楼</h1>
    </div>
    <div class="addDormBuild">
      <el-form label-position="left" ref="sel"  :rules="rules" label-width="110px" :model="sel" class="demo-form-inline">
        <el-form-item prop="build_name" label="宿舍楼名称">
          <el-input style="width: 250px" v-model="sel.build_name" placeholder="输入宿舍楼名称"></el-input>
        </el-form-item>
        <el-form-item prop="build_floor_num" label="宿舍楼楼层数">
          <el-input type="number" style="width: 250px" v-model="sel.build_floor_num" placeholder="输入宿舍楼名称"></el-input>
        </el-form-item>
        <el-form-item prop="campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel.campus" placeholder="选择校区">
            <el-option v-for="(item,i) in campusData" :key="'1-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="department" label="部门">
          <el-select v-model="sel.department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'2-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="type" label="类型">
          <el-select v-model="sel.type" placeholder="选择类型">
            <el-option label="学生宿舍" value="学生宿舍"></el-option>
            <el-option label="教职工宿舍" value="教职工宿舍"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="build_id" label="宿舍楼代码">
          <el-input style="width: 250px" v-model="sel.build_id" placeholder="输入宿舍楼代码"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onAdd('sel')">添加</el-button>
          <el-button size="small" @click="onBack">取消</el-button>
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
      sel:{
        build_id: "",
        build_name: "",
        build_floor_num: "",
        school: this.$store.state.userSchool,
        campus: "",
        department: "",
        type: "",
        status: '1',
        created_user: this.$store.state.userId,
      },
      rules:{
        build_id: [
          { required: true, message: '请输入宿舍楼编码', trigger: 'blur' },
        ],
        build_name: [
          { required: true, message: '请输入宿舍楼名称', trigger: 'blur' }
        ],
        build_floor_num: [
          { required: true, message: '请输入宿舍楼楼层数', trigger: 'blur' }
        ],
        campus: [
          { required: true, message: '请选择宿舍楼所在校区', trigger: 'change' }
        ],
        department: [
          { required: true, message: '请选择宿舍楼代码', trigger: 'change' }
        ],
        type: [
          { required: true, message: '请选择宿舍楼类型', trigger: 'change' }
        ],
      },
      loading: false,
    }
  },
  props:{
    departmentData:{
      type: Array,
      require: true,
    },
    campusData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getBuildId(campus){
      this.loading = true;
      requestAjax({
        url: "/dormitory/build.php",
        type: 'get',
        data:{
          type: "get_build_id",
          campus: campus
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel.build_id = res;
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel);
          arr.push(new Date().getTime());
          this.loading = true;
          requestAjax({
            url: "/dormitory/build.php",
            type: 'get',
            data:{
              type: "add_build",
              arr: arr,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '添加宿舍楼成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加宿管楼 "+this.sel.build_id,
                  type: "添加记录",
                  model: "宿舍楼模块",
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
              this.loading = false;
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
    selCampus(v){
      this.sel.department = '';
      this.getBuildId(v);
      this.$emit('getDep',v);
    },
  }
}
</script>

<style>
  .addDormBuild{
    padding: 0 24px;
  }
</style>