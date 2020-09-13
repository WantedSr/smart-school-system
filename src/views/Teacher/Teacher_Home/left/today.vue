<template>
  <div class="left_today box" v-loading="loading">
    <el-row>
      <el-col :lg="24" class="head">
        <h4 class="por">今日待办<small class="poa">TO-DO Today</small></h4>
      </el-col>
      <el-col class="mywork" :span="24">
        <ul>
          <li class="mwItem">
            <el-link class="por" href="/teacher/today" target="_blank" :underline="false">
              <div>
                <h4>早上三四节复习数学</h4>
                <p><span>早上</span><span>第三节</span></p>
              </div>
              <div class="poa">
                <el-tag type="danger" size="mini">未完成</el-tag>
                <p>2020/04/22</p>
              </div>
            </el-link>
          </li>
          <li class="mwItem">
            <el-link class="por" href="/teacher/today" target="_blank" :underline="false">
              <div>
                <h4>中午午休时间上传证书</h4>
                <p><span>下午</span><span>午休</span></p>
              </div>
              <div class="poa">
                <el-tag type="primary" size="mini">待完成</el-tag>
                <p>2020/04/22</p>
              </div>
            </el-link>
          </li>
          <li class="mwItem">
            <el-link class="por" href="/teacher/today" target="_blank" :underline="false">
              <div>
                <h4>下午第七节课学生会开会</h4>
                <p><span>下午</span><span>第七节</span></p>
              </div>
              <div class="poa">
                <el-tag type="success" size="mini">完成</el-tag>
                <p>2020/04/22</p>
              </div>
            </el-link>
          </li>
        </ul>
      </el-col>
      <el-col :lg="24" class="more">
        <el-link :underline="false" href="/teacher/today" target="_blank">查看更多</el-link>
      </el-col>
    </el-row>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      loading: false,
    }
  },
  props:{
    show:{
      type: Boolean,
      default: false,
    },
  },
  methods:{
    toLink(){
      this.$router.push("/student/message/xxxxx");
    },
    getToDo(){
      this.loading = true;
      requestAjax({
        url: "/affairs.php",
        type: "post",
        data:{
          action: 'today',
          type: 'tea',
          userid: this.$store.state.userId,
        },
        async: true,
        success:res=>{
          console.log(res);
          this.loading = false;
        },
        error:err=>{
          console.error(err);
          this.loading = false;
          this.$notify.error({
            title: '服务器出错',
            message: '请求数据出现错误，请稍后再试或联系管理员',
          });
        }
      })
    },
  }
}
</script>

<style scoped>
  /* ------------------------------------mywork-------------------------------------- */
  .head{
    margin-bottom: 20px;
  }
  .left_today{
    height: 295px;
  }
  .mwItem{
    width: 100%;
    border-bottom: 1px solid #f5f5f5;
    transition: all .2s;
    margin-bottom: 3px;
  }
  .mwItem:hover{
    border-bottom: 1px solid #409EFF;
    background-color: rgba(52, 152, 219,.1)
  }
  .mwItem>a{
    display: block;
    width: 100%;
    padding: 6px;
  }
  .mwItem h4{
    font-size: 18px;
    font-weight: 500;
    color: #666;
    margin-bottom: 2px;
    width: 100%;
    overflow: hidden;
    text-overflow:ellipsis;
    padding-right: 80px;
    white-space: nowrap;
  }
  .mwItem p span{
    font-size: 14px;
    color: #bbb;
    margin-right: 12px;
  }
  .mwItem>a>span>div:nth-child(2){
    right: 6px;
    color: #999;;
    font-size: 14px;
    bottom: 6px;
    text-align: right;
  }
  .mwItem>a>span>div:nth-child(2)>p{
    margin-top: 3px;
  }

  .left_today .more{
    text-align: center;
    padding: 6px 0;
  }

</style>