<template>
  <div class="stuInfo" v-loading="loading">
    <el-form ref="form" :model="userInfo" label-width="80px">
      <el-form-item label="学号">
        <el-input disabled v-model="userInfo.userid"></el-input>
      </el-form-item>
      <el-form-item label="学生姓名">
        <el-input v-model="userInfo.username"></el-input>
      </el-form-item>
      <el-form-item
        label="年龄"
        :rules="[
          { required: true, message: '年龄不能为空'},
          { type: 'number', message: '年龄必须为数字值'}
        ]">
        <el-input disabled type="age" v-model.number="age" autocomplete="off"></el-input>
      </el-form-item>
      <el-form-item label="身份证">
        <el-input v-model="userInfo.user_ID"></el-input>
      </el-form-item>
      <el-form-item label="电话号码">
        <el-input v-model="userInfo.phone_me"></el-input>
      </el-form-item>
      <el-form-item label="性别">
        <el-radio-group
         v-model="userInfo.sex">
          <el-radio label="男">男</el-radio>
          <el-radio label="女">女</el-radio>
        </el-radio-group>
      </el-form-item>
      <el-form-item label="部门">
        <el-input disabled v-model="userInfo.department"></el-input>
      </el-form-item>
      <el-form-item label="班级">
        <el-input disabled v-model="userInfo.class"></el-input>
      </el-form-item>
      <el-form-item label="宿舍">
        <el-input disabled v-model="school_home"></el-input>
      </el-form-item>
      <el-form-item label="入学时间">
        <el-col :span="11">
          <el-date-picker disabled type="date" placeholder="选择日期" v-model="inSchool" style="width: 100%;"></el-date-picker>
        </el-col>
      </el-form-item>
      <el-form-item label="团员">
        <el-switch disabled v-model="chinaMember"></el-switch>
      </el-form-item>
      <el-form-item label="自我评价">
        <el-input type="textarea" v-model="userInfo.evaluation"></el-input>
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
      userInfo: JSON.parse(this.userdata),

      loading: false,
    }
  },
  props:{
    userdata:{
      type: String,
      require: true,
    }
  },
  computed:{
    inSchool(){
      let admission = this.userInfo.grade;
      return admission+'-09-01';
    },
    chinaMember(){
      return this.userInfo.china_member == 1 ? true : false;
    },
    age(){
      let born = this.userInfo.bornDate.substr(0,4);
      let y = new Date().getFullYear()
      return y - born;
    }
  },
  methods: {
    onSubmit() {
      let token = localStorage.getItem("Authorization");
      // let evaluation = this.userInfo.evaluation;
      let obj = {
        evaluation: this.userInfo.evaluation,
        sex: this.userInfo.sex,
        username: this.userInfo.username,
        user_ID: this.userInfo.user_ID,
        phone_me: this.userInfo.phone_me,
      };
      this.loading = true;
      requestAjax({
        type: 'post',
        url: "/stuSetting.php",
        data:{
          type: "upaStu",
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
          }else{
            this.$message.error('修改失败，请稍后再试！');
          }
        },
        error:(err)=>{
          console.log(err);
          this.loading = false;
          this.$message.error('服务器有误，请稍后再试或联系网络管理员!');
        }
      })
    }
  }
}
</script>

<style>

</style>