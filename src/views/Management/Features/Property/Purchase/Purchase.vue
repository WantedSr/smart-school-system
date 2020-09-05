<template>
  <div class="purchase">
    <div class="pagehead">
      <h1>添加固定资产</h1>
    </div>
    <div>
      <el-form ref="form" :rules="rules" :model="form" label-width="80px">
        <el-form-item prop="semester" label="所在学期">
          <el-select v-model="form.semester" placeholder="请选择所在学期">
            <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="asset_id" label="资产编号">
          <el-input v-model="form.asset_id" placeholder="请输入资产订单号或产品产品编号"></el-input>
        </el-form-item>
        <el-form-item prop="asset_name" label="资产名称">
          <el-input v-model="form.asset_name"></el-input>
        </el-form-item>
        <el-form-item prop="asset_type" label="资产类型">
          <el-select v-model="form.asset_type" placeholder="请选择类别">
            <el-option v-for="(item,i) in assetType" :key="'t-'+i" :label="item.type_name" :value="item.type_id"></el-option>
          </el-select>
        </el-form-item>
        <el-row>
          <el-col :span="11">
            <el-form-item label="资产品牌" prop="asset_brand">
              <el-input placeholder="请填写品牌" v-model="form.asset_brand"></el-input>
            </el-form-item>
          </el-col>
          <el-col class="line" :span="2">-</el-col>
          <el-col :span="11">
            <el-form-item label="资产规格" prop="asset_model">
              <el-input placeholder="请填写资产规格" v-model="form.asset_model"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item prop="buy_type" label="资产形式">
          <el-radio-group v-model="form.buy_type">
            <el-radio label="调拨" value="调拨"></el-radio>
            <el-radio label="外购" value="外购"></el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item prop="unit" label="单位">
          <el-input v-model="form.unit"></el-input>
        </el-form-item>
        <el-form-item prop="num" label="数量">
          <el-input type="number" v-model="form.num"></el-input>
        </el-form-item>
        <el-form-item prop="single_price" label="单价">
          <el-input type="number" v-model="form.single_price"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit('form')">立即创建</el-button>
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
        asset_type: "",
        asset_name: "",
        asset_brand: "",
        asset_model: "",
        buy_type: "",
        asset_id: "",
        unit: "",
        num: "",
        borrow_num: 0,
        scrapped_num: 0,
        single_price: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        semester: this.$store.state.semester,
        created_user: this.$store.state.userId,
      },
      assetType: [],
      semesterData: [],
      rules:{
        semester: [
          { required: true, message: '请选择所在学期', trigger: 'change' },
        ],
        asset_type: [
          { required: true, message: '请选择资产类型', trigger: 'change' },
        ],
        asset_name: [
          { required: true, message: '请输入资产名称', trigger: 'blur' },
        ],
        asset_brand: [
          { required: true, message: '请输入资产品牌', trigger: 'blur' },
        ],
        asset_model: [
          { required: true, message: '请输入资产规格', trigger: 'blur' },
        ],
        unit: [
          { required: true, message: '请输入单位', trigger: 'blur' },
        ],
        asset_id:[
          { required: true, message: '请输入资产编号', trigger: 'blur' }
        ],
        buy_type: [
          { required: true, message: '请选择购买方式', trigger: 'change' }
        ],
        num: [
          { required: true, message: '请输入资产数量', trigger: 'blur' }
        ],
        single_price: [
          { required: true, message: '请输入资产单价', trigger: 'blur' }
        ],
      },
    }
  },
  created(){
    this.getAssetType();
    this.getSemester();
  },
  methods:{
    getAssetType(){
      this.loading = true;
      requestAjax({
        type: 'get',
        url: "/property/assetType.php",
        data:{
          type: "sel_asset_type",
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.assetType = res;
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
            url: "/property/asset.php",
            type: 'get',
            data:{
              type: "add_asset",
              arr: arr,
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              console.log(arr);
              if(res){
                this.$message({
                  message: '资产采购登记成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "资产采购登记 "+addTime,
                  type: "添加记录",
                  model: "资产采购模块",
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

<style scoped>
  .line{
    text-align: center;
  }
</style>