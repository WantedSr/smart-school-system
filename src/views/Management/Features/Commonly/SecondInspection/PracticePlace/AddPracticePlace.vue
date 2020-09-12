<template>
  <div class="subpage" v-loading='loading'>
    <div class="add_record">
      <div class="pagehead">
        <h1>添加实习场地信息</h1>
      </div>
      
      <div class="sel">
        <el-form label-position="left" label-width="100px" :rules="rules" ref="form" :model="form" class="demo-form-inline">
          <el-form-item prop="department" label="所在部门">
            <el-select @change="getClass" size="small" v-model="form.department" placeholder="选择部门">
              <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="place_id" label="场所编号">
            <el-input style="width: 250px" v-model="form.place_id" placeholder="场所ID（自动获取）"></el-input>
          </el-form-item>
          <el-form-item prop="place_name" label="场所名称">
            <el-input style="width: 250px" v-model="form.place_name" placeholder="输入场所名称"></el-input>
          </el-form-item>
          <el-form-item label="">
            <el-button size="small" type="success" @click="onAdd('form')">添加</el-button>
            <el-button size="small" @click="onBack">取消</el-button>
          </el-form-item>
        </el-form>
      </div>

    </div>  
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      form:{
        place_id: "",
        place_name: "",
        department: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },
      
      rules:{
        department: [
          { required: true, message: '请选择部门', trigger: 'change' }
        ],
        place_id: [
          { required: true, message: '请输入场所编号', trigger: 'blur' }
        ],
        place_name: [
          { required: true, message: '请输入场所名称', trigger: 'blur' }
        ],
      },

      loading: false,
    }
  },
  created(){
    this.getId();
  },
  props:{
    departmentData:{
      type: Array,
      default: [],
      request: true,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getId(){
      this.loading = true;
      requestAjax({
        type: 'post',
        url: "/SecondInspection/PracticePlace.php",
        data:{
          action: "getId",
          campus: this.$store.state.userCampus,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          if(res.data.length > 0){
            let oldid = res.data[0].id;
            this.form.place_id = "P" + (parseInt(oldid) + 1);
          }
          else{
            this.form.place_id = "P1";
          }
          console.log(res);
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
          this.form.addTime = new Date().getTime();
          let arr = Object.values(this.form);
          this.loading = true;
          requestAjax({
            url: "/SecondInspection/PracticePlace.php",
            type: "post",
            data:{
              action: "add",
              arr: JSON.stringify(arr),
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              // console.log(res);
              if(res.data){
                this.$message({
                  message: '添加实习场所信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加实习场所信息 学期：" + this.form.semester + " 部门：" + this.form.department + " 场所" + this.form.place_name,
                  type: "添加记录",
                  model: "实习场所管理模块",
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
              this.loading = false;
            },
            error:(err)=>{
              this.loading = false;
              console.error(err);
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
  }
}
</script>

<style>

</style>