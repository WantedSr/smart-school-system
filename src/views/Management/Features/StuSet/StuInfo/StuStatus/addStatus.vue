<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>添加学籍状态</h1>
    </div>
    
    <div class="sel_add">
      <el-form label-position="left" ref="sel_add" :rules="rules" label-width="120px" :model="sel_add" class="demo-form-inline">
        <el-form-item prop="status_id" label="状态代码">
          <el-input style="width: 250px" v-model="sel_add.status_id" placeholder="状态代码"></el-input>
        </el-form-item>
        <el-form-item prop="status_name" label="状态名称">
          <el-input style="width: 250px" v-model="sel_add.status_name" placeholder="状态名称"></el-input>
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
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel_add:{
        status_id: "",
        status_name: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId,
      },
      rules:{
        status_name: [
          { required: true, message: '请输入奖惩项目名称', trigger: 'blur' },
        ],
        status_id: [
          { required: true, message: '请选择奖惩项目代码', trigger: 'blur' }
        ],
      }
    }
  },
  created(){
    requestAjax({
      type: "get",
      url: "/StuManagement/StuInfo/schoolStatus.php",
      data:{
        type: 'get_reward_id',
      },
      success:(res)=>{
        res = JSON.parse(res);
        this.sel_add.status_id = "T"+(parseInt(res)+1);
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
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onAdd(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          let arr = Object.values(this.sel_add);
          arr.push(new Date().getTime());
          requestAjax({
            type: "get",
            url: "/StuManagement/StuInfo/schoolStatus.php",
            data: {
              type: "add_status",
              arr: arr,
            },
            success:(res)=>{
              if(res){
                console.log(res);
                this.$message({
                  message: '恭喜你，添加学籍状态成功',
                  type: 'success'
                });
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