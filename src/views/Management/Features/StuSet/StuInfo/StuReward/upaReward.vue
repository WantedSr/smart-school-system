<template>
  <div class="addDormRoom">
    <div class="pagehead">
      <h1>编辑奖惩信息</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="120px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="reward_id" label="奖惩项目代码">
          <el-input style="width: 250px" v-model="sel_upa.reward_id" placeholder="奖惩项目代码"></el-input>
        </el-form-item>
        <el-form-item prop="reward_name" label="奖惩项目名称">
          <el-input style="width: 250px" v-model="sel_upa.reward_name" placeholder="奖惩项目名称"></el-input>
        </el-form-item>
        <el-form-item prop="reward_score" label="奖惩项目分值">
          <el-input type="number" style="width: 250px" v-model="sel_upa.reward_score" placeholder="奖惩项目分值"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button type="success" @click="onUpa('sel_upa')">编辑</el-button>
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
      sel_upa:{
        reward_id: "",
        reward_name: "",
        reward_score: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId,
      },
      rules:{
        reward_name: [
          { required: true, message: '请输入奖惩项目名称', trigger: 'blur' },
        ],
        reward_id: [
          { required: true, message: '请选择奖惩项目代码', trigger: 'blur' }
        ],
        reward_score: [
          { required: true, message: '请选择奖惩项目分值', trigger: 'blur' }
        ],
      }
    }
  },
  created(){
    requestAjax({
      type: "get",
      url: "/StuManagement/StuInfo/rewardType.php",
      data:{
        type: 'sel_reward',
        selobj: {
          'reward_id':this.$route.query.rewardId,
        }
      },
      success:(res)=>{
        res = JSON.parse(res);
        this.sel_upa = res[0];
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
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          requestAjax({
            type: "get",
            url: "/StuManagement/StuInfo/rewardType.php",
            data: {
              type: "upa_reward",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '恭喜你，修改奖惩项目成功',
                  type: 'success'
                });
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
  }
}
</script>

<style>

</style>