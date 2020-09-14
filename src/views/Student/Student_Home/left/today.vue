<template>
  <div class="today box">
    <el-row>
      <el-col :lg="24" class="head">
        <h4 class="por">今日待办<small class="poa"><el-link href="/student/today" :underline="false">更多</el-link></small></h4>
        <!-- <h4 class="por">今日待办<small class="poa">TO-DO Today</small></h4> -->
      </el-col>
      <el-col class="mywork" :span="24">
        <ul v-if="todoData.length > 0">
          <li v-for="(item,i) in todoData" :key="'do-'+i" class="mwItem">
            <el-link class="por" href="/student/today" target="_blank" :underline="false">
              <div>
                <h4>{{ item.title }}</h4>
                <p>{{ getTime(item.start_time) }}</p>
              </div>
              <div class="poa">
                <el-tag :type="getState(item.start_time,item.end_time) == 0 ? 'primary' : getState(item.start_time,item.end_time) == 1 ? 'success' : 'danger'" size="mini">
                  {{ getState(item.start_time,item.end_time) == 0 ? '待完成' : getState(item.start_time,item.end_time) == 1 ? '完成中' : '已超时' }}
                </el-tag>
                <p>{{ item.start_time }}</p>
              </div>
            </el-link>
          </li>
        </ul>
        <p v-else style="color: #7f8c8d;font-size:16px" class="more">今日没有要办的事务哦！</p>
      </el-col>
      <el-col v-if="todoData.length > 0" :lg="24" class="more">
        <el-link :underline="false" href="/student/today">查看更多</el-link>
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
    todoData:{
      type: Array,
      required: true,
      default: [],
    }
  },
  computed:{
    getTime(){
      return (s)=>{
        let hour = parseInt(s.split(":")[0]);
        if(hour >= 4 && hour < 12){
          return "早上";
        }
        else if(hour >= 12 && hour < 20){
          return "下午";
        }
        else if(hour >= 20 || hour < 4){
          return "晚上"
        }
      }
    },
    getState(){
      return (s,e)=>{
        let date = new Date();
        let start = s.split(':');
        let end = e.split(':');
        let starthour = parseInt(start[0]),startmin = parseInt(start[1]);
        let endhour = parseInt(end[0]),endmin = parseInt(end[1]);
        let nowhour = date.getHours(),nowmin = date.getMinutes();
        if(starthour > nowhour){
          return 0
        }
        else if(starthour == nowhour){
          if(startmin > nowmin){
            return 0;
          }else{
            if(endhour == nowhour && endmin > nowmin){
              return 1;
            }
            return 2;
          }
        }
        else if(endhour > nowhour){
          return 1;
        }
        else{
          if(endhour == nowhour){
            if(endmin > nowmin){
              return 1;
            }
            return 2
          }
          else{
            return 2;
          }
        }
      }
    },
  },
  methods:{
    toLink(){
      this.$router.push("/student/message/xxxxx");
    },
  }
}
</script>

<style scoped>
  /* ------------------------------------mywork-------------------------------------- */
  .head{
    margin-bottom: 20px;
  }
  .today{
    height: 295px;
  }
  .mwItem{
    width: 100%;
    border-bottom: 1px solid #f5f5f5;
    transition: all .2s;
    margin-bottom: 3px;
  }
  .mwItem:hover{
    border-bottom: 1px solid #409EFF;
    background-color: rgba(52, 152, 219,.1)
  }
  .mwItem>a{
    display: block;
    width: 100%;
    padding: 6px;
  }
  .mwItem h4{
    font-size: 18px;
    font-weight: 500;
    color: #666;
    margin-bottom: 2px;
    width: 100%;
    overflow: hidden;
    text-overflow:ellipsis;
    padding-right: 80px;
    white-space: nowrap;
  }
  .mwItem p span{
    font-size: 14px;
    color: #bbb;
    margin-right: 12px;
  }
  .mwItem>a>span>div:nth-child(2){
    right: 6px;
    color: #999;;
    font-size: 14px;
    bottom: 6px;
    text-align: right;
  }
  .mwItem>a>span>div:nth-child(2)>p{
    margin-top: 3px;
  }

  .today .more{
    text-align: center;
    padding: 6px 0;
  }

</style>