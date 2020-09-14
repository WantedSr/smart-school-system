<template>
  <div class="mywork box">
    <el-row>
      <el-col :lg="24" class="head">
        <h4 class="por">我的事项<small class="poa">My Work</small></h4>
      </el-col>
      <el-col class="mwList" :lg="24" v-if="show">
        <el-row>
          <el-col :span="8">
            <el-link :underline="false" type="success" @click="toToday">
              <h1>{{todoNum}}</h1>
              <p>今日待办</p>
            </el-link>
          </el-col>
          <el-col :span="8">
            <el-link :underline="false" type="warning" @click="toMessage">
              <h1>{{newTab}}</h1>
              <p>通知</p>
            </el-link>
          </el-col>
          <el-col :span="8">
            <el-link :underline="false" type="danger" @click="toMessage">
              <h1>{{holdSee}}</h1>
              <p>作业</p>
            </el-link>
          </el-col>
        </el-row>
      </el-col>
      <el-col :lg="24" v-else>
        <p class="nodata">取消展示</p>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  data(){
    return {

    }
  },
  props:{
    newTab:{
      type: Number,
      default: 0,
    },
    todoNum:{
      type: Number,
      default: 3,
    },
    holdSee:{
      type: Number,
      default: 0,
    },
    show:{
      type: Boolean,
      default: true,
    }
  },
  methods:{
    toMessage(){
      this.$router.push('/student/message');
    },
    toHomeWork(){
      this.$router.push('/student/homework');
    },
    toToday(){
      this.$router.push('/student/today');
    },
  }
}
</script>

<style scoped>
  /* ----------mywork---------- */
  .mywork{
    height: 150px;
  }
  .mwList>div>div{
    text-align: center;
    margin-bottom: 10px;
  }
  .mywork>div>div:nth-child(2)>h1{
    color: #2ecc71;
    margin-bottom: 5px;
  }
  .mywork>div>div:nth-child(3)>h1{
    color: #e74c3c;
    margin-bottom: 5px;
  }
  /* @media screen and (max-width:1170px){
    .mywork .el-row .el-row .el-col{
      margin: 16px 0;
    }
  } */
</style>