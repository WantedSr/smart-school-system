<template>
  <div class="datacenter">
    <div v-if="toOther" class="particles">
      <div>
        <div class="intro">
          <h1>智慧校园平台数据中心</h1>
          <p>Smart campus data center platform</p>
        </div>
        <el-row :getter="20">
          <el-col :offset="onXS" :lg="12" :md="12" :sm="12" :xs="24">
            <div class="dc-option">
              <ul class="block clearfix">
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/class_summary?type=class" target="_blank" class="hexIn">
                      <span class="el-icon-s-claim"></span>
                      <p>课堂-班级</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/class_summary?type=department" target="_blank" class="hexIn">
                      <span class="el-icon-s-claim"></span>
                      <p>课堂-部门</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/class_summary?type=school" target="_blank" class="hexIn">
                      <span class="el-icon-s-claim"></span>
                      <p>课堂-学校</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/dormitory_summary?type=class" target="_blank" class="hexIn">
                      <span class="el-icon-s-home"></span>
                      <p>宿舍-班级</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/dormitory_summary?type=department" target="_blank" class="hexIn">
                      <span class="el-icon-s-home"></span>
                      <p>宿舍-部门</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/dormitory_summary?type=school" target="_blank" class="hexIn">
                      <span class="el-icon-s-home"></span>
                      <p>宿舍-学校</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/homework_summary?type=class" target="_blank" class="hexIn">
                      <span class="el-icon-edit"></span>
                      <p>作业-班级</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/homework_summary?type=department" target="_blank" class="hexIn">
                      <span class="el-icon-edit"></span>
                      <p>作业-部门</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/homework_summary?type=school" target="_blank" class="hexIn">
                      <span class="el-icon-edit"></span>
                      <p>作业-学校</p>
                    </el-link>
                  </span>
                </li>
                <li>
                  <span class="hex">
                    <el-link href="/other/data_center/system_summary" target="_blank" class="hexIn">
                      <span class="el-icon-s-marketing"></span>
                      <p>平台数据</p>
                    </el-link>
                  </span>
                </li>
              </ul>
            </div>
          </el-col>
        </el-row>
      </div>
    </div>
    <div v-else class="dataItem">
      <keep-alive>
        <router-view></router-view>
      </keep-alive>
    </div>
    <particles></particles>
  </div>
</template>

<script>
import particlesJS from "components/common/Particles/particles";
export default {
  data(){
    return {
      clientWidth: document.body.clientWidth,
      scrollHeight: document.documentElement.scrollHeight || document.body.scrollHeight,
    }
  },
  mounted(){
    // console.log(this.scrollHeight);
    // $(".dataItem").css("height",(this.scrollHeight-18)+'px');
  },
  computed:{
    onXS(){
      if(this.clientWidth < 768){
        // console.log(this.clientWidth);
        return 0;
      }
      return 12;
    },
    toOther(){
      if(this.$route.path == '/other/data_center'){
        return true;
      }
      return false;
    }
  },
  methods:{

  },
  components:{
    particles:particlesJS,
  }
}
</script>

<style>

  .pagehead{
    margin-bottom: 12px;;
  }
  .pagehead2{
    margin-bottom: 8px;
  }
  .pagehead2>h2{
    font-size: 16px;
    font-weight: 500;
    position: relative;;
    color: #FFF;
  }
  .pagehead>h1{
    font-size: 20px;
    color: #FFF;
    font-weight: 500;
    position: relative;
  }
  .pagehead2>h2>small{
    position: absolute;
    right: 0;
    bottom: 0;
    font-size: 14px;
  }
  .pagehead>h1>small{
    /* position: absolute; */
    right: 0;
    float: right;
    bottom: 2px;
    font-size: 16px;
    color: #FFF;
  }

  .datacenter{
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 50% 50%;
  }
  .particles{
    position: absolute;
    height: 80%;
    min-height: 730px;
    width: 80%;
    top: 10%;
    bottom: 10%;
    padding: 20px;
    background-color: rgba(0,0,0,.6);
    color: #FFF;
    border-radius: 5px;
  }
  .datacenter>.dataItem{
    position: absolute;
    height: 98%;
    min-height: 730px;
    width: 98%;
    top: 1%;
    padding: 12px;
    /* background-color: rgba(0,0,0,.6); */
    color: #FFF;
    border-radius: 5px;
  }
  
  @media screen and (max-width: 768px){
    .datacenter > .dataItem{
      background-color: transparent;
    }
  }

  .intro{
    position: absolute; 
    top: 43%;
    transform: translateY(-50%);
    left: 5%;
  }
  .intro>h1{
    font-size: 50px;
    font-weight: 500;;
  }
  .intro>p{
    color: #ddd;
    font-size: 18px;
    padding-left: 6px;
  }

  .dc-option{
    width: 100%;
  }

  .dc-option ul {
    margin: 0;
    padding: 0;
  }
  .dc-option ul {
    list-style: none;
    width: 550px;
    margin: 100px auto;
  }
  .dc-option li {
    float: left;
    margin: 0 5px;
    /* height: 96px; */
    position: relative;
    overflow: hidden;
    transition: all .2s ease-in-out;
    top: 0;
  }
  .dc-option li:hover{
    top: -10px;
  }
  .dc-option .hex {
    overflow: hidden;
    display: block;
    width: 130px;
    height: 150.8px;
    transform: rotate(-60deg) skewY(30deg);
  }
  .dc-option .hexIn {
    background: linear-gradient(45deg,#273c75,#192a56);
    display: block;
    width: 130px;
    height: 150.8px;
    line-height: 150.8px;
    transition: all .2s ease-in-out;
    text-align: center;
    transform: skewY(-30deg) rotate(60deg);
    color: #bbb;
  }
  .dc-option .hexIn:hover{
    background: #fff;
  }
  .hexIn>span>span{
    font-size: 42px;
    width: 100%;
    /* color: #333; */
  }
  .hexIn>span>p{
    font-size: 16px;
    /* color: #333; */
    margin-top: -125px;
    top: 0;
  }
  .dc-option li:nth-child(6n + 4) {
    margin-left: 75px;
  }
  .dc-option li:nth-child(n+4){
    margin-top: -25px;
  }



</style>