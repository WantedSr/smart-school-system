<template>
  <div class="addStuInfo">
    <div class="pagehead">
      <h1>添加学生信息</h1>
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
              <el-input size="small" minlength="18" maxlength="18" v-model="ruleForm.user_ID"></el-input>
            </el-form-item>
            <el-form-item label="电话号码" prop="phone_me">
              <el-input size="small" v-model="ruleForm.phone_me"></el-input>
            </el-form-item>
          </el-col>
          <el-col :lg="6" :md="8" :sm="12">
            <el-form-item label="学号" prop="userid">
              <el-input type="number" size="small" v-model="ruleForm.userid"></el-input>
            </el-form-item>
            <el-form-item label="专业部" prop="dpmentid">
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
              <el-button type="primary" @click="onAdd('ruleForm')">添加</el-button>
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
      ruleForm:{
        userid: "",
        username: "",
        sex: "",
        nature: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        grade: "",
        department: "",
        skill: "",
        class: "",
        address_province: "",
        address_city: "",
        address_area: "",
        address_detailed: "",
        house_register_provinces: "",
        house_register_city: "",
        house_register_area: "",
        house_register_type: "",
        phone_me: "",
        phone_home: "",
        phone_parent: "",
        bornDate: "",
        nation: "",
        china_member: "",
        exam: "",
        job: "S1",
        type: "",
        parent_name: "",
        user_ID: "",
        evaluation: "",
        msgSet: "1,1",
        created_user: this.$store.state.userId
      },
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
  },
  methods:{
    onAdd(formName) {
      this.$refs[formName].validate((valid) => {
        if (valid) {

          let arr = Object.values(this.ruleForm);
          arr.push(new Date().getTime());

          requestAjax({
            type: "post",
            url: "/StuManagement/StuInfo/StuLibrary.php",
            data:{
              type: "add_stu",
              arr: arr,
              arr2:[
                this.ruleForm.userid,
                this.ruleForm.username,
                123456,
                this.ruleForm.phone_me,
                "S1",
                this.$store.state.userSchool,
                this.$store.state.userCampus,
                "",
                "",
                this.$store.state.userId,
                new Date().getTime()
              ],
            },
            success:(res)=>{
              // console.log(res);
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '添加学生信息成功',
                  type: 'success'
                });
                
                // 日志写入
                let obj = {
                  content: "添加学生档案 学生：" + this.ruleForm.userid,
                  type: "添加记录",
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
                this.$message.error('添加失败，请稍后再试！');
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