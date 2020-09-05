<template>
  <div class="right" v-loading="loading">
    <div class="rightBox">
      <div class="userInfo box">
        <el-row>
          <el-col :lg="24">
            <div class="user por">
              <div class="userHead">
                <el-avatar :src="src" :size="120" icon="el-icon-user"></el-avatar>
              </div>
              <h1>{{userInfo.username}}&nbsp;老师</h1>
              <p>{{userInfo.department}}</p>
            </div>
            <div class="model">
              <el-row>
                <el-col :lg="24" class="head">
                  <h4 class="por">常用功能<small class="poa">Common Functions</small></h4>
                </el-col>
                <el-col :lg="24" class="modelList">
                  <el-row>
                    <!-- 最多3个 -->
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/teacher/message')">
                        <div class="modelImg">
                          <i class="el-icon-s-order"></i>
                        </div>
                        <div class="modelText">
                          <p>我的通知</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/teacher/setting')">
                        <div class="modelImg">
                          <i class="el-icon-s-tools"></i>
                        </div>
                        <div class="modelText">
                          <p>个人设置</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/teacher/today')">
                        <div class="modelImg">
                          <i class="el-icon-s-claim"></i>
                        </div>
                        <div class="modelText">
                          <p>我的事务</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" href='/management' target="_blank">
                        <div class="modelImg">
                          <i class="el-icon-s-cooperation"></i>
                        </div>
                        <div class="modelText">
                          <p>后台操作</p>
                        </div>
                      </el-link>
                    </el-col>

                  </el-row>
                </el-col>
              </el-row>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      src: 'https://cube.elemecdn.com/6/94/4d3ea53c084bad6931a56d5158a48jpeg.jpeg',
      // src: "",
      userInfo: "",
      loading: false,
    }
  },
  computed:{
    twoname(){
      if(this.username != ""){
        return this.username.substr(-2,2);
      }
    },
    age(){
      let born = "20" + this.data.bornDate.substr(0,2);
      let age = new Date().getFullYear() - parseInt(born);
      return age;
    },
    arr(){
      let arr = this.data.nature.split(',');
      let newarr = [];
      $.each(arr, function (i, v) { 
        if(v != "") newarr.push(v);
      });
      return newarr;
    }
  },
  created(){
    this.userInfo = JSON.parse(this.userData);
  },
  props:{
    userData:{
      type: String,
      require: true,
    }
  },
  methods:{
    toLink(link){
      this.$router.push(link);
    }
  }
}
</script>

<style>

  /* ------------------------------------------right----------------------------------- */
  .userInfo{
    height: 755px;
  }
  .user{
    text-align: center;
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 10px;
  }
  .userHead{
    width: 120px;
    height: 120px;
    border-radius: 50%;
    color: #FFF;
    text-align: center;
    margin: 30px auto 0;;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .userHead i{
    font-size: 40px;
    position: relative;
    top: 10px;
  }
  .user h1{
    color: #333;
    font-size: 20px;
    margin-top: 10px;
    margin-bottom: 5px;
  }
  .user p{
    color: #999;
    font-size: 16px;
  }

  /* ------------------------mine------------------------------------ */
  .mine{
    padding-bottom: 20px;
    border-bottom: 1px solid #f0f0f0;
    margin-bottom: 20px;
  }
  .mylabel>span{
    font-size: 16px;
    display: inline-block;
    margin-bottom: 5px;
    margin-right: 6px;
  }





  /* -------------------model------------------------ */
  .model .head a{
    color: #2ecc71;
    text-decoration: none;
  }
  .modelList{
    
  }
  .modelItem{
    text-align: center;
    margin-bottom: 20px;
  }
  .modelItem a{
    text-decoration: none;
  }
  .modelImg{
    width: 50px;
    height: 50px;
    margin: 0 auto;
    margin-bottom: 10px;
    background-color: #3498db;
    border-radius: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .modelImg i{
    color: #FFF;
    font-size: 24px;
  }
  /* -------------------------------------myTime--------------------------------- */
  .mine .myTime{
    text-align: center;
  }
  .myTime>div h1{
    font-size: 20px;
  }
  .myTime>div>div:nth-child(1)>h1{
    color: #2ecc71;
  }
  .myTime>div>div:nth-child(2)>h1{
    color: #3498db;
  }

</style>