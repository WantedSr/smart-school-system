<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>修改校区信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="120px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="campus_name" label="校区名称">
          <el-input style="width: 250px" v-model="sel_upa.campus_name" placeholder="校区名称"></el-input>
        </el-form-item>
        <el-form-item prop="campus_school" label="所在学校">
          <el-select @change="getCampusId" v-model="sel_upa.campus_school" placeholder="请选择所在学校">
            <el-option v-for="(item,i) in schoolData" :key="i" :label="item.school_name" :value="item.school_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="provinceData != ''" label="校区位置-省">
          <el-select @change="selCity" v-model="province" placeholder="请选择省份">
            <el-option v-for="(item,i) in provinceData" :key="'1-'+i" :label="item.province" :value="item.provinceid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="province != '' && cityData != ''" label="校区位置-市">
          <el-select @change="selArea" v-model="city" placeholder="请选择市">
            <el-option v-for="(item,i) in cityData" :key="'2-'+i" :label="item.city" :value="item.cityid"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="campus_position" v-if="city != '' && areaData != ''" label="校区位置-区/县">
          <el-select v-model="sel_upa.campus_position" placeholder="请选择区/县">
            <el-option v-for="(item,i) in areaData" :key="'3-'+i" :label="item.area" :value="item.areaid"></el-option>  
          </el-select>
        </el-form-item>
        <el-form-item prop="campus_id" label="校区代码">
          <el-input style="width: 250px" v-model="sel_upa.campus_id" placeholder="年级代码"></el-input>
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
      sel_upa:{
        campus_id: "",
        campus_name: "",
        campus_school: "",
        campus_position: "",
      },
      rules:{
        campus_name: [
          { required: true, message: '请输入校区名称', trigger: 'blur' },
        ],
        campus_id: [
          { required: true, message: '请选择校区代码', trigger: 'blur' }
        ],
        campus_school: [
          {required: true, message: '请选择所在学校', trigger: 'change' }
        ],
        campus_position: [
          {required: true, message: '请选择所处区/县', trigger: 'change' }
        ],
      },
      province: "",
      provinceData: "",
      city: "",
      cityData: "",
      areaData: "",
      campusId: this.$route.query.campusId,
    }
  },
  props:{
    schoolData:{
      type: Array,
      require: true,
    }
  },
  created(){
    this.getData("*","campus_id",this.campusId);
    this.selProvince();
    this.selCity(this.province);
    this.selArea(this.city);
    return;
  },
  methods:{
    getData(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus", 
          col: col,
          sel: sel,
          val: val,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.province = res['campus_position'].substr(0,2)+'0000';
          this.city = res['campus_position'].substr(0,4)+'00';
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
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if(this.province == '' || this.city == ''){
            this.$message.error('请选择校区位置！');
            return false
          }
          requestAjax({
            type: "get",
            url: "/base/campus.php",
            data: {
              type: "upa_campus",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改校区成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改校区信息 "+ this.sel_upa.campus_id,
                  type: "修改记录",
                  model: "校区模块",
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
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
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
    selCity(v){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_cities",
          provinceId: v,
        },
        success:(res)=>{
          this.cityData = JSON.parse(res);
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
    selArea(v){
      requestAjax({
        type: 'get',
        url: "/chinaPosition.php",
        data:{
          type: "get_areas",
          cityId: v,
        },
        success:(res)=>{
          this.areaData = JSON.parse(res);
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
    getCampusId(v){
      requestAjax({
        url: "/base/campus.php",
        type: 'get',
        data:{
          type: "sel_campus", 
          col: "COUNT(*)",
          sel: "campus_school",
          val: v,
        },
        async: true,
        success:(res)=>{
          let letter = ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
          res = parseInt(JSON.parse(res)[0]['COUNT(*)']);
          let id = v+'_'+letter[res];
          this.sel_upa.campus_id = id;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    }
  }
}
</script>

<style>

</style>