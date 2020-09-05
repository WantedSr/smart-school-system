<template>
  <div class="purchase">
    <div class="pagehead">
      <h1>修改领用借用资产信息</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="80px">
        <el-form-item prop="asset_id" label="资产">
          <el-select v-model="form.asset_id" placeholder="请选择资产">
            <el-option v-for="(item,i) in assetData" :key="'a-'+i" :label="item.asset_name + '--' + item.asset_brand" :value="item.asset_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="semester" label="所在学期">
          <el-select v-model="form.semester" placeholder="请选择所在学期">
            <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="department" label="所在部门">
          <el-select size="small" v-model="form.department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="get_type" label="使用形式">
          <el-radio-group v-model="form.get_type">
            <el-radio label="领用"></el-radio>
            <el-radio label="借用"></el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item prop="borrow_num" label="数量">
          <el-input min="0" max="80" type="number" v-model="form.borrow_num"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">编辑</el-button>
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
      form:{
        asset_id: "",
        get_type: "",
        borrow_num: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        department: "",
        semester: "",
        created_usesr: this.$store.state.userId,
      },
      old: {},
      semesterData: [],
      departmentData: [],
      assetData: [],
      rules:{
        semester: [
          { required: true, message: '请选择所在学期', trigger: 'change' },
        ],
        get_type: [
          { required: true, message: '请选择使用类型', trigger: 'change' },
        ],
        borrow_num: [
          { required: true, message: '请输入数量', trigger: 'blur' },
        ],
        asset_id: [
          { required: true, message: '请选择资产', trigger: 'select' },
        ],
        department: [
          { required: true, message: '请选择部门', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.getData();
    this.getSemester();
    this.getDep();
    this.getAsset();
  },
  methods:{
    getData(){ 
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/property/borrow.php",
        data:{
          type: "sel_borrow",
          selobj: {
            id: this.$route.query.borId,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          // console.log(res);
          this.form = res;
          this.old = {
            'asset_id': res['asset_id'],
            'borrow_num':res['borrow_num'],
          };
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
    onBack(){
      this.$router.go(-1);
    },
    getAsset(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/property/asset.php",
        data:{
          type: "sel_asset",
          selobj: {
            'campus':this.$store.state.userCampus,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.assetData = res;
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
    getDep(){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: "*",
          sel: "campus",
          val: this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
    getSemester(){
      this.loading = true;
      requestAjax({
        type: 'get',
        url: "/base/semester.php",
        data:{
          type: "sel_semester",
        },
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
        },
      })
    },
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/property/borrow.php",
            type: 'get',
            data:{
              type: "upa_borrow",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
              old: this.old,
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '资产领用信息修改成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "资产" + this.form.get_type + "修改信息 数量 " + this.form.borrow_num + " " + this.form.department + " " + this.form.asset_id,
                  type:  "修改记录",
                  model: "资产领用模块",
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
                this.$message.error('登记失败，请稍后再试！');
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
          });
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
  .line{
    text-align: center;
  }
</style>