<template>
  <div class="news box">
    <el-row>
      <el-col :lg="24" class="newshead">
        <el-tabs v-model="activeName" @tab-click="handleClick">
          <el-tab-pane label="全部" name="all">
            <!-- 最多三条 -->
            <div>
              <el-row>
                <el-col :lg="24" class="por newsItem">
                  <el-row>
                    <el-col :lg="20" :md="16" :xs="16">
                      <p><a href="#">云端流淌的抗“疫”温度 学院推出辅导员线上思政微课程</a></p>
                    </el-col>
                    <p class="poa">20/04/02 19:09:12</p>
                  </el-row>
                </el-col>
                <el-col :lg="24" class="por newsItem">
                  <el-row>
                    <el-col :lg="16" :md="16" :xs="16">
                      <p><a href="#">学院经济学院：技能大赛转战线上培训，疫情期间备赛不松懈</a></p>
                    </el-col>
                    <p class="poa">20/04/02 19:09:12</p>
                  </el-row>
                </el-col>
                <el-col :lg="24" class="por newsItem">
                  <el-row>
                    <el-col :lg="16" :md="16" :xs="16">
                      <p><a href="#">市卫生监督局一行到校指导疫情防控工作</a></p>
                    </el-col>
                    <p class="poa">20/04/02 19:09:12</p>
                  </el-row>
                </el-col>
                <el-col :lg="24" class="more">
                  <el-link :underline='false' href="#">查看更多</el-link>
                </el-col>
              </el-row>
            </div>
          </el-tab-pane>
          <el-tab-pane label="校园资讯" name="0">
            <div class="col-lg-12 nodata">
              <p>暂无公告</p>
            </div>
          </el-tab-pane>
          <el-tab-pane label="专业动态" name="1"></el-tab-pane>
          <el-tab-pane label="信息公开" name="2"></el-tab-pane>
          <el-tab-pane label="政策法规" name="3"></el-tab-pane>
          <el-tab-pane label="党建园地" name="4"></el-tab-pane>
          <el-tab-pane label="豆丁之家" name="5"></el-tab-pane>
          <el-tab-pane label="园所风采" name="6"></el-tab-pane>
        </el-tabs>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  data(){
    return{
      activeName: "all",
    }
  },
  methods:{
    handleClick(tab, event) {
      console.log(tab, event);
    }
  }
}
</script>

<style>
  /* -----------------news-------------------- */

  .news{
    height: 230px;
  }
  .newshead{
    margin-bottom: 20px;
  }
  .newshead>ul{
    width: 800px;
  }

  .newsItem{
    border-bottom: 1px solid #f5f5f5; 
    margin-bottom: 12px;
    position: relative;
  }
  .newsItem::before{
    content:"";
    display: block;
    width: 10px;
    height: 10px;
    position: absolute;
    left: 15px;
    top: 5px;
    background-color: #2ecc71;
  }
  .newsItem>div>div>p{
    text-align: left;
    font-size: 14px;
    overflow: hidden;
    color: #666;
    text-overflow:ellipsis;
    white-space: nowrap;
    padding-left: 30px; 
    margin-bottom: 5px;
  }
  .newsItem>div p a{
    color: #666;
    text-decoration: none;;
  }
  .newsItem>div p > a:hover{
    color: #3498db;
  }
  .newsItem>div>p{
    right: 12px;
    text-align: right;
    font-size: 12px;
    top: 4px;
    color: #aaa;
  }
  .news .more{
    text-align: center;
  }
</style>