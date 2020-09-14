<template>
  <div class="today">
    <el-row :gutter="40">
      <el-col :lg="12">
        <stu-today @getDayToDo="getDayToDo"></stu-today>
      </el-col>
      <el-col class="hidden-sm-and-down" :lg="12">
        <stu-today-work :todoData="todoData" :loading="loading"></stu-today-work>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import StuToday from "./StuToday";
import StuTodayWork from './StuTodayWork';
export default {
  data(){
    return{
      todoData: [],

      loading: false,
    }
  },
  computed:{
    getData(){
      if(this.userData != ""){
        return true;
      }
      return false
    }
  },
  methods:{
    getDayToDo(day){
      // console.log(day);
      let date = new Date();
      let arr = day.split("-");
      date.setFullYear(parseInt(arr[0]));
      date.setMonth(parseInt(arr[1])-1);
      date.setDate(parseInt(arr[2]));
      this.todoData = [];
      this.loading = true;
      requestAjax({
        url: "/affairs.php",
        type: "post",
        data:{
          action: 'day',
          type: 'stu',
          cls: this.$store.state.userClass,
          dep: this.$store.state.userDepartment,
          userid: this.$store.state.userId,
          campus: this.$store.state.userCampus,
          date: date.setHours(0,0,0,0),
        },
        async: true,
        success:res=>{
          res = JSON.parse(res);
          if(res.data.length > 0){
            let data = res.data;
            if(data.length <= 3){
              this.todoData = res.data;
            }else{
              data.sort((a,b)=>parseInt(a.addTime) - parseInt(b.addTime));
              for(let i=0;i<3;i++){
                this.todoData.push(data[i]);
              }
            }
          }
          // console.log(this.todoData);
          this.loading = false;
        },
        error:err=>{
          console.error(err);
          this.loading = false;
          this.$notify.error({
            title: '服务器出错',
            message: '请求数据出现错误，请稍后再试或联系管理员',
          });
        }
      })
    }
  },
  created(){
    let token = localStorage.getItem("Authorization");
    requestAjax({
      type: "get",
      url: "/userStatus.php",
      data:{
        token: token,
        type: "student",
      },
      success:(res)=>{
        res = JSON.parse(res);
        if(res.code != 200){
          return this.error;
        }else{
          if(res == null || res == "" || res == "null"){
            this.$alert('用户数据有误，请检查LocalStorage是否冲突或被修改', '查询不到数据', {
              confirmButtonText: '确定',
              callback: action => {
                location.reload();
              }
            });
          }else{
            this.userData = JSON.stringify(res['info']);
          }
        }
      },
      error:(err)=>{
        console.log(err);
        this.$notify.error({
          title: '服务器出错',
          message: '请求数据出现错误，请稍后再试或联系管理员',
        });
      }
    });
  },
  components:{
    StuToday,
    StuTodayWork,
  }
}
</script>

<style>
  .today{
    width: 90%;
    margin: 0 auto;
  }
</style>