<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>作业数据汇总中心
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
        <el-col v-for="(item,i) in dataNum" :key="i" :xs="12" :sm="8" :md="8" :lg="4">
          <div class="data-item">
            <h3>{{item.num}}</h3>
            <p>{{item.type}}</p>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="chart">
      <el-row :gutter="12">
        <el-col :lg="8">
          <div class="data-item" style="height: 330px;margin-bottom:12px;">
            <el-table
              :data="hwData"
              height="318"
              :header-cell-style="headRowStyle"
              :row-style="rowStyle"
              :cell-style="rowStyle"
              size="mini"
              border 
              style="width: 100%;background:transparent;">
              <el-table-column
                prop="hw_class"
                width="60"
                label="课程">
              </el-table-column>
              <el-table-column
                prop="hw_name"
                width="180"
                label="作业">
              </el-table-column>
              <el-table-column
                prop="hw_teacher"
                width="80"
                label="教师">
              </el-table-column>
              <el-table-column
                prop="hw_state"
                width="80"
                label="状态">
              </el-table-column>
              <el-table-column
                prop="hw_timeout"
                width="130"
                label="截止">
              </el-table-column>
            </el-table>
          </div>
          <div class="data-item" style="height: 391px;margin-bottom:12px;">
            <el-table
              :data="stData"
              height="379"
              :header-cell-style="headRowStyle"
              :row-style="rowStyle"
              :cell-style="rowStyle"
              size="mini"
              border 
              style="width: 100%;background:transparent;">
              <el-table-column
                prop="st_id"
                width="100"
                label="学号">
              </el-table-column>
              <el-table-column
                prop="st_name"
                width="60"
                label="学生">
              </el-table-column>
              <el-table-column
                prop="st_homework"
                width="200"
                label="作业">
              </el-table-column>
              <el-table-column
                prop="st_class"
                width="60"
                label="科目">
              </el-table-column>
              <el-table-column
                prop="st_state"
                width="80"
                label="状态">
              </el-table-column>
            </el-table>
          </div>
        </el-col>
        <el-col :lg="8">
          <div style="height:330px;margin-bottom:12px" class="data-item ball">
            <div class="circle">
              <div class="wave">
                <h1>50%</h1>
              </div>
            </div>
            <p>今日出勤率</p>
          </div>
          <div class="probability">
            <el-row :gutter="12">
              <el-col v-for="(item,i) in probability" :key="i" :xs="12" :sm="8" :md="8" :lg="12">
                <div class="data-item">
                  <h3>{{item.num}}</h3>
                  <p>{{item.type}}</p>
                </div>
              </el-col>
            </el-row>
          </div>
        </el-col>
        <el-col :lg="8">  
          <div class="today data-item" :style="{width: '100%', height: '330px'}" id="todayClass"></div>
          <div class="data2 data-item por">
            <div class="pagehead2">
              <h2>本周作业提交</h2>
            </div>
            <div class="do">
              <el-button size="small">周</el-button>
              <el-button size="small">月</el-button>
              <el-button size="small">学期</el-button>
            </div>
            <div class="attendanceTo" :style="{width: '100%', height: '350px'}" id="attendance"></div>
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
      dataNum:[
        {
          type: "今日布置量",
          num: 5000,
        },
        {
          type: "班级总人数",
          num: 50,
        },
        {
          type: "应交",
          num: 5000,
        },
        {
          type: "实交",
          num: 500,
        },
        {
          type: "缺交",
          num:5000,
        },
        {
          type: "补交",
          num: 500,
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
      value: "19r1",
      hwData:[
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
        {
          hw_class: "语文",
          hw_name: "背诵完成唐诗宋词1000首",
          hw_teacher: "张某某",
          hw_state: "未收齐",
          hw_timeout: "2020-04-28 08:20"
        },
      ],
      stData:[
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
        {
          st_id: "2017217040",
          st_name: "李某某",
          st_homework: "背诵完成唐诗宋词1000首",
          st_class: "语文",
          st_state: "未完成",
        },
      ],
      probability:[
        {
          type: "今日上交率",
          num: "100%",
        },
        {
          type: "今日缺交率",
          num: "0%",
        },
        {
          type: "本周上交率",
          num: "100%",
        },
        {
          type: "本周缺交率",
          num: "0%",
        },
        {
          type: "本月上交率",
          num: "100%",
        },
        {
          type: "本月缺交率",
          num: "0%",
        },
      ],
    }
  },
  computed:{
    headRowStyle(){
      return {background:'#2c3e50',color:'#FFF',border:'1px solid #fff'};
    },
    rowStyle(){
      return {background:'transparent',color:'#FFF'};
    },
    todayToChart(){
      let newarr = [];
      $.each(this.dataNum, function (i, v) { 
         let obj = {
           value: v.num,
           name: v.type,
         };
        if(v.type != '今日布置量' && v.type != '应交'){
          newarr.push(obj);
        }
      });
      console.log(newarr);
      return newarr;
    },
    todayToType(){
      let newarr = [];
      $.each(this.dataNum, function (i, v) { 
        if(v.type != '今日布置量' && v.type != '应交'){
          newarr.push(v.type);
        }
      });
      return newarr;
    }
  },
  mounted(){
    let dom1 = document.getElementById("todayClass");
    let dom2 = document.getElementById("attendance");
    let myChart1 = this.echarts.init(dom1);
    let myChart2 = this.echarts.init(dom2);

    // 绘制图表
    myChart1.setOption({
      title:{
        text: "今日作业情况",
        subtext: "信息部",
        x: 'center',
        textStyle:{
          color: "#FFF",
        }
      },
      legend:{
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
        radius: '60%',
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
                formatter: '{b} : {c} ({d}%)' 
            }, 
            labelLine :{show:true} 
          } 
        },
      }]
    });

    myChart2.setOption({
      title: {
          x: 'center',
          show: false,
          textStyle:{
            color: "#FFF",
          }
      },
      tooltip: {
        trigger: 'axis',
        formatter:'{a}:{c}',
        axisPointer: {
          type: 'cross',
          crossStyle: {
            color: '#999'
          }
        }
      },
      legend: {
        show:true,
        selectedMode:'single',    //设置显示单一图例的图形，点击可切换
        bottom:0,//legend位置的调整
        left:10,
        textStyle:{
            color:'#5e859e',
            fontSize:10
        },
        // itemGap:20,
        // data:['点位一','点位二','点位三'],
        // inactiveColor:'#ccc'
      },
      grid: {//图位置调整
        top:'5%',
        left: '0%',
        right: '0%',
        bottom: '10%',
        containLabel: true
      },
      xAxis: [
        {
          type: 'category',
          data: ['周一', '周二', '周三', '周四', '周五', '周六', '周日'],
          axisLine:{
            lineStyle:{
              color:'#5e859e',//横坐标轴和字体颜色
              width:2//这里是为了突出显示加上的
            },
          },
          axisPointer: {
            type: 'shadow',
          },
          axisTick: {
            show:true,
            interval: 0
          }
        }
      ],
      //设置两个y轴，左边显示数量，右边显示概率
      yAxis: [
        {
          type: 'value',
          name: '',
          show: true,
          max: 1000,
          min: 0,
          interval: 100,
          axisLine: {
            lineStyle: {
              color: '#5e859e',
              width: 2
            }
          }
        },
        {
          type: 'value',
          name: '',
          min: 0,
          max: 100,
          interval: 10,
          axisLabel: {
            formatter: '{value} %'
          },
          axisLine: {
            lineStyle: {
              color: '#5e859e',//纵坐标轴和字体颜色
              width: 2
            }
          }
        }
      ],
      //每个设备分数量、概率2个指标，只要让他们的name一致，即可通过，legeng进行统一的切换
      series: [
        {
          name:'作业提交量',
          type:'bar',
          color:'#f17e0e',
          data:[400,500,700,680,650,0,0 ],
          barWidth : '50%'
        },
        {
          name:'',
          type:'line',
          color:'#2f87f1',
          yAxisIndex: 1,    //这里要设置哪个y轴，默认是最左边的是0，然后1，2顺序来。
          data:[75,65,85,66,45,0,0],
          symbolSize:10
        },
      ]
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
    transition: all .1s ease-in-out;
    margin-bottom: 12px;
  }
  .data1 .el-col:hover{
    background-color: rgba(52, 152, 219,.1);
  }
  .data1 h3{
    font-size: 30px;
    color: #3498db;
  }
  .data1 p{
    font-size: 14px;
    color: #ccc;
  }
  .today{
    margin-bottom: 12px;
  }
  .data2{
    position: relative;
  }
  .data2 > .do{
    text-align: right;
    margin-bottom: 12px;
    position: absolute;
    right: 12px;
    top: 6px;
  }
  .data2 > .pagehead2{
    left: 12px;
    top: 6px;
  }
  .ball>p{
    text-align: center;
    color: #FFF;
    font-size: 20px;
  }

  .probability .data-item{
    margin-bottom: 12px;
    text-align: center;
  }
  .probability h3{
    font-size: 30px;
    color: #0984e3;
  }
  .probability p{
    font-size: 14px;
    color: #FFF;
  }





  .circle{
      width: 230px;
      height: 230px;
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