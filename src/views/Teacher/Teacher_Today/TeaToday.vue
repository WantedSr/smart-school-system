<template>
  <div class="box todayList">
    <div>
      <el-calendar
      v-model="date">
        <!-- 这里使用的是 2.5 slot 语法，对于新项目请使用 2.6 slot 语法-->
        <template
          slot="dateCell"
          slot-scope="{date, data}">
          <div :class="data.isSelected ? 'is-selected' : ''" @click="getDate(data.day)">
            <!-- {{ data.day.split('-').slice(1).join('-') }} {{ data.isSelected ? '✔️' : ''}} -->
            {{ data.day.split('-').slice(1).join('-') }}
            <!-- {{data.day.split('-')[2]}} -->
            <br>
            <div v-if="workData[data.day.split('-').slice(1).join('-')] != ''">
              <ul>
                <!-- <li class="success">早上三四节数学考试</li>
                <li class="warning">中午午休上传成绩</li>
                <li class="danger">下午第七节学生会开会</li> -->
                <li v-for="(item,i) in workData[data.day.split('-').slice(1).join('-')]" :key="i" :class="item.type"><el-link :title="item.title" :underline="false" :type="item.type">{{item.title}}</el-link></li>
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
export default {
  data(){
    return{
      date: new Date(),
      workData: {
        "04-22":[
          {
            title: "早上三四节数学考试",
            type: "success",
          },
          {
            title: "中午午休上传成绩",
            type: "warning",
          },
          {
            title: "下午第七节学生会开会",
            type: "danger",
          },
        ]
      },
    }
  },
  methods:{
    goBack(){
      this.$router.go(-1);
    },
    getDate(day){
      console.log(day);
    }
  }
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
</style>