<template>
  <div class="upaTeacherInfo" v-loading="loading">
    <div class="pagehead">
      <h1>编辑教师信息</h1>
    </div>
    <div>
      <el-form ref="form" label-position="left" :rules="rules" :model="form" label-width="100px">
        <el-form-item prop="userid" label="用户id">
          <el-input placeholder="请填写登录名称" v-model="form.userid"></el-input>
        </el-form-item>
        <el-form-item prop="teacher_phone" label="电话号码">
          <el-input placeholder="请填写电话号码" v-model="form.teacher_phone"></el-input>
        </el-form-item>
        <el-form-item prop="username" label="姓名">
          <el-input placeholder="请填写真实姓名" v-model="form.username"></el-input>
        </el-form-item>
        <el-form-item prop="sex" label="性别">
          <el-select v-model="form.sex" placeholder="请选择性别">
            <el-option label="男" value="男"></el-option>
            <el-option label="女" value="女"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="nation" label="民族">
          <el-input placeholder="请填写民族" v-model="form.nation"></el-input>
        </el-form-item>
        <el-form-item prop="teacher_sfz" label="身份证">
          <el-input placeholder="请填写身份证" v-model="form.teacher_sfz"></el-input>
        </el-form-item>
        <el-form-item prop="teacher_born" label="出生日期">
          <el-date-picker
            v-model="form.teacher_born"
            type="date"
            placeholder="选择日期"
            format="yyyy 年 MM 月 dd 日"
            value-format="yyyyMMdd">
          </el-date-picker>
        </el-form-item>
        <el-form-item required label="户籍地址">
          <el-row :gutter="6">
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="house_register_provinces">
                <el-select @change="regSelCity" size="small" v-model="form.house_register_provinces" placeholder="请选择省份">
                  <el-option v-for="(item,i) in provinceData" :key="'p1-'+i" :label="item.province" :value="item.provinceid"></el-option>
                </el-select>
              </el-form-item>
            </el-col>  
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="house_register_city">
                <el-select @change="regSelArea" size="small" v-model="form.house_register_city" placeholder="请选择市">
                  <el-option v-for="(item,i) in regCityData" :key="'c1-'+i" :label="item.city" :value="item.cityid"></el-option>
                </el-select>
              </el-form-item>
            </el-col>  
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="house_register_area">
                <el-select size="small" v-model="form.house_register_area" placeholder="请选择区/县">
                  <el-option v-for="(item,i) in regAreaData" :key="'a1-'+i" :label="item.area" :value="item.areaid"></el-option>
                </el-select>
              </el-form-item>
            </el-col>  
          </el-row> 
        </el-form-item>
        <el-form-item required label="居住地址">
          <el-row :gutter="6">
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="address_province">
                <el-select @change="addSelCity" size="small" v-model="form.address_province" placeholder="请选择省份">
                  <el-option v-for="(item,i) in provinceData" :key="'p1-'+i" :label="item.province" :value="item.provinceid"></el-option>
                </el-select>
              </el-form-item>
            </el-col>  
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="address_city">
                <el-select @change="addSelArea" size="small" v-model="form.address_city" placeholder="请选择市">
                  <el-option v-for="(item,i) in regCityData" :key="'c1-'+i" :label="item.city" :value="item.cityid"></el-option>
                </el-select>
              </el-form-item>
            </el-col>  
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="address_area">
                <el-select size="small" v-model="form.address_area" placeholder="请选择区/县">
                  <el-option v-for="(item,i) in regAreaData" :key="'a1-'+i" :label="item.area" :value="item.areaid"></el-option>
                </el-select>
              </el-form-item>
            </el-col>  
            <el-col :lg="4" :md="6" :sm="11" :xs="23">
              <el-form-item prop="address_detailed">
                <el-input placeholder="请填写详细地址" v-model="form.address_detailed"></el-input>
              </el-form-item>
            </el-col>  
          </el-row> 
        </el-form-item>
        <el-form-item label="社会面貌">
          <el-input placeholder="请填写社会面貌,若无则空着" v-model="form.china_member"></el-input>
        </el-form-item>
        <el-form-item prop="type" label="状态">
          <el-select v-model="form.type" placeholder="请选择状态">
            <el-option label="在职" value="1"></el-option>
            <el-option label="离职" value="2"></el-option>
            <el-option label="调出" value="3"></el-option>
            <el-option label="退休" value="4"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onUpa('form')">编辑</el-button>
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
      form:{},
      loading: true,
      
      provinceData:[],
      addCityData:[],
      addAreaData:[],
      regCityData:[],
      regAreaData:[],

      rules:{
        username: [
          { required: true, message: '请输入教师名称', trigger: 'blur' },
        ],
        userid: [
          { required: true, message: '请输入教师编号', trigger: 'blur' }
        ],
        nation: [
          { required: true, message: '请输入民族', trigger: 'blur' }
        ],
        teacher_sfz: [
          { required: true, message: '请输入学生身份证号', trigger: 'blur' }
        ],
        teacher_phone: [
          { required: true, message: '请输入学生电话号码', trigger: 'blur' }
        ],
        sex: [
          { required: true, message: '请选择教师性别', trigger: 'change' }
        ],
        address_province: [
          { required: true, message: '请选择住址省份', trigger: 'change' }
        ],
        address_city: [
          { required: true, message: '请选择住址城市', trigger: 'change'}
        ],
        address_area: [
          { required: true, message: '请选择住址区/县', trigger: 'change' }
        ],
        address_detailed: [
          { required: true, message: '请选择住址详细地址', trigger: 'blur' }
        ],
        house_register_provinces: [
          { required: true, message: '请选择户籍省份', trigger: 'change' }
        ],
        house_register_city: [
          { required: true, message: '请选择户籍城市', trigger: 'change'}
        ],
        house_register_area: [
          { required: true, message: '请选择户籍区/县', trigger: 'change' }
        ],
        house_register_type: [
          { required: true, message: '请选择户籍类型', trigger: 'change' }
        ],
        teacher_born: [
          {required: true, message: '请选择出生年月', trigger: 'change' }
        ],
        type: [
          {required: true, message: '请选择教师状态', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.getData();
    this.selProvince();
    
    let addCity = this.form.address_city;
    let addArea = this.form.address_area;
    let regCity = this.form.house_register_city;
    let regArea = this.form.house_register_area;
    this.addSelCity(this.form.address_province);
    this.form.address_city = addCity;
    this.addSelArea(this.form.address_city);
    this.form.address_area = addArea;
    this.regSelCity(this.form.house_register_provinces);
    this.form.house_register_city = regCity;
    this.regSelArea(this.form.house_register_city);
    this.form.house_register_area = regArea;
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          selobj: {
            'userid':this.$route.query.teaId,
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          // console.log(res);
          this.loading = false;
          this.form = res;
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
    selProvince(){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_provinces",
        },
        success:(res)=>{
          this.provinceData = JSON.parse(res);
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    selCity(v,t){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_cities",
          provinceId: v,
        },
        success:(res)=>{
          if(t == 'add'){
            this.addCityData = JSON.parse(res);
          }
          else if(t == 'reg'){
            this.regCityData = JSON.parse(res);
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
    },
    selArea(v,t){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_areas",
          cityId: v,
        },
        success:(res)=>{
          // console.log(res);
          if(t == 'add'){
            this.addAreaData = JSON.parse(res);
          }else if(t == 'reg'){
            this.regAreaData = JSON.parse(res);
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
    },
    addSelCity(v){
      if(this.form.address_province == '') return;
      this.form.address_city = '';this.form.address_area = '';
      this.selCity(v,'add');
    },
    addSelArea(v){
      if(this.form.address_province == '' || this.form.address_city == '') return;
      this.form.address_area = '';
      this.selArea(v,'add');
    },
    regSelCity(v){
      if(this.form.house_register_province == '') return;
      this.form.house_register_city = '';this.form.house_register_area = '';
      this.selCity(v,'reg');
    },
    regSelArea(v){
      if(this.form.house_register_province == '' || this.form.house_register_city == '') return;
      this.form.house_register_area = '';
      this.selArea(v,'reg');
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            type: "get",
            url: "/TeaManagement/TeaInfo/TeaLibrary.php",
            data:{
              type: "upa_tea",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              // console.log(res);
              if(res){
                this.$message({
                  message: '修改教师信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改教师信息 "+this.form.userid,
                  type: "修改记录",
                  model: "教师库模块",
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
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    }
  }
}
</script>

<style>

</style>