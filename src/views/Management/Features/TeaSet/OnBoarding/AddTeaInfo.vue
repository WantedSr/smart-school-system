<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>教师入职登记</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-upload
        action="https://jsonplaceholder.typicode.com/posts/"
        class="upload-demo"
        ref="upload"
        accept=".xlsx, .xls"
        :on-preview="handlePreview"
        :on-remove="handleRemove"
        :file-list="fileList"
        :limit="1"
        :on-change="handle"
        :auto-upload="false">
        <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
        <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传</el-button>
        <el-button style="margin-left: 10px;" size="small" @click="down" type="warning">下载模板</el-button>
        <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
      </el-upload>
    </div>
    <div class="tea_onboarding" v-loading="loading">
      <el-form ref="form" :rules="rules" :model="form" label-width="100px">
        <el-row :gutter="6">
          <el-col :lg="12" :md="12" :sm="24">
            <el-form-item prop="userid" label="教师编码">
              <el-input size="small" v-model="form.userid" placeholder="请输入教师编码"></el-input>
            </el-form-item>
            <el-form-item prop="teacher_phone" label="电话号码">
              <el-input size="small" v-model="form.teacher_phone" placeholder="请输入电话号码"></el-input>
            </el-form-item>
            <el-form-item prop="teacher_name" label="姓名">
              <el-input size="small" v-model="form.teacher_name" placeholder="请输入真实姓名"></el-input>
            </el-form-item>
            <el-form-item prop="teacher_sfz" label="身份证">
              <el-input size="small" v-model="form.teacher_sfz" placeholder="请输入有效的身份证号"></el-input>
            </el-form-item>
            <el-form-item prop="sex" label="性别">
              <el-select size="small" v-model="form.sex" placeholder="请选择教师性别">
                <el-option label="男" value="男"></el-option>
                <el-option label="女" value="女"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item prop="nation" label="民族">
              <el-input size="small" v-model="form.nation" placeholder="请输入教师民族"></el-input>
            </el-form-item>
            <el-form-item prop="teacher_born" label="出生年月">
              <el-date-picker
                v-model="form.teacher_born"
                size="small"
                type="date"
                placeholder="选择日期"
                format="yyyy 年 MM 月 dd 日"
                value-format="yyyyMMdd">
              </el-date-picker>        
            </el-form-item>
            <el-form-item prop="china_member" label="社会面貌">
              <el-input size="small" v-model="form.china_member" placeholder="请输入教师社会面貌,若没有则空着"></el-input>
            </el-form-item>
          </el-col>
          <el-col :lg="12" :md="12" :sm="24">
            <el-form-item label="住址(省)" prop="address_province">
              <el-select @change="addSelCity" size="small" v-model="form.address_province" placeholder="请选择省份">
                <el-option v-for="(item,i) in provinceData" :key="'p1-'+i" :label="item.province" :value="item.provinceid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="住址(市)" prop="address_city">
              <el-select @change="addSelArea" size="small" v-model="form.address_city" placeholder="请选择市">
                <el-option v-for="(item,i) in addCityData" :key="'c1-'+i" :label="item.city" :value="item.cityid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="住址(区)" prop="address_area">
              <el-select size="small" v-model="form.address_area" placeholder="请选择区/县">
                <el-option v-for="(item,i) in addAreaData" :key="'a1-'+i" :label="item.area" :value="item.areaid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="详细地址" prop="address_detailed">
              <el-input size="small" placeholder="请输入住址详细地址" v-model="form.address_detailed"></el-input>
            </el-form-item>
            <el-form-item label="户籍(省)" prop="house_register_provinces">
              <el-select @change="regSelCity" size="small" v-model="form.house_register_provinces" placeholder="请选择省份">
                <el-option v-for="(item,i) in provinceData" :key="'p1-'+i" :label="item.province" :value="item.provinceid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="户籍(市)" prop="house_register_city">
              <el-select @change="regSelArea" size="small" v-model="form.house_register_city" placeholder="请选择市">
                <el-option v-for="(item,i) in regCityData" :key="'c1-'+i" :label="item.city" :value="item.cityid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="户籍(区)" prop="house_register_area">
              <el-select size="small" v-model="form.house_register_area" placeholder="请选择区/县">
                <el-option v-for="(item,i) in regAreaData" :key="'a1-'+i" :label="item.area" :value="item.areaid"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item label="户籍类型" prop="house_register_type">
              <el-select size="small" v-model="form.house_register_type" placeholder="请选择区/县">
                <el-option label="城市" value="城市"></el-option>
                <el-option label="农村" value="农村"></el-option>
              </el-select>
            </el-form-item>

            <el-form-item prop="type" label="在职情况">
              <el-select size="small" v-model="form.type" placeholder="在职情况">
                <el-option label="在职" value="1"></el-option>
                <el-option label="离职" value="2"></el-option>
                <el-option label="调出" value="3"></el-option>
                <el-option label="退休" value="4"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="24">
            <el-form-item>
              <el-button type="primary" @click="onSubmit('form')">立即创建</el-button>
              <el-button @click="onBack">取消</el-button>
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
import xlsx from "xlsx";
import {readFile} from "assets/js/readFile";
export default {
  data(){
    return{
      form:{
        userid: "",
        teacher_name: "",
        sex: "",
        nation: "",
        teacher_phone: "",
        teacher_sfz: "",
        teacher_job: "T1",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        department: "0",
        teacher_born: "",
        china_member: "",
        house_register_provinces: "",
        house_register_city: "",
        house_register_area: "",
        house_register_type: "",
        address_province: "",
        address_city: "",
        address_area: "",
        address_detailed: "",
        msgSet: '1,1,1',
        type: "",
        created_user: this.$store.state.userId
      },
      rules:{
        teacher_name: [
          { required: true, message: '请输入教师名称', trigger: 'blur' },
        ],
        userid: [
          { required: true, message: '请输入教师编号', trigger: 'blur' }
        ],
        nation: [
          { required: true, message: '请输入民族', trigger: 'blur' }
        ],
        teacher_sfz: [
          { required: true, message: '请输入教师身份证号', trigger: 'blur' }
        ],
        teacher_phone: [
          { required: true, message: '请输入教师电话号码', trigger: 'blur' }
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
          {required: true, message: '请选择教师在职情况', trigger: 'change' }
        ],
      },

      
      character:{
        userid: {
          text: "教师编码",
          type: "String",
        },
        username: {
          text: "教师姓名",
          type: "String",
        },
        sex: {
          text: "教师性别",
          type: "String",
        },
        nation: {
          text: "民族",
          type: "String",
        },
        teacher_sfz:{
          text: "身份证",
          type: "String",
        },
        teacher_phone:{
          text: "手机号码",
          type: "String",
        },
        teacher_job:{
          text: "职务",
          type: "String",
        },
        address_province:{
          text: "居住省份",
          type: "String",
        },
        address_city:{
          text: "居住城市",
          type: "String",
        },
        address_area:{
          text: "居住地区",
          type: "String",
        },
        address_detailed:{
          text: "详细居住地址",
          type: "String",
        },
        house_register_provinces:{
          text: "户籍所在省",
          type: "String",
        },
        house_register_city:{
          text: "户籍所在市",
          type: "String",
        },
        house_register_area:{
          text: "户籍所在区",
          type: "String",
        },
        house_register_type:{
          text: "户籍类型",
          type: "String",
        },
        china_member: {
          text: "社会面貌",
          type: "String",
        },
      },

      excelData: [],
      
      provinceData:[],
      addCityData:[],
      addAreaData:[],
      regCityData:[],
      regAreaData:[],

      loading: false,

      n: this.$route.query.n,
    }
  },
  created(){
    this.selProvince();
  },
  methods:{
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loadingn = true;
          let arr = Object.values(this.form);
          arr.push(new Date().getTime());
          let obj = {
            'id': this.form.userid,
            'name': this.form.teacher_name,
            'password': 123456,
            'phone': this.form.teacher_phone,
            'group': this.form.teacher_job,
            'school': this.form.school,
            'campus': this.form.campus,
            'wxid': "",
            "ddid": '',
            'created_user': this.form.created_user,
            'addTime': new Date().getTime(),
          }
          let arr2 = Object.values(obj);
          requestAjax({
            type: 'get',
            url: "/TeaManagement/EntryRegister/TeaRegister.php",
            data:{
              arr: arr,
              arr2: arr2,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res[0] && res[1]){
                this.$message({
                  message: '恭喜你，教师登记成功！',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "教师入职登记 "+ this.form.userid,
                  type: "添加记录",
                  model: "教师入职模块",
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
                if(!res[0] && res[1]){
                  this.$message({
                    message: '教师入职登记失败',
                    type: 'warning'
                  });
                }
                else if(!res[1] && res[0]){
                  this.$message({
                    message: '用户信息登记失败',
                    type: 'warning'
                  });
                }
                else{
                  this.$message.error('教师入职登记，用户信息登记失败!');
                }
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
    onBack(){
      this.$router.go(-1);
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

    
    // 采集 EXCEL 的数据
    async handle(ev){
      let file = ev.raw;
      if(!file) return false;

      // 读取FILE 中的数据
      let data = await readFile(file);    // 获取文件的二进制数据
      let workbook = xlsx.read(data,{ type: 'binary' });   // 通过xlsx 插件生成一个excel 目录

      // 拿到第一表的数据
      let worksheet = workbook.Sheets[workbook.SheetNames[0]]
      // 将表的是数据进行JSON格式化（插件自带的功能）
      data= xlsx.utils.sheet_to_json(worksheet);

      let arr = data.map((value,index,arr)=>{
        let obj = {};
        for(let key in this.character){
          if(!this.character.hasOwnProperty(key)) break;
          let v = this.character[key],
            text = v.text,
            type = v.type;
          v = value[text] || "";
          type === 'string' ? (v = String(v)) : null;
          type === 'number' ? (v = Number(v)) : null;
          obj[key] = v;
        }
        obj['department'] = "0";
        obj['school'] = this.$store.state.userSchool;
        obj['campus'] = this.$store.state.userCampus;
        
        obj['teacher_born'] = "";

        obj['type'] = "1";
        obj['msgSet'] = "1,1,1";
        obj['created_user'] = this.$store.state.userId;
        obj['addTime'] = new Date().getTime();
        return obj;
      });

      this.excelData = arr;
      // console.log(this.excelData);
    },
    // 上传服务器
    submitUpload(){
      if(this.excelData.length == 0){
        this.$message({
          message: '请上传具有实际意义的文件！',
          type: 'warning'
        });
      }else{
        requestAjax({
          type: 'post',
          url: "/TeaManagement/EntryRegister/ImpRegister.php",
          data:{
            arr: this.excelData,
          },
          async: false,
          success:(res)=>{
            res = JSON.parse(res);
            console.log(res);
            let arr = [];
            $.each(res, (i, v)=>{ 
              if(!v){
                arr.push((i+1));
              }
            });
            if(arr.length != 0){
              this.$message({
                message: '导入部分失败！请打开 F12 查看',
                type: 'warning'
              });
              let str = arr.join(',');
              console.log('数据第 ' + str + " 条添加失败");
            }else{
              this.$message({
                message: '数据导入成功!',
                type: 'success'
              });
              
              // 日志写入
              let obj = {
                content: "导入课程信息",
                type: "添加记录",
                model: "课程模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }
            this.$router.go(-1);
          },
          error:(err)=>{
            console.log(err);
            this.$notify.error({
              title: '错误',
              message: '服务器错误，请稍后再试!'
            });
          }
        })
      }
    },
    down(){
      location.href = this.$store.state.endUrl + "/TeaManagement/EntryRegister/教师导入模板.xlsx";
    },
    handleRemove(){
      this.excelData = [];
    }
  }
}
</script>

<style>

</style>