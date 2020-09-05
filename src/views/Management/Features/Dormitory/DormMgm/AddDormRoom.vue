<template>
  <div class="addDormRoom" v-loading="loading">
    <div class="pagehead">
      <h1>添加宿舍房间</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="80px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="dormroom_name" label="房间名称">
          <el-input style="width: 250px" v-model="sel_add.dormroom_name" placeholder="房间名称"></el-input>
        </el-form-item>
        <el-form-item prop="dormroom_bed_num" label="可用床位">
          <el-input style="width: 250px" v-model="sel_add.dormroom_bed_num" placeholder="可用床位"></el-input>
        </el-form-item>
        <el-form-item prop="dormroom_bed_num" label="宿舍楼">
          <el-select @change="getDormId" v-model="sel_add.build" placeholder="选择宿舍楼">
            <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="dormroom_id" label="房间编号">
          <el-input style="width: 250px" v-model="sel_add.dormroom_id" placeholder="输入房间编号"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onAdd('sel_add')">添加</el-button>
          <el-button size="small" @click="onBack">取消</el-button>
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
        dormroom_id: "",
        dormroom_name: "",
        dormroom_bed_num: "",
        build: "",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        status: '1',
        created_user: this.$store.state.userId,
      },
      rules:{
        dormroom_id: [
          { required: true, message: '请输入宿舍房间编码', trigger: 'blur' },
        ],
        dormroom_name: [
          { required: true, message: '请输入宿舍房间名称', trigger: 'blur' }
        ],
        dormroom_bed_num: [
          { required: true, message: '请输入宿舍房间床位数量', trigger: 'blur' }
        ],
        build: [
          { required: true, message: '请选择宿舍房间所在宿舍楼', trigger: 'change' }
        ],
      },
      loading: false,
    }
  },
  props:{
    buildData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          this.loading = true;
          requestAjax({
            url: "/dormitory/dormroom.php",
            type: 'get',
            data:{
              type: "add_dormroom",
              arr: arr,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '添加宿舍房间成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加宿舍 "+this.sel_add.dormroom_id,
                  type: "添加记录",
                  model: "宿舍模块",
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
    getDormId(v){
      this.loading = true;
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "get_dormroom_id",
          build: v,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.sel_add.dormroom_id = res;
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
    }
  }
}
</script>

<style>

</style>