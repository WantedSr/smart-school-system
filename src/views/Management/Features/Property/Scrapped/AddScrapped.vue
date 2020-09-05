<template>
  <div class="purchase">
    <div class="pagehead">
      <h1>添加报废资产信息</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="80px">
        <el-form-item prop="semester" label="所在学期">
          <el-select v-model="form.semester" placeholder="请选择所在学期">
            <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="asset_id" label="资产">
          <el-select v-model="form.asset_id" placeholder="请选择资产">
            <el-option v-for="(item,i) in assetData" :key="'a-'+i" :label="item.asset_name + '--' + item.asset_brand" :value="item.asset_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="scrapped_type" label="报废原因">
          <el-select v-model="form.scrapped_type" placeholder="请选择报废原因">
            <el-option v-for="(item,i) in scrappedType" :key="'s-'+i" :label="item.type_name" :value="item.type_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="scrapped_num" label="报废数量">
          <el-input type="number" v-model="form.scrapped_num"></el-input>
        </el-form-item>
        <el-form-item prop="scrapped_store" label="存储位置">
          <el-select size="small" v-model="form.scrapped_store" placeholder="选择存储部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">立即添加</el-button>
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
        scrapped_type: "",
        scrapped_num: "",
        scrapped_store: "",
        semester: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId,
      },
      scrappedType: [],
      semesterData: [],
      departmentData: [],
      assetData: [],
      rules:{
        semester: [
          { required: true, message: '请选择所在学期', trigger: 'change' },
        ],
        scrapped_type: [
          { required: true, message: '请选择报废类型', trigger: 'change' },
        ],
        asset_id:[
          { required: true, message: '请选择资产', trigger: 'change' }
        ],
        scrapped_num: [
          { required: true, message: '请输入资产数量', trigger: 'blur' }
        ],
        scrapped_store: [
          { required: true, message: '请选择存放位置', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.getScrappedType();
    this.getSemester();
    this.getDep();
    this.getAsset();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;

          let arr = Object.values(this.form);
          let addTime = new Date().getTime();
          arr.push(addTime);

          requestAjax({
            url: "/property/scrapped.php",
            type: 'get',
            data:{
              type: "add_scrapped",
              arr: arr,
              num: this.form.scrapped_num,
              asset: this.form.asset_id,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '资产报废登记成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "报废资产登记 数量：" + this.form.scrapped_num + " 存储地址：" + this.form.department + " 资产：" + this.form.asset_id,
                  type: "添加记录",
                  model: "报废资产模块",
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
    getScrappedType(){
      this.loading = true;
      requestAjax({
        type: 'get',
        url: "/property/scrappedType.php",
        data:{
          type: "sel_scrapped_type",
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          console.log(res);
          this.scrappedType = res;
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
  }
}
</script>

<style>
  .line{
    text-align: center;
  }
</style>