<template>
  <div class="addClassroom">
    <div class="pagehead">
      <h1>添加教室信息</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="100px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="room_name" label="教室门牌号">
          <el-input style="width: 250px" v-model="sel_add.room_name" placeholder="教室门牌号"></el-input>
        </el-form-item>
        <el-form-item prop="room_floor" label="楼层">
          <el-input type="number" style="width: 250px" v-model="sel_add.room_floor" placeholder="楼层"></el-input>
        </el-form-item>
        <el-form-item prop="room_accommodate" label="容纳人数">
          <el-input type="number" style="width: 250px" v-model="sel_add.room_accommodate" placeholder="容纳人数"></el-input>
        </el-form-item>
        <el-form-item prop="room_campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel_add.room_campus" placeholder="选择校区">
            <el-option v-for="(item,i) in campusData" :key="'c-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="room_department" label="所在部门">
          <el-select @change="selBuild" v-model="sel_add.room_department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="room_build" label="所在教学楼">
          <el-select @change="getRoomId" v-model="sel_add.room_build" placeholder="选择教学楼">
            <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="room_id" label="教室代码">
          <el-input style="width: 250px" v-model="sel_add.room_id" placeholder="教室代码"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onAdd('sel_add')">添加</el-button>
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
      sel_add:{
        room_id: "",
        room_name: "",
        room_build: "",
        room_floor: "",
        room_accommodate: "",
        room_department: "",
        room_school: this.$store.state.userSchool,
        room_campus: "",
      },
      rules:{
        room_name: [
          { required: true, message: '请输入教室名称', trigger: 'blur' },
        ],
        room_id: [
          { required: true, message: '请输入教室代码', trigger: 'blur' }
        ],
        room_campus: [
          {required: true, message: '请选择教室所在校区', trigger: 'change' }
        ],
        room_department: [
          {required: true, message: '请选择教室所在部门', trigger: 'change' }
        ],
        room_build: [
          {required: true, message: '请选择教室所在教学楼', trigger: 'change' }
        ],
        room_accommodate: [
          {required: true, message: '请选择教室容纳人数', trigger: 'blur' }
        ],
        room_floor: [
          {required: true, message: '请选择专业所在楼层', trigger: 'blur' }
        ],
      },
    }
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
    buildData:{
      type: Array,
      require: true,
    },
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    selCampus(v){
      this.sel_add.room_department = '';
      this.sel_add.room_build = '';
      this.$emit('getDep',v);
    },
    selBuild(v){
      this.sel_add.room_build = '';
      this.$emit('getBud',v,this.sel_add.room_campus);
    },
    getRoomId(v){
      requestAjax({
        type: "get",
        url: "/base/classroom.php",
        data: {
          type: "get_classroom_id",
          build: v,
        },
        success:(res)=>{
          if(res == null || res == ''){
            this.sel_add.room_id = v+"_001"
          }else{
            res = parseInt(res) + 1;
            if(res<10){
              res = '00'+res;
            }else if(res<100){
              res = '0'+res;
            }
            this.sel_add.room_id = v+"_"+res;
          }
          // console.log(res);
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
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          requestAjax({
            type: "get",
            url: "/base/classroom.php",
            data: {
              type: "add_classroom",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '添加教室成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加教室信息 "+ this.sel_add.room_id,
                  type: "添加记录",
                  model: "教室模块",
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
  }
}
</script>

<style>

</style>