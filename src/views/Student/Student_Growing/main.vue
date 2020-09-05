<template>
  <div class="growing box">
    <el-page-header @back="goHome" content="我的成长档案"></el-page-header>
    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="选择学期">
        <el-select @change="selSeme" v-model="sel.semester" placeholder="学期">
          <el-option label="2019-2020下学期" value="192002"></el-option>
          <el-option label="2019-2020上学期" value="192001"></el-option>
          <el-option label="2018-2019下学期" value="181902"></el-option>
          <el-option label="2018-2019上学期" value="181901"></el-option>
        </el-select>
      </el-form-item>
    </el-form>
    <div class="growingBody" v-if="sel.semester != ''">
      <p><span>姓名：{{username}}</span><span>学号：{{user_id}}</span><span>班级：{{classroom}}</span></p>
      <el-timeline>
        <el-timeline-item timestamp="课堂表现" placement="top">
          <el-card>
            <el-table
              :data="classType"
              style="width: 100%">
              <el-table-column
                prop="bj"
                label="病假">
              </el-table-column>
              <el-table-column
                prop="cd"
                label="迟到">
              </el-table-column>
              <el-table-column
                prop="gj"
                label="公假">
              </el-table-column>
              <el-table-column
                prop="kk"
                label="旷课">
              </el-table-column>
              <el-table-column
                prop="sj"
                label="事假">
              </el-table-column>
              <el-table-column
                prop="zt"
                label="早退">
              </el-table-column>
              <el-table-column
                prop="jh"
                label="讲话">
              </el-table-column>
              <el-table-column
                prop="sj"
                label="玩手机">
              </el-table-column>
              <el-table-column
                prop="yx"
                label="玩游戏">
              </el-table-column>
            </el-table>
          </el-card>
        </el-timeline-item>
        <el-timeline-item timestamp="职业素养分" placement="top">
          <el-card>
            <el-table
              :data="profession"
              style="width: 100%">
              <el-table-column
                prop="more"
                label="总分">
              </el-table-column>
              <el-table-column
                prop="class"
                label="课堂">
              </el-table-column>
              <el-table-column
                prop="homework"
                label="作业">
              </el-table-column>
              <el-table-column
                prop="jl"
                label="奖励">
              </el-table-column>
              <el-table-column
                prop="cf"
                label="处罚">
              </el-table-column>
              <el-table-column
                prop="ss"
                label="宿舍">
              </el-table-column>
            </el-table>
          </el-card>
        </el-timeline-item>
        <el-timeline-item timestamp="学科成绩" placement="top">
          <el-card>
            <el-table
              :data="score"
              style="width: 100%">
              <el-table-column
                prop="class"
                label="课程">
              </el-table-column>
              <el-table-column
                prop="ps"
                label="平时">
              </el-table-column>
              <el-table-column
                prop="qz"
                label="期中">
              </el-table-column>
              <el-table-column
                prop="qm"
                label="期末">
              </el-table-column>
              <el-table-column
                prop="more"
                label="总评">
              </el-table-column>
              <el-table-column
                prop="score"
                label="学分">
              </el-table-column>
            </el-table>
          </el-card>
        </el-timeline-item>
        <el-timeline-item timestamp="评价" placement="top">
          <el-card>
            <div class="pj"><span>学生自评：</span><p>我是一个热爱学习的三好青年，我拥戴中国共产党！</p></div>
            <div class="pj"><span>家长评价：</span><p>你是一个热爱学习的三好青年，你拥戴中国共产党！</p></div>
            <div class="pj"><span>班主任评价：</span><p>他是一个热爱学习的三好青年，他拥戴中国共产党！</p></div>
          </el-card>
        </el-timeline-item>
      </el-timeline>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      sel:{
        semester: "",
      },
      username: '李某某',
      user_id: '2017217041',
      classroom: "19软件1班",
      classType:[
        {
          bj: 0,
          cd: 0,
          gj: 0,
          kk: 0,
          sj: 0,
          zt: 0,
          jh: 0,
          sj: 0,
          yx: 0,
        }
      ],
      profession:[
        {
          more: 150,
          class: "-10",
          homework: "-10",
          jl: 0,
          cf: 0,
          ss: 0,
        }
      ],
      score: [
        {
          class: "语文",
          ps: 100,
          qz: 100,
          qm: 100,
          more: 100,
          score: 2,
        },
        {
          class: "数学",
          ps: 100,
          qz: 100,
          qm: 100,
          more: 100,
          score: 2,
        },
        {
          class: "英语",
          ps: 100,
          qz: 100,
          qm: 100,
          more: 100,
          score: 2,
        },
        {
          class: "计算机基础",
          ps: 100,
          qz: 100,
          qm: 100,
          more: 100,
          score: 2,
        },
        {
          class: "体育",
          ps: 100,
          qz: 100,
          qm: 100,
          more: 100,
          score: 2,
        },
      ]
    }
  },
  methods:{
    goHome(){
      this.$router.push("/student/home");
    },
    selSeme(){
      this.$router.push({
        query:{
          semester: this.sel.semester,
        }
      })
    }
  },
} 
</script>

<style>
  .growing{
    max-width: 1170px;
    margin: 0 auto;
  }
  .growing>.el-page-header{
    padding-bottom: 6px;
    margin-bottom: 24px;
  }
  .growingBody{
    padding: 12px 24px;
  }
  .growingBody>p{
    margin-bottom: 24px;
    color: #666;;
  }
  .growingBody>p>span{
    margin-right: 24px;
  }
  .growingBody .pj{
    margin-bottom: 16px;
    font-size: 16px;
    color: #333;;
  }
  .growingBody .pj span{
    color: #666;
    margin-bottom: 6px;
    display: block;
  }
  .growingBody .pj p{
    text-indent: 2em;
  }
  
</style>