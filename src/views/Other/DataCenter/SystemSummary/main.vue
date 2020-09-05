<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>平台数据汇总中心</h1>
    </div>
    <div class="data1">
      <el-row :gutter="12">
        <el-col v-for="(item,i) in systemData" :key="i" :xs="12" :sm="6" :md="6" :lg="6">
          <div class="systemData data-item clearfix">
            <p>{{item.type}}</p>
            <h3>{{item.num}}</h3>
          </div>
        </el-col>
        <el-col v-for="(item,r) in systemInfo" :key="'0'+r" :xs="8" :sm="8" :md="8" :lg="8">
          <div class="systemInfo data-item">
            <h3>{{item.num}}</h3>
            <p>{{item.type}}</p>
          </div>
        </el-col>
      </el-row>
    </div>
    <div class="info">
      <el-row :gutter="12">
        <el-col :lg="8">
          <div class="data-item" style="height: 330px;margin-bottom:12px;">
            <el-table
              :data="loginData"
              height="318"
              :header-cell-style="headRowStyle"
              :row-style="rowStyle"
              :cell-style="rowStyle"
              size="mini"
              border 
              style="width: 100%;background:transparent;">
              <el-table-column
                prop="name"
                label="姓名">
              </el-table-column>
              <el-table-column
                prop="school"
                width="180"
                label="学校">
              </el-table-column>
              <el-table-column
                prop="group"
                label="权限">
              </el-table-column>
              <el-table-column
                prop="state"
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
            <p>系统安全系数</p>
          </div>
        </el-col>
        <el-col :lg="8">
          <div class="data-item" style="height: 330px;margin-bottom:12px;">
            <el-table
              :data="loginData"
              height="318"
              :header-cell-style="headRowStyle"
              :row-style="rowStyle"
              :cell-style="rowStyle"
              size="mini"
              border 
              style="width: 100%;background:transparent;">
              <el-table-column
                prop="name"
                label="姓名">
              </el-table-column>
              <el-table-column
                prop="school"
                width="180"
                label="学校">
              </el-table-column>
              <el-table-column
                prop="group"
                label="权限">
              </el-table-column>
              <el-table-column
                prop="state"
                label="状态">
              </el-table-column>
            </el-table>
          </div>
        </el-col>
      </el-row>
    </div>
    
    <div class="chart">
      <el-row :gutter="12">
        <el-col :lg="8">
          <div class="data-item" :style="{width: '100%', height: '320px'}" id="todayClass"></div>
        </el-col>
        <el-col :lg="8">
          <el-row :gutter="12">
            <el-col :span="24">
              <div class="data-item info">
                <h3>500</h3>
                <p>存储ip地址数量</p>
              </div>
            </el-col>
            <el-col :span="24">
              <div class="data-item info">
                <h3>1380</h3>
                <p>被攻击次数</p>
              </div>
            </el-col>
          </el-row>
        </el-col>
        <el-col :lg="8">
          <div class="data2 por">
            <div class="do poa">
              <el-button size="mini">日</el-button>
              <el-button size="mini">月</el-button>
              <el-button size="mini">年</el-button>
            </div>
            <div class="data-item" :style="{width: '100%', height: '320px'}" id="attendance"></div>
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
      systemData:[
        {
          type: "用户人数",
          num: "88888",
        },
        {
          type: "在线人数",
          num: "9876",
        },
        {
          type: "运维时长",
          num: "12345.5h",
        },
        {
          type: "被访问次数",
          num: "999999",
        },
      ],
      systemInfo:[
        {
          type: "学校数量",
          num: "120",
        },
        {
          type: "学生总数",
          num: "9090",
        },
        {
          type: "教师总数",
          num: "800",
        },
      ],
      loginData:[
        {
          name: "李某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '在线',
        },
        {
          name: "李某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '在线',
        },
        {
          name: "黄某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '离线',
        },
        {
          name: "李某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '在线',
        },
        {
          name: "张某某",
          school: "深圳市宝安职业技术学校",
          group: "教师",
          state: '在线',
        },
        {
          name: "李某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '在线',
        },
        {
          name: "李某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '在线',
        },
        {
          name: "黄某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '离线',
        },
        {
          name: "李某某",
          school: "深圳市宝安职业技术学校",
          group: "学生",
          state: '在线',
        },
        {
          name: "张某某",
          school: "深圳市宝安职业技术学校",
          group: "教师",
          state: '在线',
        },
      ],
      clientWidth: document.documentElement.clientWidth || document.body.clientWidth,
      attack:[
        {
          name: "漏洞攻击",
          value: "500",
        },
        {
          name: "网络攻击",
          value: "380",
        },
        {
          name: "sql攻击",
          value: "120",
        },
        {
          name: "服务器攻击",
          value: "600",
        },
        {
          name: "数据骚扰",
          value: "340",
        },
      ],
      attack_type:["漏洞攻击","网络攻击","sql攻击","服务器攻击","数据骚扰"],
    }
  },
  computed:{
    toClient(){
      if(this.clientWidth < 768){
        return false
      }
      return true;
    },
    headRowStyle(){
      return {background:'#2c3e50',color:'#FFF',border:'1px solid #fff'};
    },
    rowStyle(){
      return {background:'transparent',color:'#FFF'};
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
        data: this.attack_type,
        textStyle:{
          color: "#FFF",
          
        }
      },
      series: [{
        name: '攻击记录',
        type: 'pie',
        radius: '45%',
        data: this.attack,
        center: ['50%', '60%'],
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
        text: '攻击次数地理分布',
        textStyle:{
          color: "#FFF",
        }
      },
      tooltip: {},
      legend: {},
      xAxis: {
        data: ["广东","北京","上海","杭州","四川","武汉","山东"],
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
        data: [138, 240, 88, 64, 20, 67,77],
      }],
      itemStyle: {
        // 设置扇形的颜色
        color: 'rgba(52, 152, 219,1.0)',
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
    margin-bottom: 6px;
    transition: all .1s ease-in-out;
  }
  .data1 .el-col:hover{
    background-color: rgba(52, 152, 219,.1);
  }
  .systemData h3{
    font-size: 30px;
    color: #3498db;
    text-align: left;
  }
  .systemData p{
    text-align: left;
    font-size: 16px;
    color: #FFF;
  }
  .systemInfo h3{
    font-size: 30px;
    color: #3498db;
  }
  .systemInfo p{
    font-size: 14px;
    color: #FFF;
  }
  .data2 > .do{
    text-align: right;
    margin-bottom: 12px;
    position: absolute;
    right: 12px;
    top: 6px;
    z-index: 2;
  }
  .chart h3{
    font-size: 36px;
    text-align: center;
    color: #3498db;
    margin-bottom: 4px;
  }
  .chart p{
    font-size: 16px;
    text-align: center;
  }
  .chart .info{
    margin-bottom: 12px;
  }
  .chart .data-item{
    margin-bottom: 12px;
  }


  .today{
    margin-bottom: 24px;
  }
  .data2 > .do{
    text-align: right;
    margin-bottom: 12px;
  }
  .attendanceTo{
    width: 100%;
    height: 500px;
  }
  .ball>p{
    text-align: center;
    color: #FFF;
    font-size: 20px;
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