<template>
  <div class="addStuInfo">
    <div class="pagehead">
      <h1>修改学生信息</h1>
    </div>
    <div>
      <el-form label-position="left" :rules="rules" :inline="true" :model="ruleForm" ref="ruleForm" label-width="80px" class="demo-ruleForm">
        <el-row :gutter="6">
          <el-col :lg="6" :md="8" :sm="12" :xs="24">
            <el-form-item label="姓名" prop="username">
              <el-input size="small" v-model="ruleForm.username"></el-input>
            </el-form-item>
            <el-form-item label="性别" prop="sex">
              <el-select size="small" v-model="ruleForm.sex" placeholder="请选择性别">
                <el-option label="男" value="男"></el-option>
                <el-option label="女" value="女"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="bornDate" label="出生年月">
              <el-date-picker size="small" type="date" placeholder="选择日期"
              format="yyyy 年 MM 月 dd 日"
              value-format="yyyyMMdd" 
              v-model="ruleForm.bornDate" 
              style="width: 100%"></el-date-picker>
            </el-form-item>
            <el-form-item label="民族" prop="nation">
              <el-input size="small" v-model="ruleForm.nation"></el-input>
            </el-form-item>
            <el-form-item label="身份证" prop="user_ID">
              <el-input size="small" v-model="ruleForm.user_ID"></el-input>
            </el-form-item>
            <el-form-item label="电话号码" prop="phone_me">
              <el-input size="small" v-model="ruleForm.phone_me"></el-input>
            </el-form-item>
          </el-col>
          
          <el-col :lg="6" :md="8" :sm="12">
            <el-form-item label="学号" prop="userid">
              <el-input size="small" v-model="ruleForm.userid"></el-input>
            </el-form-item>
            <el-form-item label="专业部" prop="department">
              <el-select @change="selSkill" size="small" v-model="ruleForm.department" placeholder="请选择专业部">
                <el-option v-for="(item,i) in depData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="专业" prop="skill">
              <el-select @change="selClass" size="small" v-model="ruleForm.skill" placeholder="请选择专业">
                <el-option v-for="(item,i) in skillData" :key="'2-'+i" :label="item.skill_name" :value="item.skill_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="年级" prop="grade">
              <el-select @change="selClass" size="small" v-model="ruleForm.grade" placeholder="请选择年级">
                <el-option v-for="(item,i) in gradeData" :key="'3-'+i" :label="item.grade_name" :value="item.grade_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="班级" prop="class">
              <el-select size="small" v-model="ruleForm.class" placeholder="请选择班级">
                <el-option v-for="(item,i) in classData" :key="'4-'+i" :label="item.class_name" :value="item.class_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="状态" prop="type">
              <el-select size="small" v-model="ruleForm.type" placeholder="请选择学生状态">
                <el-option v-for="(item,i) in statusData" :key="'4-'+i" :label="item.status_name" :value="item.status_id"></el-option>
              </el-select>
            </el-form-item>
          </el-col>

          <el-col :lg="6" :md="8" :sm="12">
            <el-form-item label="家庭电话" prop="phone_home">
              <el-input size="small" v-model="ruleForm.phone_home"></el-input>
            </el-form-item>
            <el-form-item label="父母姓名" prop="parent_name">
              <el-input size="small" v-model="ruleForm.parent_name"></el-input>
            </el-form-item>
            <el-form-item label="父母电话" prop="phone_parent">
              <el-input size="small" v-model="ruleForm.phone_parent"></el-input>
            </el-form-item>
            <el-form-item label="团籍" prop="chinaMember">
              <el-switch size="small" active-value='1' inactive-value="0" v-model="ruleForm.china_member"></el-switch>
            </el-form-item>
            <el-form-item label="中考分数" prop="exam">
              <el-input size="small" v-model="ruleForm.exam"></el-input>
            </el-form-item>
          </el-col>

          <el-col :lg="6" :md="8" :sm="12">
            <el-form-item label="住址(省)" prop="address_province">
              <el-select @change="addSelCity" size="small" v-model="ruleForm.address_province" placeholder="请选择省份">
                <el-option v-for="(item,i) in provinceData" :key="'p1-'+i" :label="item.province" :value="item.provinceid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="住址(市)" prop="address_city">
              <el-select @change="addSelArea" size="small" v-model="ruleForm.address_city" placeholder="请选择市">
                <el-option v-for="(item,i) in addCityData" :key="'c1-'+i" :label="item.city" :value="item.cityid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="住址(区)" prop="address_area">
              <el-select size="small" v-model="ruleForm.address_area" placeholder="请选择区/县">
                <el-option v-for="(item,i) in addAreaData" :key="'a1-'+i" :label="item.area" :value="item.areaid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="详细地址" prop="address_detailed">
              <el-input size="small" v-model="ruleForm.address_detailed"></el-input>
            </el-form-item>
            <el-form-item label="户籍(省)" prop="house_register_provinces">
              <el-select @change="regSelCity" size="small" v-model="ruleForm.house_register_provinces" placeholder="请选择省份">
                <el-option v-for="(item,i) in provinceData" :key="'p1-'+i" :label="item.province" :value="item.provinceid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="户籍(市)" prop="house_register_city">
              <el-select @change="regSelArea" size="small" v-model="ruleForm.house_register_city" placeholder="请选择市">
                <el-option v-for="(item,i) in regCityData" :key="'c1-'+i" :label="item.city" :value="item.cityid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="户籍(区)" prop="house_register_area">
              <el-select size="small" v-model="ruleForm.house_register_area" placeholder="请选择区/县">
                <el-option v-for="(item,i) in regAreaData" :key="'a1-'+i" :label="item.area" :value="item.areaid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="户籍类型" prop="house_register_type">
              <el-select size="small" v-model="ruleForm.house_register_type" placeholder="请选择区/县">
                <el-option label="城市" value="1"></el-option>
                <el-option label="农村" value="0"></el-option>
              </el-select>
            </el-form-item>
          </el-col>

          <el-col style="text-align:right;margin-top: 24px" :span="24">
            <el-form-item>
              <el-button type="primary" @click="onUpa('ruleForm')">编辑</el-button>
              <el-button @click="onBack">返回</el-button>
            </el-form-item>
          </el-col>
        </el-row>
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
      ruleForm:{},
      rules:{
        username: [
          { required: true, message: '请输入学生名称', trigger: 'blur' },
        ],
        userid: [
          { required: true, message: '请输入学生学号', trigger: 'blur' }
        ],
        nation: [
          { required: true, message: '请输入民族', trigger: 'blur' }
        ],
        user_ID: [
          { required: true, message: '请输入学生身份证号', trigger: 'blur' }
        ],
        phone_me: [
          { required: true, message: '请输入学生电话号码', trigger: 'blur' }
        ],
        phone_parent: [
          { required: true, message: '请输入学生父母电话', trigger: 'blur' }
        ],
        phone_home: [
          { required: true, message: '请输入学生家庭', trigger: 'blur' }
        ],
        parent_name: [
          { required: true, message: '请输入学生家长姓名', trigger: 'blur' }
        ],
        sex: [
          { required: true, message: '请选择学生性别', trigger: 'change' }
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
        department: [
          { required: true, message: '请选择部门', trigger: 'change' }
        ],
        skill: [
          { required: true, message: '请选择专业', trigger: 'change' }
        ],
        grade: [
          { required: true, message: '请选择年级', trigger: 'change' }
        ],
        class: [
          { required: true, message: '请选择班级', trigger: 'change' }
        ],
        bornDate: [
          {required: true, message: '请选择出生年月', trigger: 'change' }
        ],
        campus_position: [
          {required: true, message: '请选择所处区/县', trigger: 'change' }
        ],
      },
    }
  },
  props:{
    depData:{
      type: Array,
      require: true,
    },
    skillData:{
      type: Array,
      require: true,
    },
    gradeData:{
      type: Array,
      require: true,
    },
    classData:{
      type: Array,
      require: true,
    },
    provinceData:{
      type: Array,
      require: true,
    },
    addCityData:{
      type: Array,
      require: true,
    },
    addAreaData:{
      type: Array,
      require: true,
    },
    regCityData:{
      type: Array,
      require: true,
    },
    regAreaData:{
      type: Array,
      require: true,
    },
    statusData:{
      type: Array,
      require: true,
    }
  },
  created(){
    this.getData();
    let skill = this.ruleForm.skill;
    let cls = this.ruleForm.class;
    let addCity = this.ruleForm.address_city;
    let addArea = this.ruleForm.address_area;
    let regCity = this.ruleForm.house_register_city;
    let regArea = this.ruleForm.house_register_area;
    this.selSkill(this.ruleForm.dpmentid);
    this.ruleForm.skill = skill;
    this.selClass();
    this.ruleForm.class = cls;
    this.addSelCity(this.ruleForm.address_province);
    this.ruleForm.address_city = addCity;
    this.addSelArea(this.ruleForm.address_city);
    this.ruleForm.address_area = addArea;
    this.regSelCity(this.ruleForm.house_register_provinces);
    this.ruleForm.house_register_city = regCity;
    this.regSelArea(this.ruleForm.house_register_city);
    this.ruleForm.house_register_area = regArea;
    // console.log(this.ruleForm.dpmentid);
  },
  methods:{
    getData(){
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          selobj:{
            'userid':this.$route.query.stuId,
          },
        },
        async: false,
        success:(res)=>{
          // console.log(res);
          this.ruleForm = JSON.parse(res)[0];
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
    onUpa(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            type: "get",
            url: "/StuManagement/StuInfo/StuLibrary.php",
            data:{
              type: "upa_stu",
              obj: this.ruleForm,
              sel: 'id',
              val: this.ruleForm.id,
            },
            success:(res)=>{
              // console.log(res);
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '修改学生信息成功',
                  type: 'success'
                });


                // 日志写入
                let obj = {
                  content: "修改学生档案 学生：" + this.ruleForm.id,
                  type: "修改记录",
                  model: "学生库模块",
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
    },
    onBack(){
      this.$router.go(-1);
    },
    selSkill(v){
      this.ruleForm.skill = '',
      this.$emit('selSkill',v);
    },
    selClass(v){
      this.ruleForm.class = '';
      if(this.ruleForm.skill == "" || this.ruleForm.grade == '') return;
      this.$emit('selClass',this.ruleForm.dpmentid,this.ruleForm.skill,this.ruleForm.grade);
    },
    addSelCity(v){
      if(this.ruleForm.address_province == '') return;
      this.ruleForm.address_city = '';this.ruleForm.address_area = '';
      this.$emit('selCity',v,'add');
    },
    addSelArea(v){
      if(this.ruleForm.address_province == '' || this.ruleForm.address_city == '') return;
      this.ruleForm.address_area = '';
      this.$emit('selArea',v,'add');
    },
    regSelCity(v){
      if(this.ruleForm.house_register_province == '') return;
      this.ruleForm.house_register_city = '';this.ruleForm.house_register_area = '';
      this.$emit('selCity',v,'reg');
    },
    regSelArea(v){
      if(this.ruleForm.house_register_province == '' || this.ruleForm.house_register_city == '') return;
      this.ruleForm.house_register_area = '';
      this.$emit('selArea',v,'reg');
    },
  }
}
</script>

<style>

</style>