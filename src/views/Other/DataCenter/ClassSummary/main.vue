<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>课堂数据汇总中心
        <small>
          <el-select size="mini" v-model="value" placeholder="请选择">
            <el-option
              v-for="item in options"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
        </small>
      </h1>
    </div>
    <div class="class_info">
      <el-row :gutter="12">
        <el-col class="data-item" :xs="24" :sm="8" :md="8" :lg="8">
          <div>
            <h3>50</h3>
            <p>应到人数</p>
          </div>
        </el-col>
        <el-col class="data-item" :xs="24" :sm="8" :md="8" :lg="8">
          <div>
            <h3>40</h3>
            <p>实到人数</p>
          </div>
        </el-col>
        <el-col class="data-item" :xs="24" :sm="8" :md="8" :lg="8">
          <div>
            <h3>10</h3>
            <p>未到人数</p>
          </div>
        </el-col>
        <el-col class="data-item" v-for="(item,i) in class_probability" :xs="12" :sm="12" :md="12" :lg="6" :key="i">
          <div>
            <h3>{{item.num}}</h3>
            <p>{{item.type}}</p>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="chart">
      <el-row :gutter="12">
        <el-col :lg="10">
          <div>
            <div :style="{width: '100%', height: '400px'}" id="todayClass"></div>
          </div>
        </el-col>
        <el-col :lg="4">
          <div class="today">
            <div>
              <div class="pagehead2">
                <h2>今日出勤率</h2>
              </div>
              <div class="gl">
                <h1>87%</h1>
              </div>
            </div>
            <div>
              <div class="pagehead2">
                <h2>今日迟到率</h2>
              </div>
              <div class="gl">
                <h1>13%</h1>
              </div>
            </div>
          </div>
        </el-col>
        <el-col :lg="10">
          <div class="data2 por">
            <div class="data2_do poa">
              <el-button size="small">周</el-button>
              <el-button size="small">月</el-button>
              <el-button size="small">学期</el-button>
            </div>
            <div class="attendanceTo" id="attendance"></div>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="data1">
      <el-row :gutter="12">
        <el-col :lg="14">
          <div class="class_detailed">
            <el-row :gutter="12">
              <el-col class="data-item" v-for="(item,i) in dataNum" :key="i" :xs="12" :sm="8" :md="6" :lg="6">
                <div>
                  <h3>{{item.num}}</h3>
                  <p>{{item.type}}</p>
                </div>
              </el-col>
            </el-row>
          </div>
        </el-col>
        <el-col class="data-item" :lg="10">
          <div class="class_student" style="height:164px">
            <el-table
              :data="tableData"
              size="mini"
              height="150px"
              border
              style="width: 100%;">
              <el-table-column
                prop="name"
                label="姓名">
              </el-table-column>
              <el-table-column
                prop="state"
                width="100"
                label="情况">
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import echarts from 'echarts';
export default {
  data(){
    return{
      clientWidth: document.body.clientWidth || document.documentElement.clientWidth,
      class_probability:[
        {
          type: "本周出勤率",
          num: "100%",
        },
        {
          type: "本周缺勤率",
          num: "20%",
        },
        {
          type: "本月出勤率",
          num: "60%",
        },
        {
          type: "本月缺勤率",
          num: "15%",
        },
      ],
      dataNum:[
        {
          type: "事假",
          num: 60,
        },
        {
          type: "公假",
          num: 40,
        },
        {
          type: "迟到",
          num: 80,
        },
        {
          type: "早退",
          num: 120,
        },
        {
          type: "讲话",
          num: 200,
        },
        {
          type: "玩手机",
          num: 250,
        },
        {
          type: "玩游戏",
          num: 300,
        },
        {
          type: "其他",
          num: 0,
        },
      ],
      options: [
        {
          value: '19r1',
          label: '19软件1班'
        }, {
          value: '19w2',
          label: '19网络2班'
        }
      ],
      tableData:[
        {
          name: "李某某",
          state: "出勤",
        },
        {
          name: "李某某",
          state: "出勤",
        },
        {
          name: "李某某",
          state: "出勤",
        },
        {
          name: "李某某",
          state: "出勤",
        },
        {
          name: "李某某",
          state: "出勤",
        },
        {
          name: "李某某",
          state: "出勤",
        },
      ],
      value: '19r1'
    }
  },
  computed:{
    todayToChart(){
      let newarr = [];
      $.each(this.dataNum, function (i, v) { 
         let obj = {
           value: v.num,
           name: v.type,
         };
         newarr.push(obj);
      });
      return newarr;
    },
    todayToType(){
      let newarr = [];
      $.each(this.dataNum, function (i, v) { 
         newarr.push(v.type);
      });
      return newarr;
    }
  },
  mounted(){
    $("body").css("overflow","auto");
    let dom1 = document.getElementById("todayClass");
    let dom2 = document.getElementById("attendance");
    let myChart1 = this.echarts.init(dom1);
    let myChart2 = this.echarts.init(dom2);

    // 绘制图表
    myChart1.setOption({
      title:{
        text: "今日课堂情况",
        subtext: "信息部",
        x: 'center',
        textStyle:{
          color: "#FFF",
        }
      },
      legend:{
        show: this.clientWidth < 768 ? false : true,
        orient: "veritcal",
        left: "left",
        data: this.todayToType,
        textStyle:{
          color: "#FFF",
        }
      },
      series: [{
        name: '今日课堂情况',
        type: 'pie',
        radius: '50%',
        data: this.todayToChart,
        center: ['50%', '50%'],
        itemStyle: {
          emphasis: {
            shadowBlur: 10,
            shadowOffsetX: 0,
            shadowColor: 'rgba(0, 0, 0, 0.5)'
          },
          normal:{ 
            label:{ 
              show: true, 
              formatter: '{b} : {c} ({d}%)',
            }, 
            labelLine :{
              show: true,
            } 
          } 
        },
      }]
    });

    myChart2.setOption({
      title: {
        text: '全勤走势图',
        textStyle:{
          color: "#FFF",
        }
      },
      tooltip: {},
      legend: {},
      xAxis: {
        data: ["周一","周二","周三","周四","周五","周六","周日"],
        axisLabel:{
          textStyle:{
            color: "#ddd",
          }
        },
      },
      yAxis: {
        axisLabel:{
          textStyle:{
            color: "#ddd",
          }
        },
      },
      series: [{
        type: 'bar',
        data: [520, 603, 628, 430, 750, 0,0],
      }],
      itemStyle: {
        // 设置扇形的颜色
        color: 'rgba(52, 152, 219,1.0)',
      } 
    });

  }
}
</script>

<style>

  .class_info{
    margin-bottom: 12px;
    text-align: center;
  }
  .class_info .el-col{
    margin-bottom: 12px;
  }
  .class_info .data-item > div{
    padding: 6px;
  }
  .class_info h3{
    font-size: 30px;
    margin-bottom: 6px;
    color: #3498db;
  }
  .class_info p{
    font-size: 14px;
    color: #bbb;
  }

  .chart{
    color: #FFF;
    margin-bottom: 0px;
  }
  .data1{
    text-align: center;
  }
  .data1 .el-col{
    margin-bottom: 12px;
    transition: all .1s ease-in-out;
  }
  .data-item>div{
    background-color: rgba(0,0,0,.6);
    padding: 6px;
    border-radius: 6px;
  }
  .data1 h3{
    font-size: 30px;
    margin-bottom: 6px;
    color: #3498db;
  }
  .data1 p{
    font-size: 14px;
    color: #bbb;
  }

  @media screen and (max-width:1200px) {
    .chart .el-col{
      margin-bottom: 12px;
    }
  }

  .chart .el-col > div{
    background-color: rgba(0,0,0,.6);
    padding: 12px;
    border-radius: 6px;
    height: 420px;
  }

  .today>div{
    margin-bottom: 100px;
  }
  .gl{
    text-align: center;
    margin-top: 36px;
  }
  .gl>h1{
    font-size: 50px;
  }

  .today{
    margin-bottom: 24px;
  }
  .data2 > .data2_do{
    text-align: right;
    margin-bottom: 12px;
    right: 12px;
    top: 12px;
  }
  .attendanceTo{
    width: 100%;
    height: 400px;
  }

  .el-table__header .el-table td, .el-table th.is-leaf{
    background-color: #2c3e50;
    color: #FFF;
  }


</style>