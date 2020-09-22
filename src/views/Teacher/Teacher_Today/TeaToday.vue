<template>
  <div class="box todayList" v-loading="loading">
    <div>
      <el-calendar
      v-model="date">
        <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
        <template
          slot="dateCell"
          slot-scope="{data}">
          <div :class="data.isSelected ? 'is-selected' : ''" @click="getDate(data.day)">
            <!-- {{ data.day.split('-').slice(1).join('-') }} {{ data.isSelected ? '✔️' : ''}} -->
            {{ data.day.split('-').slice(1).join('-') }}
            <!-- {{data.day.split('-')[2]}} -->
            <br>
            <div v-if="workData.filter(item=>item.addDate == data.day).length > 0">
              <ul>
                <li v-for="(item,i) in workData.filter(item=>item.addDate == data.day)[0]['child']" :key="day+'-'+i" :class="item.type">
                  <el-link :title="item.title" :underline="false" :type=" getState(item.start_time,item.end_time) == 0 ? 'primary' : getState(item.start_time,item.end_time) == 1 ? 'success' : 'danger' ">{{item.title}}</el-link>
                </li>
              </ul>
            </div>
              <!-- {
                "isSelected": true,   //选中状态
                "type": "current-month",    // 当前日期所属月份：prev-month 上个月; current-month 这个月; next-month 下个月
                "day": "2020-04-22"   // 格式化的日期   yyyy-MM-dd
              }  -->
          </div>
        </template>
      </el-calendar>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      date: new Date(),
      workData: [],
      loading: false,

      year: new Date().getFullYear(),
      month: new Date().getMonth() + 1,
      day: new Date().getDate(),
    }
  },
  created(){
    this.getToDo();
    let date = this.year + '-' + this.month + "-" + this.day;
    this.$emit("getDayToDo",date);
  },
  methods:{
    goBack(){
      this.$router.go(-1);
    },
    getDate(date){
      // console.log(day);
      let arr = date.split('-');
      if(this.day != parseInt(arr[2])){
        this.day = parseInt(arr[2]);
      }
      if(this.year != parseInt(arr[0]) || this.month != parseInt(arr[1])){
        this.year = parseInt(arr[0]);
        this.month = parseInt(arr[1]);
        this.getToDo();
      }
      this.$emit("getDayToDo",date);
    },
    getToDo(){
      let start = new Date(this.year,this.month-1,1).getTime();
      let end = new Date(this.year,this.month,0).getTime();
      // console.log(start);
      // console.log(end);
      this.loading = true;
      requestAjax({
        type: "post",
        url: "/affairs.php",
        data:{
          action: 'month',
          type: "tea",
          userid: this.$store.state.userId,
          campus: this.$store.state.userCampus,
          start: start,
          end: end,
        },
        success:res=>{
          this.loading = false;
          res = JSON.parse(res);
          this.workData = res.data;
          // console.log(res.data);
        },
        error:err=>{
          this.loading = false;
          console.error(err);
        }
      })
    }
  },
  computed:{
    
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
}
</script>

<style>
  .todayList{
    height: 755px;
  }
  .todayList>.el-page-header{
    margin-bottom: 24px;
  }
  .is-selected {
    color: #1989FA;
  }
  .el-calendar-table:not(.is-range) td.next, .el-calendar-table:not(.is-range) td.prev{
    color: #aaa;
    background-color: #f5f5f5;
  }
  .current{
    height: 100%;
  }
  .el-calendar-table .el-calendar-day{
    /* height: auto; */
    min-height: 90px;
    position: relative;
    overflow: hidden;
  }
  .el-calendar-day > div{
    width: 100%;
    min-height: 90px;
  }
  .el-calendar-day li{
    width: 100%;
    overflow: hidden;
    text-overflow:ellipsis;
    white-space: nowrap;
    line-height: 16px;
    margin-bottom: 3px;
  }
  .el-calendar-day li a{
    font-size: 12px;
  }
  .el-calendar-day li.success{
    border-left: 2px solid #2ecc71;
    padding-left: 3px;
  }
  .el-calendar-day li.warning{
    border-left: 2px solid #f1c40f;
    padding-left: 3px;
  }
  .el-calendar-day li.danger{
    border-left: 2px solid #e74c3c;
    padding-left: 3px;
  }
  .el-calendar__button-group{
    display: none;;
  }
</style>