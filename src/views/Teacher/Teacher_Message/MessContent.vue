<template>
  <div class="messContent">
    <div class="mess box">
      <div class="messdo">
        <el-row>
          <el-col :lg="12" :xs="24" style="margin-bottom: 12px">      
            <el-page-header @back="goMess" content="我的通知"></el-page-header>
          </el-col>
          <el-col :lg="12" :xs="24" class="messsize">
            <el-button size="small">小</el-button>
            <el-button size="small">中</el-button>
            <el-button size="small">大</el-button>
          </el-col>
        </el-row>
      </div>
      <div class="messmain">
        <div class="messtitle">
          <h2>这是一篇示例文本</h2>
          <p>部门&nbsp;&nbsp;通知&nbsp;&nbsp;发布时间：2020-04-12 12:53:03&nbsp;&nbsp;阅读次数：167</p>
        </div>
        <div class="messbody">
          <p style="text-align:left;color:#333;text-indent:0">这是一篇示例文本:</p>
          <p style="padding: 0 24px">这是一篇示例文本这是一篇示例文本这是一篇示例文本这是一篇示例文本这是一篇示例文本
            根据宝安区教育局《关于开展“孩子心理危机识别与干预”家长培训的通知》要求，结合我校实际，决定组织教师及家长参加“孩子心理危机识别与干预”培训工作。现就有关事项通知如下：
          </p>
          <p style="padding: 0 6px">一、 培训时间</p>
          <p style="padding: 0 6px">4 月 13 日（周一）晚上8：00—9：30</p>
          <p style="padding: 0 6px">二、培训对象</p>
          <p style="padding: 0 6px">全体教师及学生家长</p>
          <p style="padding: 0 6px">三、培训方式</p>
          <p style="padding: 0 6px">主讲人：攸佳宁 （华南师范大学教授）</p>
          <p style="padding: 0 6px">（一）  可以按时收看宝安第一课电视频道；</p>
          <p style="padding: 0 6px">（二）  或者关注活动当天“宝安教育”微信公众号平台推送的活动链接，扫描活动二维码在线收看。</p>
          <p style="padding: 0 6px">这是一篇示例文本这是一篇示例文本这是一篇示例文本这是一篇示例文本这是一篇示例文本
            这是一篇示例文本这是一篇示例文本这是一篇示例文本这是一篇示例文本这是一篇示例文本
          </p>
          <br>
          <br>
          <br>
          <br>
          <p style="text-align:right;">这是一篇示例文本</p>
          <p style="text-align:right;">2020年4月13日</p>
        </div>
      </div>
      <div class="MessBeside">
        <ul>
          <li>
            <a href="#"><span>附件:</span>这是一段示例文本</a>
          </li>
          <li>
            <a href="#"><span>附件:</span>这是一段示例文本</a>
          </li>
        </ul>
      </div>
      <div class="MessDo">
        <el-link :underline="false"><i class="el-icon-download"></i> 打印</el-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      
    }
  },
  methods:{
    goMess(){
      this.$router.push("/student/message");
    }
  }
}
</script>

<style>
  .mess>.el-page-header{
    border-bottom: 1px solid #ccc;;
    padding-bottom: 5px;
    margin-bottom: 10px;
  }
  .mess>.el-breadcrumb{
    margin-bottom: 20px;
  }
  .messdo{
    margin-bottom: 20px;
  }
  .messsize{
    text-align: right;
  }
  .messmain{
    padding: 12px;
  }
  .messtitle{
    text-align: center;
    padding: 0 10px;
    margin-bottom: 30px;
  }
  .messtitle>h2{
    font-size: 26px;
    padding-bottom: 12px;
    font-weight: 500;
    color: #333;
    border-bottom: 1px solid #ccc;
  }
  .messtitle>p{
    margin-top: 6px;
    font-size: 14px;
  }
  .messContent{
    max-width: 1170px;
    margin: 0 auto;
  }
  .messbody{
    font-size: 21px;
    padding: 0 24px;
    color: #666;
  }
  .messbody>p{
    margin-bottom: 10px;
    line-height: 37px;
    text-indent: 2em;
  }
  .bread{
    margin-bottom: 30px;
  }

  
  .MessBeside{
    margin-top: 10px;
    margin-bottom: 20px;
  }
  .MessBeside li{
    margin-bottom: 4px;
  }
  .MessBeside a{
    border-radius: 3px;
    display: inline-block;
    padding: 6px;
    color: #FFF;
    background-color: #3498db;
    font-size: 14px;;
  }
  .MessBeside span{
    margin-right: 6px;
  }

  @media screen and (max-width:768px){
    .mess{
      padding: 6px;
    }
    .messsize{
      text-align: center;
    }
    .messmain{
      padding: 0;
    }
    .messtitle{
      padding: 0;
    }
    .messbody{
      padding: 0;
    }
    .messtitle>h2{
      font-size: 20px;
    }
    .messtitle>p{
      font-size: 12px;
    }
    .messbody>p{
      font-size: 16px;;
    }
  }

</style>