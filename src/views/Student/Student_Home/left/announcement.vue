<template>
  <div class="announcement box">
    <el-row>
      <el-col :lg="24" class="head">
        <!-- <h4 class="por">今日待办<small class="poa">TO-DO Today</small></h4> -->
        <h4 class="por">校园通告<small class="poa"><el-link :underline="false">更多</el-link></small></h4>
      </el-col>
      <el-col :lg="24" v-if="show">
        <el-row>
          <el-col v-for="(item,i) in msgData" :key="'msg-'+i" :lg="24" :class="{'por':true,'acmItem':true,'noread':item.read <= 0,'read':item.read > 0}">
            <el-row>
              <el-col :lg="16" :md="16" :xs="16">
                <p @click="toLink(item.message_id,item.type)" href="#">{{ item.title }}</p>
              </el-col>
              <p class="poa">{{ getDate(item.addTime) }}</p>
            </el-row>
          </el-col>
          <el-col v-if="msgData.length > 0" :lg="24" class="more">
            <el-link :underline="false" href="/student/message" target="_blank">查看更多</el-link>
          </el-col>
          <!-- 无公告 -->
          <el-col v-else-if="msgData.length <= 0" :lg="24" class="nodata">
            <p>暂无公告</p>   
          </el-col>
        </el-row>
      </el-col>
      <el-col v-else :lg="24">
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
    news:{
      type: Array,
    },
    show:{
      type: Boolean,
      default: false,
    },
    msgData:{
      type: Array,
      required: true,
      default: [],
    }
  },
  methods:{
    toLink(id,type){
      this.$router.push({
        path: "/teacher/message/"+id,
        query:{
          type: type,
        }
      });
    },
  },
  computed:{
    sum(){
      return this.tableData.length;
    },
    getDate(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        m = String(m).length == 1 ? '0'+m : m;
        let d = date.getDate();
        d = String(d).length == 1 ? '0'+d : d;
        return y+"-"+m+"-"+d;
      }
    },
  },
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
  .noread::before{
    content:"";
    display: block;
    width: 10px;
    height: 10px;
    position: absolute;
    left: 15px;
    top: 5px;
    background-color: #2ecc71;
  }
  .read::before{
    content:"";
    display: block;
    width: 10px;
    height: 10px;
    position: absolute;
    left: 15px;
    top: 5px;
    background-color: #cccccc;
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