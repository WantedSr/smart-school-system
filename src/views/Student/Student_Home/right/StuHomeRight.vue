<template>
  <div class="right">
    <div class="rightBox">
      <div class="userInfo box">
        <el-row>
          <el-col :lg="24">
            <div class="user por">
              <div class="myStrong poa">
                <a href="/student/growing" target="_blank"><p>我的成长档案&nbsp;&gt;</p></a>
              </div>
              <div class="userHead">
                <el-avatar :src="src" :size="120" icon="el-icon-user"></el-avatar>
              </div>
              <h1>{{userInfo.username}}</h1>
              <p>{{userInfo.class}}</p>
            </div>
            <!-- <div class="mine">
              <el-row>
                <el-col :lg="24" class="head">
                  <h4 class="por">我的标签<small class="poa">My Label</small></h4>
                </el-col>
                <el-col :lg="20" :offset="1" class="mylabel">
                  <el-tag class="label label-default">{{data.dpmentid}}</el-tag>
                  <el-tag type="success" class="label label-default">{{data.class}}</el-tag>
                  <el-tag type="danger" class="label label-default">{{age}}岁</el-tag>
                  <el-tag type="info" v-if="data.china_member == 1" class="label label-danger">团员</el-tag>
                  <el-tag type="warning" v-for="(item,i) in arr" :key="i" class="label label-success">{{item}}</el-tag>
                </el-col>
              </el-row>
            </div> -->
            <div class="model">
              <el-row>
                <el-col :lg="24" class="head">
                  <h4 class="por">常用功能<small class="poa">Common Functions</small></h4>
                </el-col>
                <el-col :lg="24" class="modelList">
                  <el-row>
                    <!-- 最多3个 -->
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/student/message')">
                        <div class="modelImg">
                          <i class="el-icon-s-order"></i>
                        </div>
                        <div class="modelText">
                          <p>我的通知</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/student/reward')">
                        <div class="modelImg">
                          <i class="el-icon-tickets"></i>
                        </div>
                        <div class="modelText">
                          <p>奖惩查询</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/student/certificate')">
                        <div class="modelImg">
                          <i class="el-icon-notebook-2"></i>
                        </div>
                        <div class="modelText">
                          <p>我的证书</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/student/graduation')">
                        <div class="modelImg">
                          <i class="el-icon-info"></i>
                        </div>
                        <div class="modelText">
                          <p>毕业审核</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/student/setting')">
                        <div class="modelImg">
                          <i class="el-icon-s-tools"></i>
                        </div>
                        <div class="modelText">
                          <p>个人设置</p>
                        </div>
                      </el-link>
                    </el-col>
                    <el-col class="modelItem" :lg="8" :sm="8" :xs="8" :md="8">
                      <el-link :underline="false" @click="toLink('/student/today')">
                        <div class="modelImg">
                          <i class="el-icon-s-claim"></i>
                        </div>
                        <div class="modelText">
                          <p>我的事务</p>
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
export default {
  data(){
    return {
      userInfo: "",
      src: this.$store.state.endUrl + "/userhead/" + this.$store.state.userHead,
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
    padding-top: 20px;
    padding-bottom: 10px;
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
    border: 1px solid #f0f0f0;
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
  .myStrong{
    right: -12px;
    top: 20px;;
    border-top-left-radius: 50px;
    border-bottom-left-radius: 50px;
    background-color: rgba(46, 204, 113,.8);
    padding: 6px 12px;
  }
  .myStrong a , .myStrong p{
    text-decoration: none;;
    color: #FFF;
    margin: 0;
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