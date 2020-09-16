<template>
  <div id="login" @click="noneewm" class="poa flex" @keyup.enter="tologin">
    <div class="wrap">
      <h1><img src="~assets/images/school_logo.png" alt="no_logo"><span>安丘职业中等专业学校</span></h1>
      <div id="login_frame">
        <h1>智慧校园管理系统</h1>
        <form method="post" > 
          <div>
            <input type="text" required id="userPhone" name="userPhone" @blur="getUser" class="text_field" placeholder="请输入手机号"/>
          </div>
          <div><input type="text" required placeholder="请输入用户名" id="username" name="username" class="text_field"/></div>
          <div><input type="password" required id="password" class="text_field" placeholder="请输入密码"/></div>
          <div><input type="hidden" required id="usergroup" class="text_field" /></div>
          <div id="login_control">
            <input type="button" @click="login" id="btn_login" value="登录"/>
          </div>
          <div id="three_id">
            <!-- <img id="wx_img" src="~assets/images/wx1.png" @click="wxewmshow"> -->
            <!-- <img id="dd_img" src="~assets/images/dd.png" @click="ddewmshow"> -->
            <!-- <a id="forget_pwd" href="./loginzc/register.php">忘记密码</a> -->
          </div>
        </form>
      </div>
      <div id="wx_ewm" v-if="ewmshow">
        <img src="~assets/images/1.png" id="img_threeewm">
      </div>
    </div>

    <ul>
      <li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li><li></li>
    </ul>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
import $ from "jquery";
export default {
  data(){
    return{
      ewm: $("#wx_ewm"),
      ewmimg: $("#img_threewem"),
      ewmshow: false,
      oldshow: "",
    }
  },
  created(){
    
  },
  methods:{
    getUser(){
      let $userPhone = $("#userPhone").val();
      requestAjax({
        url: "/login.check.php",
        data:{
          'userPhone':$userPhone
        },
        type: "post",
        success:(res)=>{
          //此处处理后台返回的数据
          if(res == null || res == ""){
            return false;
          }
          res = JSON.parse(res);
          if(res.length > 0){
            $("#username").val(res[0]['user_id']);
            $("#usergroup").val(res[0]['user_group']);
          }else{
            return 
          }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器出错，请稍后再试！',
            duration: 0,
          });
        },
      })
    },
    tologin(){
      return this.login();
    },
    login(){
      let $userPhone = $("#userPhone").val();
      let $userid = $("#username").val();
      let $password = $("#password").val();
      if($userPhone==undefined||$userPhone==''){
        alert("手机号不能为空");
        return;
      }
      if($password==undefined||$password==''){
        alert("密码不能为空");
        return;
      }
      if($userid==undefined||$userid==''){
        alert("用户名不能为空");
        return;
      }
      // //将请求提交到后台
      requestAjax({
        url: "/login.check.php",
        type: 'post',
        data:{
          "userPhone":$userPhone,
          "userid":$userid,
          "password":$password
        },
        async: false,
        success:(res)=>{
          if(res == null || res == ""){
            this.$message.error('用户名或密码或手机号错误，请重试!');
            return false;
          }
          // console.log(res);
          let msg = JSON.parse(res)[1];
          res = JSON.parse(res)[0];
          this.$store.commit("setToken",msg);
          this.$store.commit("handleUsername",res['user_name']);
          this.$store.commit("getUser");
          let $group = res['user_group'];

          // 日志写入
          let obj = {
            content: $group.indexOf("S") != -1 ? "学生登录" : $group.indexOf("T") != -1 ? "教师登录" : "管理员登录",
            type: "登录记录",
            model: "登录模块",
            ip: sessionStorage.getItem('ip'),
            user: this.$store.state.userId,
            area: sessionStorage.getItem("area"),
            brower: sysTool.GetCurrentBrowser(),
            addTime: new Date().getTime(),
          }
          let arr = Object.values(obj);
          requestAjax({
            type: "get",
            url: "/system/systemLog.php",
            data:{
              type: 'add_log',
              arr: arr,
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                return;
              }else{
                console.log("日志填写失败！");
              }
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器出错，请稍后再试！',
                duration: 0,
              });
            }
          });

          // console.log($group);
          this.$message({
            message: '恭喜你，登陆成功',
            type: 'success',
            showClose: true,
          });
          // 对后台返回信息进行处理
          if($group.indexOf("D") !== -1){
            this.$router.replace("/teacher/home");
          }else if($group.indexOf("T") !== -1){
            this.$router.replace("/teacher/home");
          }else if($group.indexOf("S") !== -1){
            this.$router.replace("/student/home");
          }
        },
        error(err){
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器出错，请稍后再试！',
            duration: 0,
          });
        }
      });
    },
    // 二维码操作
    noneewm(){
      this.ewmshow = false;
      this.oldshow = "";
    },
    wxewmshow() {
      if(this.oldshow == "wx"){
        this.ewmshow = false;
        this.oldshow = "";
      }else{
        this.ewmshow = true;
        this.oldshow = 'wx';
        this.ewmimg.title = '微信';
      }
      window.event? window.event.cancelBubble = true : e.stopPropagation();
    },
    ddewmshow() {
      if(this.oldshow == "dd"){
        this.ewmshow = false;
        this.oldshow = "";
      }else{
        this.ewmshow = true;
        this.oldshow = 'dd';
        this.ewmimg.title = '钉钉';
      }
      window.event? window.event.cancelBubble = true : e.stopPropagation();
    }
  }
}
</script>

<style>

  #login{
    width: 100%;
    height: 100%;
    position: absolute;
    overflow: hidden;
  }
  #login::before{
    background-color: rgba(255, 255, 255,.5);
    content:"";
    display: block;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;;;
  }
  #login::after{
    content:"";
    display: block;
    width: 100%;
    height: 100%;
    background-image: url("~assets/images/aqzjxxbjt.jpg");
    -moz-background-size:100% 100%;
    background-size:100% 100%;
    background-repeat: no-repeat;
    -webkit-filter: blur(5px);
    -moz-filter: blur(5px);
    -o-filter: blur(5px);
    -ms-filter: blur(5px);
    filter: blur(5px);
    z-index: -2;;
    position: absolute;
    top: 0;
    left: 0;
    opacity: .3;
  }
  .wrap {
      width: 90%;
      height: 568px;
      position: relative;
      /* left: 50%;
      transform: translateX(-50%); */
      max-width: 1200px;
      padding: 40px;
      /* top: 8%; */
      border-radius: 20px;
      border: 2px solid #f0f0f0;
      background-position: -300px 0;
      opacity: 1;
      background-image: url("~assets/images/aqzjxxbjt.jpg");
      -moz-background-size:1280px auto;
      background-size:1280px auto;
      background-repeat: no-repeat;
      /* position: fixed; */
      /* margin-top: -200px; */
      /*background-position: right top;*/
      /*background-position: right bottom, left top;*/
      /*background: linear-gradient(to bottom right,#50a3a2,#53e3a6);*/
      /*background: -webkit-linear-gradient(to bottom right,#50a3a2,#53e3a6);*/
      
      overflow: hidden;
      box-shadow: 0 5px 5px rgba(128,128,128,.2);
      z-index: 2;
  }


  .wrap > h1{
    font-size: 20px;
    margin-top: -6px;
    margin-bottom: 0;
    color: #FFF;;
    font-weight: 400;
    position: relative;
  }
  .wrap>h1>span{
    opacity: .8;
    margin-left: 35px;
  }
  .wrap>h1>img{
    height: 30px;
    position: absolute;
  }

  .label_input {
      font-size: 14px;
      width: 65px;
      height: 28px;
      line-height: 28px;
      text-align: left;
      color: #0f0f0f;
      /*background-color: #00a58c;*/
      background-color:rgba(255,255,255,0.5);
      /*3CD8FF  rgba(240, 255, 255, 0.5)  F0FFFF*/
      border-top-left-radius: 5px;
      border-bottom-left-radius: 5px;
  }

  .text_field {
      width: 278px;
      height: 28px;
      line-height: 20px;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
      border: 0;
      margin-bottom: 20px;
      padding: 6px;
      outline: none;
      border-bottom: 1px solid #ddd;
  }

  #btn_login {
      font-size: 14px;
      width: 270px;
      padding: 8px 0;
      text-align: center;
      color: white;
      background-color: #3BD9FF;
      border-radius: 6px;
      border: 0;
      margin-top: 50px;
  }
  #btn_ewm{
      font-size: 14px;
      width: 120px;
      height: 28px;
      line-height: 28px;
      text-align: center;
      color: white;
      background-color: #3BD9FF;
      border-radius: 6px;
      border: 0;
      float: left;
  }

  #login_frame {
      width: 350px;
      height: 290px;
      padding: 13px;
      position: absolute;
      /* left: 70%; */
      right: -10px;
      top: 8%;
      /* margin-left: -200px;
      margin-top: -200px; */
      /*background-color: #00a58c;*/
      /* background-color:rgba(255,255,255,0.5);
      border-radius: 10px; */
      text-align: center;
      z-index: 3;;
  }

  #login_frame::after,#login_frame::before{
    content:"";
    display: block;
    position: absolute;;
    z-index: -1;;
  }
  #login_frame::after{
    width: 900px;
    height: 900px;
    top: -100%;
    right: -140%;;
    transform: rotate(60deg);
    background-color: rgba(255,255,255,.9);
  }
  #login_frame::before{
    width: 1000px;
    height: 1000px;
    top: -100%;
    right: -170%;;
    transform: rotate(12deg);
    background-color: rgba(255,255,255,.9);
  }


  #login_frame h1{
    font-size: 33px;
    font-weight: 500;
    color: #333;
    margin-bottom: 50px;
  }


  #three_id{
      float:left;
      width:100%;
      padding: 0 30px;
  }
  #three_id img{
      float: left;
      width: 30px;
      margin-top: 10px;
      height: 30px;
      margin-right: 10px;


  }
  #ewm{
      width: 180px;
      height: 160px;
      margin-left: 10px;
      /*padding:10px;*/
      position: absolute;
      left: 85%;
      top: 28%;
      text-align: center;
  }
  #ewm img{
      padding-right: 0px;
  }
  #wx_ewm{
      width: 180px;
      height: 160px;
      margin-left: 10px;
      /*padding:10px;*/
      position: absolute;
      left: 66%;
      top: 42%;
      text-align: center;
      z-index: 9;
  }
  #forget_pwd {
      height: 30px;
      text-align: right;
      display: block;
      margin-top: 10px;
      float: right;;
      text-decoration: none;
  }
  #login_control {
      /* width: 400px; */
  }

  .container {
      width: 60%;
      margin: 0 auto;
  }
  .container h1 {
      text-align: center;
      color: #FFFFFF;
      font-weight: 500;
  }
  #login ul {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
  }
  #login ul li {
      list-style-type: none;
      display: block;
      position: absolute;
      bottom: -120px;
      width: 15px;
      height: 15px;
      z-index: 3;
      background-color:rgba(52, 152, 219,.3);
      animotion: square 20s infinite;
      -webkit-animation: square 20s infinite;
  }
  #login ul li:nth-child(1) {
      left:45%;
      animation-duration: 10s;
      -moz-animation-duration: 10s;
      -o-animation-duration: 10s;
      -webkit-animation-duration: 10s;
  }
  #login ul li:nth-child(2) {
      width: 80px;
      height: 80px;
      left: 6%;
      animation-duration: 15s;
      -moz-animation-duration: 15s;
      -o-animation-duration: 15s;
      -webkit-animation-duration: 15s;
  }
  #login ul li:nth-child(3) {
      left: 20%;
      width: 25px;
      height: 25px;
      animation-duration: 12s;
      -moz-animation-duration: 12s;
      -o-animation-duration: 12s;
      -webkit-animation-duration: 12s;
  }
  #login ul li:nth-child(4) {
      width: 50px;
      height: 50px;
      left: 30%;
      -webkit-animation-delay: 3s;
      -moz-animation-delay: 3s;
      -o-animation-delay: 3s;
      animation-delay: 3s;
      animation-duration: 12s;
      -moz-animation-duration: 12s;
      -o-animation-duration: 12s;
      -webkit-animation-duration: 12s;
  }
  #login ul li:nth-child(5) {
      width: 60px;
      height: 60px;
      left: 40%;
      animation-duration: 10s;
      -moz-animation-duration: 10s;
      -o-animation-duration: 10s;
      -webkit-animation-duration: 10s;
  }
  #login ul li:nth-child(6) {
      width: 75px;
      height: 75px;
      left: 50%;
      -webkit-animation-delay: 7s;
      -moz-animation-delay: 7s;
      -o-animation-delay: 7s;
      animation-delay: 7s;
  }
  #login ul li:nth-child(7) {
      left: 60%;
      animation-duration: 8s;
      -moz-animation-duration: 8s;
      -o-animation-duration: 8s;
      -webkit-animation-duration: 8s;
  }
  #login ul li:nth-child(8) {
      width: 90px;
      height: 90px;
      left: 70%;
      -webkit-animation-delay: 4s;
      -moz-animation-delay: 4s;
      -o-animation-delay: 4s;
      animation-delay: 4s;
  }
  #login ul li:nth-child(9) {
      width: 100px;
      height: 100px;
      left: 80%;
      animation-duration: 20s;
      -moz-animation-duration: 20s;
      -o-animation-duration: 20s;
      -webkit-animation-duration: 20s;
  }
  #login ul li:nth-child(10) {
      width: 120px;
      height: 120px;
      left: 90%;
      -webkit-animation-delay: 6s;
      -moz-animation-delay: 6s;
      -o-animation-delay: 6s;
      animation-delay: 6s;
      animation-duration: 30s;
      -moz-animation-duration: 30s;
      -o-animation-duration: 30s;
      -webkit-animation-duration: 30s;
  }

  @keyframes square {
      0%  {
          -webkit-transform: translateY(0);
          transform: translateY(0)
      }
      100% {
          bottom: 800px;
          transform: rotate(600deg);
          -webit-transform: rotate(600deg);
          -webkit-transform: translateY(-800);
          transform: translateY(-800)
      }
  }
  @-webkit-keyframes square {
      0%  {
          -webkit-transform: translateY(0);
          transform: translateY(0)
      }
      100% {
          bottom: 800px;
          transform: rotate(600deg);
          -webit-transform: rotate(600deg);
          -webkit-transform: translateY(-800);
          transform: translateY(-800)
      }
  }


  @media screen and (max-width: 992px){
    #login,html{
      overflow: unset;
    }
    .wrap {
      height: auto;
      width: 500px;
      background-image: none;
      padding: 0;
    }
    .wrap > h1{
      font-size: 18px;
      display: none;-webkit-text-size-adjust: none;
    }
    #login_frame {
      width: 100%;
      height: auto;
      position: relative;
      left: 0;
      top: 0;
      background-color: rgba(255, 255, 255,1);
      box-shadow: 0 5px 12px rgba(128,128,128,.5);
      border-radius: 20px;
    }
    #login_frame::before,#login_frame::after{
      display: none;
    }
    #login_frame > h1{
      font-size: 24px;
    }
    #three_id{
      float: none;
      width: 270px;
      margin: 10px auto;
      padding: 0;
    }
    #three_id::after{
      content:"";
      display: block;
      width: 0;
      height: 0;
      clear: both;
      visibility: hidden;
    }
    .text_field{
      width: 90%;
      font-size: 14px;
      height: auto;
      border-top-right-radius: 5px;
      border-bottom-right-radius: 5px;
      border: 0;
      margin-bottom: 6px;
      padding: 4px;
      outline: none;
      border-bottom: 1px solid #ddd;
    }
    #login>ul{
      display: none;
    }
  } 
  @media screen and (max-width: 600px){
    .wrap{
      width: 90%; 
    } 
    #three_id{
      width: 100%;
    }
    #btn_login{
      width: 100%;
    }

  } 



  .copyright{
    position: absolute;
    font-size: 14px;
    width: 100%;
    text-align: center;
    left: 0;
    right: 0;
    bottom: 20px;;
  }
  .copyright>p{
    color: #999;
  }
  .copyright>p>span{
    color: #3498db;
  }



</style>