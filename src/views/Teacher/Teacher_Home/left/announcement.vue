<template>
  <div class="announcement box">
    <el-row>
      <el-col :lg="24" class="head">
        <!-- <h4 class="por">今日待办<small class="poa">TO-DO Today</small></h4> -->
        <h4 class="por">校园通告<small class="poa">SCHOOL MESSAGE</small></h4>
      </el-col>
      <el-col :lg="24" v-if="show">
        <el-row>
          <el-col :lg="24" class="por acmItem">
            <el-row>
              <el-col :lg="16" :md="16" :xs="16">
                <p><a @click="toLink()" href="#">学校召开2019-2020学年第二学期教学工作专项会</a></p>
              </el-col>
              <p class="poa">20/04/02</p>
            </el-row>
          </el-col>
          <el-col :lg="24" class="por acmItem">
            <el-row>
              <el-col :lg="16" :md="16" :xs="16">
                <p><a @click="toLink()" href="#">学校召开2019-2020学年第二学期教学工作专项会</a></p>
              </el-col>              
              <p class="poa">20/04/02</p>
            </el-row>
          </el-col>
          <el-col :lg="24" class="por acmItem">
            <el-row>
              <el-col :lg="16" :md="16" :xs="16">
                <p><a @click="toLink()" href="#">学校召开2019-2020学年第二学期教学工作专项会</a></p>
              </el-col>
              <p class="poa">20/04/02</p>
            </el-row>
          </el-col>
          <el-col :lg="24" class="por acmItem">
            <el-row>
              <el-col :lg="16" :md="16" :xs="16">
                <p><a @click="toLink()" href="#">学校召开2019-2020学年第二学期教学工作专项会</a></p>
              </el-col>
              <p class="poa">20/04/02</p>
            </el-row>
          </el-col>
          <el-col :lg="24" class="more">
            <el-link :underline="false" href="/teacher/message" target="_blank">查看更多</el-link>
          </el-col>
          <!-- 无公告 -->
          <!-- <el-col :lg="24" class="nodata">
            <p>暂无公告</p>   
          </el-col> -->
        </el-row>
      </el-col>
      <!-- <el-col v-else :lg="24">
        <p class="nodata">取消展示</p>
      </el-col> -->
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
    news:{
      type: Array,
    },
    show:{
      type: Boolean,
      default: false,
    },
  },
  methods:{
    toLink(){
      this.$router.push("/teacher/message/xxxxx");
    }
  }
}
</script>

<style scoped>
  /* ------------announcement------------ */
  .announcement{
    height: 260px;
  }
  .acmItem{
    border-bottom: 1px solid #f5f5f5; 
    margin-bottom: 8px;
    position: relative;
  }
  .acmItem::before{
    content:"";
    display: block;
    width: 10px;
    height: 10px;
    position: absolute;
    left: 15px;
    top: 5px;
    background-color: #2ecc71;
  }
  .acmItem>div>div>p{
    text-align: left;
    font-size: 14px;
    overflow: hidden;
    color: #999;
    margin-bottom: 10px;
    text-overflow:ellipsis;
    white-space: nowrap;
    padding-left: 30px; 
  }
  .acmItem p:nth-child(1)>a{
    color: #666;
    text-decoration: none;;
  }
  .acmItem div div p>a:hover{
    color: #3498db;
  }
  .acmItem>div>p{
    right: 12px;
    text-align: right;
    font-size: 12px;
    transform: scale(0.9);
    top: 8px;
    color: #aaa;
  }
  .announcement .more{
    text-align: center;
    padding: 6px 0;
  }


</style>