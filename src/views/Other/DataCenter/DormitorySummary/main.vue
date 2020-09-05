<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>宿舍数据汇总中心
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
    <div class="data1">
      <el-row :gutter="12">
        <el-col v-for="(item,i) in dataNum" :key="i" :lg="8">
          <el-row :gutter="6">
            <el-col :span="24"><h3 class="data-item">{{item.date}}</h3></el-col>
            <el-col :xs="6" :sm="6" :md="6" :lg="6" v-for="(item2,r) in item.situation" :key="r">
              <div class="data-item">
                <h4>{{item2.num}}</h4>
                <p>{{item2.type}}</p>
              </div>
            </el-col>
          </el-row>
        </el-col>
      </el-row>
    </div>
    <div class="chart">
      <el-row :gutter="12">
        <el-col :lg="8">
          <div class="data-item" :style="{width: '100%', height: '350px'}" id="todayClass"></div>
        </el-col>
        <el-col :lg="8">
          <div style="height:350px" class="data-item ball">
            <div class="circle">
              <div class="wave">
                <h1>50%</h1>
              </div>
            </div>
            <p>今日出勤率</p>
          </div>
        </el-col>
        <el-col :lg="8">
          <div class="data2 por">
            <div class="do poa">
              <el-button size="mini">周</el-button>
              <el-button size="mini">月</el-button>
              <el-button size="mini">学期</el-button>
            </div>
            <div class="data-item" :style="{width: '100%', height: '350px'}" id="attendance"></div>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="probability">
      <el-row :gutter="12">
        <el-col :md="8" :lg="8">
          <el-row :gutter="12">
            <el-col v-for="(item,i) in class_probability" :key="i" :xs="12" :sm="8" :md="12" :lg="12">
              <div class="data-item">
                <h4>{{item.num}}</h4>
                <p>{{item.type}}</p>
              </div>
            </el-col>
          </el-row>
        </el-col>
        <el-col :md="16" :lg="16">
          <div class="data-item" style="height: 221px">
            <el-table
              :data="tableData"
              :header-cell-style="rowStyle"
              height="210"
              border 
              style="width: 100%;">
              <el-table-column
                prop="name"
                label="姓名">
              </el-table-column>
              <el-table-column
                prop="attendance"
                label="出勤">
              </el-table-column>
              <el-table-column
                prop="breach"
                label="违纪">
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      clientWidth: document.body.clientWidth || document.documentElement.clientWidth,
      dataNum:[
        {
          date: "早上",
          situation: [
            {
              type: "出勤",
              num: 500,
            },
            {
              type: "请假",
              num: 230,
            },
            {
              type: "旷宿",
              num: 201,
            },
            {
              type: "晚归",
              num: 40,
            },
            {
              type: "迟离",
              num: 20,
            },
            {
              type: "内务",
              num: 42,
            },
            {
              type: "值日",
              num: 88,
            },
            {
              type: "其他",
              num: 38,
            },
          ]
        },
        {
          date: "中午",
          situation: [
            {
              type: "出勤",
              num: 900,
            },
            {
              type: "请假",
              num: 120,
            },
            {
              type: "旷宿",
              num: 340,
            },
            {
              type: "晚归",
              num: 60,
            },
            {
              type: "迟离",
              num: 30,
            },
            {
              type: "内务",
              num: 40,
            },
            {
              type: "值日",
              num: 90,
            },
            {
              type: "其他",
              num: 60,
            },
          ]
        },
        {
          date: "晚上",
          situation: [
            {
              type: "出勤",
              num: 700,
            },
            {
              type: "请假",
              num: 520,
            },
            {
              type: "旷宿",
              num: 30,
            },
            {
              type: "晚归",
              num: 80,
            },
            {
              type: "迟离",
              num: 390,
            },
            {
              type: "内务",
              num: 100,
            },
            {
              type: "值日",
              num: 280,
            },
            {
              type: "其他",
              num: 120,
            },
          ]
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
      class_probability:[
        {
          type: "今日出勤率",
          num: "100%",
        },
        {
          type: "今日缺勤率",
          num: "0%",
        },
        {
          type: "本周出勤率",
          num: "0%",
        },
        {
          type: "本周缺勤率",
          num: "0%",
        },
        {
          type: "本月出勤率",
          num: "0%",
        },
        {
          type: "本月缺勤率",
          num: "0%",
        },
      ],
      value: "19r1",
      tableData:[
        {
          name: "李某某",
          attendance: "出勤",
          breach: "迟到",
        },
        {
          name: "李某某",
          attendance: "出勤",
          breach: "迟到",
        },
        {
          name: "李某某",
          attendance: "出勤",
          breach: "迟到",
        },
        {
          name: "李某某",
          attendance: "出勤",
          breach: "迟到",
        },
        {
          name: "李某某",
          attendance: "出勤",
          breach: "迟到",
        },
      ],
    }
  },
  created(){
  },
  computed:{
    toClient(){
      if(this.clientWidth < 768){
        return false
      }
      return true;
    },
    todayToChart(){
      let newarr = [];
      $.each(this.dataNum[0].situation, (i, v)=>{ 
        let obj = {};
        for(let r=0;r<this.dataNum.length;r++){
          obj[this.dataNum[r].date] = this.dataNum[r].situation[i].num;
        }
        obj['col'] = v.type;
        newarr.push(obj);
      });
      return newarr;
    },
    todayToType(){
      let newarr = [];
      $.each(this.dataNum, function (i, v) { 
         newarr.push(v.date);
      });
      newarr.unshift("col");
      return newarr;
    },
    rowStyle(){
      return {background:'#2c3e50',color:'#FFF'};
    }
  },
  mounted(){
    let dom1 = document.getElementById("todayClass");
    let dom2 = document.getElementById("attendance");
    let myChart1 = this.echarts.init(dom1);
    let myChart2 = this.echarts.init(dom2);

    // 绘制图表
    myChart1.setOption({
      legend: {
        show: this.toClient,
        textStyle:{
          color: "#FFF",
        },
      },
      tooltip: {
        show: true,
      },
      dataset: {
        // 这里指定了维度名的顺序，从而可以利用默认的维度到坐标轴的映射。
        // 如果不指定 dimensions，也可以通过指定 series.encode 完成映射，参见后文。
        dimensions: this.todayToType,
        source: this.todayToChart,
      },
      xAxis: {
        type: 'category',
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
      series: [
        {type: 'bar'},
        {type: 'bar'},
        {type: 'bar'}
      ]
    });

    myChart2.setOption({
      title: {
        text: '本周全勤走势图',
        textStyle:{
          color: "#ddd",
        },
      },
      tooltip: {
        show: true,
      },
      legend: {
        data:['数量']
      },
      xAxis: {
        type: 'category',
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
      dataset:{
        dimensions: this.todayToType,
        source: [
          {col: "周一","早上":'750',"中午":"550","晚上":"750"},
          {col: "周二","早上":'550',"中午":"250","晚上":"800"},
          {col: "周三","早上":'450',"中午":"200","晚上":"630"},
          {col: "周四","早上":'350',"中午":"400","晚上":"710"},
          {col: "周五","早上":'500',"中午":"450","晚上":"850"},
          {col: "周六","早上":'0',"中午":"0","晚上":"0"},
          {col: "周日","早上":'0',"中午":"0","晚上":"0"},
        ],
      },
      series: [
        {
          type: 'bar',
          itemStyle: {
            normal: {
              label: {
                show: true, //开启显示
                position: 'top', //在上方显示
                textStyle: { //数值样式
                  color: '#FFF',
                  fontSize: 12,
                }
              }
            }
          } 
        },
        {
          type: 'bar',
          itemStyle: {
            normal: {
              label: {
                show: true, //开启显示
                position: 'top', //在上方显示
                textStyle: { //数值样式
                  color: '#FFF',
                  fontSize: 12
                }
              }
            }
          } 
        },
        {
          type: 'bar',
          itemStyle: {
            normal: {
              label: {
                show: true, //开启显示
                position: 'top', //在上方显示
                textStyle: { //数值样式
                  color: '#FFF',
                  fontSize: 12
                }
              }
            }
          } 
        },
      ],
      itemStyle: {
        // 设置扇形的颜色
        color: '#3498db',
      }
    });

  }
}
</script>

<style scoped>
  .data-item{
    background-color: rgba(0,0,0,.6);
    padding: 6px;
    border-radius: 6px;
  }
  .data1{
    text-align: center;
  }
  .data1 .el-col{
    margin-bottom: 12px;
    transition: all .1s ease-in-out;
  }
  .data1 .el-col .el-col:hover{
    background-color: rgba(52, 152, 219,.1);
  }
  .data1 h3{
    font-size: 26px;
    color: #3498db;
  }
  .data1 h4{
    font-size: 20px;
    /* margin-bottom: 6px; */
    color: #fff;
  }
  .data1 p{
    font-size: 14px;
    color: #666;
  }
  .today{
    margin-bottom: 24px;
  }
  .data2 > .do{
    text-align: right;
    margin-bottom: 12px;
    right: 12px;
    top: 6px;
    z-index: 1;
  }
  .ball>p{
    text-align: center;
    color: #FFF;
    font-size: 20px;
  }
  .chart{
    margin-bottom: 12px;
  }
  @media screen and (max-width: 1200px){
    .chart .data-item{
      margin-bottom: 12px;
    }
  }
  .probability .data-item{
    margin-bottom: 12px;
    /* padding: 18px 12px; */
    text-align: center;
  }
  .probability .data-item h4{
    font-size: 26px;
    margin-bottom: 2px;
    color: #ecf0f1;
  }
  .probability .data-item p{
    font-size: 14px;
    color: #bdc3c7;
  }
  .el-table th, .el-table tr{
    background-color: #000;
  }





  .circle{
      width: 250px;
      height: 250px;
      margin: 20px auto;
      border-radius: 50%;
      border: 5px solid #FFF;
      box-shadow: 0 0 0 10px #4973ff;
      overflow: hidden;
    }
    .wave{
      position: relative;
      width: 100%;
      height: 100%;
      background-color: #4873ff;
      border-radius: 50%;
      box-shadow: inset 0 0 50px;
      /* background-color: rgba(0,0,0,0.5); */
      display: flex;
      align-items: center;
      justify-content: center;
      color: #000;
    }
    .wave>h1{
      position: relative;
      z-index: 3;
      color: #FFF
    }

    .wave::before,
    .wave::after{
      content:"";
      position: absolute;
      width: 200%;
      height: 200%;
      top: 0;
      left: 0;
      transform: translate(-50%,-75%);
      background-color: #000;
    }

    .wave::before{
      border-radius: 45%;
      /* background-color: rgba(255,255,255,1);; */
      background-color: transparent;
      animation: animate 5s linear infinite;
      z-index: 2;
    }

    .wave::after{
      border-radius: 40%;
      background-color: rgba(255,255,255,.5);
      z-index: 1;
      animation: animate 10s linear infinite;
    }

    @keyframes animate {
      0%{
        transform: translate(-25%,-75%) rotate(0deg);
      }
      100%{
        transform: translate(-25%,-75%) rotate(360deg);
      }
    }
</style>