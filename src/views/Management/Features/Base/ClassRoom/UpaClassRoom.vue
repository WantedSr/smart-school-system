<template>
  <div class="upaClassroom">
    <div class="pagehead">
      <h1>编辑教室信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="room_name" label="教室门牌号">
          <el-input style="width: 250px" v-model="sel_upa.room_name" placeholder="教室门牌号"></el-input>
        </el-form-item>
        <el-form-item prop="room_floor" label="楼层">
          <el-input type="number" style="width: 250px" v-model="sel_upa.room_floor" placeholder="楼层"></el-input>
        </el-form-item>
        <el-form-item prop="room_accommodate" label="容纳人数">
          <el-input type="number" style="width: 250px" v-model="sel_upa.room_accommodate" placeholder="容纳人数"></el-input>
        </el-form-item>
        <el-form-item prop="room_campus" label="所在校区">
          <el-select @change="selCampus" v-model="sel_upa.room_campus" placeholder="选择校区">
            <el-option v-for="(item,i) in campusData" :key="'c-'+i" :label="item.campus_name" :value="item.campus_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="room_department" label="所在部门">
          <el-select @change="selBuild" v-model="sel_upa.room_department" placeholder="选择部门">
            <el-option v-for="(item,i) in departmentData" :key="'d-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="room_build" label="所在教学楼">
          <el-select @change="getRoomId" v-model="sel_upa.room_build" placeholder="选择教学楼">
            <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="room_id" label="教室代码">
          <el-input style="width: 250px" v-model="sel_upa.room_id" placeholder="教室代码"></el-input>
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
  created(){
    this.getData("*","room_id",this.$route.query.roomId);
    this.$emit('getDep',this.sel_upa.room_campus);
    this.$emit('getBud',this.sel_upa.room_department,this.sel_upa.room_campus);
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
    getData(col="*",sel=null,val=null){
      requestAjax({
        url: "/base/classroom.php",
        type: 'get',
        data:{
          type: "sel_classroom",
          col: col,
          sel: sel,
          val: val,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.sel_upa = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
    },
    selCampus(v){
      this.sel_upa.room_department = '';
      this.sel_upa.room_build = '';
      this.$emit('getDep',v);
    },
    selBuild(v){
      this.sel_upa.room_build = '';
      this.$emit('getBud',v,this.sel_upa.room_campus);
    },
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            url: "/base/classroom.php",
            type: 'get',
            data:{
              type: "upa_classroom",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改教室信息成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改教室信息 "+ this.sel_add.room_id,
                  type: "修改记录",
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
              }else{
                this.$message.error('修改教室信息失败，请稍后再试！');
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
  }
}
</script>

<style>

</style>