<template>
  <div class="subpage">
    
    <div class="semesterArrangement_add">
      <div class="pagehead">
        <h1>修改学期宿舍安排</h1>
      </div>
      
      <div class="sel_upa">
        <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="80px" :model="sel_upa" class="demo-form-inline">
          <el-form-item prop="semester" label="学期">
            <el-select v-model="sel_upa.semester" placeholder="选择学期">
              <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="build" label="宿舍楼">
            <el-select @change="getDormroom" v-model="sel_upa.build" placeholder="选择宿舍楼">
              <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="dormroom_id" label="宿舍">
            <el-select v-model="sel_upa.dormroom_id" placeholder="选择宿舍">
              <el-option v-for="(item,i) in dormroomData" :key="'d-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item prop="housemaster" label="宿管">
            <el-select v-model="sel_upa.housemaster" placeholder="选择宿管">
              <el-option v-for="(item,i) in HouseMasterData" :key="'h-'+i" :label="item.teacher_name" :value="item.userid"></el-option>
            </el-select>
          </el-form-item>
          <el-form-item label="">
            <el-button type="success" @click="onUpa('sel_upa')">修改</el-button>
            <el-button @click="onBack">取消</el-button>
          </el-form-item>
        </el-form>
      </div>
      
    </div>  
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      dormroomData: [],
      HouseMasterData: [],
      sel_upa:{},
      rules:{
        semester: [
          { required: true, message: '请输选择学期', trigger: 'change' },
        ],
        build: [
          { required: true, message: '请选择宿舍楼', trigger: 'change' }
        ],
        dormroom_id: [
          { required: true, message: '请选择宿舍房间', trigger: 'change' }
        ],
        housemaster: [
          { required: true, message: '请选择管理宿管', trigger: 'change' }
        ],
      },
    }
  },
  props:{
    semesterData:{
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
  created(){
    this.getData();
    let dormid = this.sel_upa.dormroom_id;
    this.selDormbuild('all'); 
    this.getHouseMaster();
    this.getDormroom(this.sel_upa.build);
    this.sel_upa.dormroom_id = dormid;
    console.log(this.sel_upa);
  },
  methods:{
    getData(){
      requestAjax({
        url: "/dormitory/housemaster.php",
        type: 'get',
        data:{
          type: "sel_housemaster",
          selobj: {
            "id":this.$route.query.dormId,
          },
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.sel_upa = res;
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
    onBack(){
      this.$router.go(-1);
    },
    getDormroom(build){
      this.sel_upa.dormroom_id = '';
      this.loading = true;
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_dormroom",
          col: "*",
          selobj: {
            build: build,
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.dormroomData = res;
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
    getHouseMaster(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaLibrary.php",
        data:{
          type: "sel_tea",
          selobj: {
            'teacher_job': "T3",
            'campus': this.$store.state.userCampus,
          },
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          this.HouseMasterData = res; 
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
    selDormbuild(dep){
      this.$emit("getDormBuild",dep);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/dormitory/housemaster.php",
            type: 'get',
            data:{
              type: "upa_housemaster",
              obj: this.sel_upa,
              sel: 'id',
              val: this.sel_upa.id,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '修改学期宿管安排成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改宿管管理安排 "+this.sel_upa.dormroom_id,
                  type: "修改记录",
                  model: "宿舍安排模块",
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

<style>

</style>