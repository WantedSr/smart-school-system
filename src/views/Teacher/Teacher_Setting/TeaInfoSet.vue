<template>
  <div class="stuInfo">
    <el-form ref="form" v-loading="loading" :model="userInfo" label-width="80px">
      <el-form-item label="编号">
        <el-input disabled v-model="userInfo.userid"></el-input>
      </el-form-item>
      <el-form-item label="教师姓名">
        <el-input v-model="userInfo.username"></el-input>
      </el-form-item>
      <el-form-item label="电话号码">
        <el-input v-model="userInfo.teacher_phone"></el-input>
      </el-form-item>
      <el-form-item label="出生日期">
        <el-date-picker
          v-model="userInfo.teacher_born"
          type="date"
          placeholder="选择日期"
          format="yyyy-MM-dd"
          value-format="yyyyMMdd">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="身份证">
        <el-input v-model="userInfo.teacher_sfz"></el-input>
      </el-form-item>
      <el-form-item label="年龄">
        <el-input disabled type="age" v-model.number="age" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-radio-group v-model="userInfo.sex">
          <el-radio label="男"></el-radio>
          <el-radio label="女"></el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="部门">
        <el-input disabled v-model="userInfo.department"></el-input>
      </el-form-item>
      <el-form-item label="社会面貌">
        <el-input disabled v-model="userInfo.china_member"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">确认</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data() {
    return {
      userInfo: JSON.parse(this.userData),

      loading: false,
    }
  },
  props:{
    userData:{
      type: String,
      require: true,
    }
  },
  computed:{
    age(){
      let year = this.userInfo.teacher_born.substr(0,4);
      return new Date().getFullYear() - year;
    },
  },
  created(){
    // console.log(JSON.parse(this.userData));
  },
  methods: {
    onSubmit() {
      let token = localStorage.getItem("Authorization");
      // let evaluation = this.userInfo.evaluation;
      let obj = {
        sex: this.userInfo.sex,
        teacher_name: this.userInfo.teacher_name,
        teacher_sfz: this.userInfo.teacher_sfz,
        teacher_phone: this.userInfo.teacher_phone,
        teacher_born: this.userInfo.teacher_born,
      };
      this.loading = true;
      requestAjax({
        type: 'post',
        url: "/teaSetting.php",
        data:{
          type: "upaTea",
          token: token,
          obj: obj,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.loading = false;
          if(res){
            // console.log(res);
            this.$message({
              message: '恭喜你，修改成功！',
              type: 'success'
            });

            location.reload();
          }else{
            this.$message.error('修改失败，请稍后再试！');
          }
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
            this.$message.error('服务器有误，请稍后再试或联系网络管理员!');
        }
      })
    }
  }
}
</script>

<style>

</style>