<template>
  <div class="left">
    <div class="leftBox">

      <my-work :todoNum="todoNum" :show="work"></my-work>

      <today :todoData="todoData"></today>

      <announcement :show="announcement"></announcement>

    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
import MyWork from "./mywork";
import Announcement from "./announcement";
import Today from "./today";
export default {
  data(){
    return {
      show: JSON.parse(this.userData)['msgSet'].split(","),
      todoData: [],
      todoNum: 0,
    }
  },
  created(){
  },  
  computed:{
    work(){
      return this.show[0] == 1 ? true: false;
    },
    announcement(){
      return this.show[1] == 1 ? true: false;
    }
  },
  created(){
    this.getToDo();
  },
  methods:{
    getToDo(){
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
          date: new Date().setHours(0,0,0,0),
        },
        async: true,
        success:res=>{
          res = JSON.parse(res);
          if(res.data.length > 0){
            let data = res.data;
            this.todoNum = data.length;
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
    },
  },
  props:{
    userData:{
      type: String,
      require: true,
    }
  },
  components:{
    MyWork,
    Announcement,
    Today,
  }
}
</script>

<style>
  
</style>