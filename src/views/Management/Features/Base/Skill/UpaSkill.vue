<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑专业信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="skill_id" label="专业代码">
          <el-input style="width: 250px" v-model="sel_upa.skill_id" placeholder="学期代码"></el-input>
        </el-form-item>
        <el-form-item prop="skill_name" label="专业名称">
          <el-input style="width: 250px" v-model="sel_upa.skill_name" placeholder="学期名称"></el-input>
        </el-form-item>
        <el-form-item prop="skill_campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel_upa.skill_campus" placeholder="选择校区">
            <el-option v-if="campusData.length != 0" label="所有校区" value="all"></el-option>
            <el-option v-for="(item,i) in campusData" :key="'1-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="skill_department" label="所在部门">
          <el-select v-model="sel_upa.skill_department" placeholder="选择部门">
            <el-option v-if="departmentData.length != 0" label="所有部门" value="all"></el-option>
            <el-option v-for="(item,i) in departmentData" :key="'2-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
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
      sel_upa:'',
      rules:{
        skill_name: [
          { required: true, message: '请输入专业名称', trigger: 'blur' },
        ],
        skill_id: [
          { required: true, message: '请选择专业代码', trigger: 'blur' }
        ],
        skill_campus: [
          {required: true, message: '请选择专业所在校区', trigger: 'change' }
        ],
        skill_department: [
          {required: true, message: '请选择专业所在部门', trigger: 'change' }
        ],
      },
      department: '',
    }
  },
  created(){
    this.getData();
    this.selCampus(this.sel_upa.skill_campus);
    this.sel_upa.skill_department = this.department;
  },
  props:{
    campusData:{
      type: Array,
      require: true,
    },
    departmentData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/profession.php",
            type: 'get',
            data:{
              type: "upa_profession",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改专业信息成功',
                  type: 'success'
                });
                
                // 日志写入
                let obj = {
                  content: "修改专业信息 "+ this.sel_upa.skill_id,
                  type: "修改记录",
                  model: "专业模块",
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
                this.$message.error('修改专业失败，请稍后再试！');
              }
            },
            error:(err)=>{
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
    getData(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/profession.php",
        type: 'get',
        data:{
          type: "sel_profession",
          sel: 'skill_id',
          val: this.$route.query.skillId,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.department = res['skill_department'];
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
    onBack(){
      this.$router.go(-1);
    },
    selCampus(v){
      this.sel_upa.skill_department = '';
      this.$emit('getDep',v);
    }
  }
}
</script>

<style>

</style>