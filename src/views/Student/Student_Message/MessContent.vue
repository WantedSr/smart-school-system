<template>
  <div class="messContent" v-loading="loading">
    <div class="mess box">
      <div class="messdo">
        <el-row>
          <el-col :lg="12" :xs="24" style="margin-bottom: 12px">      
            <el-page-header @back="goMess" content="我的通知"></el-page-header>
          </el-col>
        </el-row>
      </div>
      <div class="messmain">
        <div class="messtitle">
          <h2>{{ msgData['title'] }}</h2>
          <p>{{ getTarget(msgData['target']) }}&nbsp;&nbsp;{{ msgType == 'message' ? '通知' : msgType == 'homework' ? '作业' : '其它' }}&nbsp;&nbsp;发布时间：{{ getDate(msgData['addTime']) }} {{ getTime(msgData['addTime']) }}&nbsp;&nbsp;阅读次数：{{ msgData['read_num'] }}</p>
        </div>
        <div class="messbody">
          <div v-html="msgData['content']"></div>
        </div>
      </div>
      <div class="MessBeside" v-if="msgData['annex'] != undefined && msgData['annex'] != null && msgData['annex'] != ''">
        <ul>
          <li>
            <a target="_blank" :href="$store.state.endUrl + '/Message/annex/' + msgData['annex']['filename']"><span>附件下载<i class="el-icon-download"></i></span></a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      msgId: this.$route.params.msg_id,
      loading: false,
      msgData: {
        title: "",
        target: "",
        addTime: "",
        read_num: "",
        content: '',
        annex: "",
      },
      msgType: this.$route.query.type,
      haveMsg: false,
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      url: "/msg.php",
      type: "post",
      data:{
        action: "getMessage",
        request: JSON.stringify({
          campus: this.$store.state.userCampus,
          department: this.$store.state.userDepartment,
          grade: this.$store.state.userGrade,
          class: this.$store.state.userClass,
          userid: this.$store.state.userId,
          semester: this.$store.state.semester,
        }),
        type: 'student',
        msgType: this.$route.query.type,
        msgId: this.msgId,
      },
      async: true,
      success:res=>{
        res = JSON.parse(res);
        console.log(res.data);
        if(res.data.length < 0){
          this.$rout.go(-1);
          this.$message({
            message: "路由有误，请重新进入",
            type: 'warning',
          });
          return;
        }else{
          this.msgData = res.data[0];
          this.haveMsg = true;
        }
        this.readMsg();
        this.loading = false;
      },
      error:err=>{
        this.loading = false;
        console.error(err);
        this.$notify.error({
          title: '错误',
          message: '服务器有误！,请稍后再试！'
        });
      }
    })
  },
  methods:{
    goMess(){
      location.href = '/student/message';
    },
    readMsg(){
      requestAjax({
        url: "/msg.php",
        type: "post",
        data:{
          action: 'readMessage',
          arr: JSON.stringify([
            this.msgId,
            this.$store.state.userId,
            this.$store.state.userCampus,
            this.$store.state.userSchool,
            this.$store.state.userId,
            new Date().getTime(),
          ]),
        },
        async: true,
        success:res=>{
          res = JSON.parse(res);
          console.log(res);
          if(res.data == 'repeat'){
            return;
          }
          else if(res.data){
            this.$message({
              message:'消息阅读记录成功',
              type: 'success',
            });
          }
          else{
            this.$message.error('消息阅读记录失败，请尝试重新进入页面');
          }
        },
        error:err=>{
          console.err(err);
          this.$message.error("消息已读记录失败,请尝试重新进入页面");
        }
      });
    }
  },
  computed:{
    sum(){
      return this.tableData.length;
    },
    getTarget(){
      return (t)=>{
        switch(t){
          case "class":
            return "班级"
            break;
          case "grade":
            return "年级"
            break;
          case "department":
            return "部门"
            break;
          case "campus":
            return "校区"
            break;
        }
      }
    },
    getDate(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        m = String(m).length == 1 ? '0'+m : m;
        let d = date.getDate();
        d = String(d).length == 1 ? '0'+d : d;
        return y+"-"+m+"-"+d;
      }
    },
    getTime(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let h = date.getHours();
        let m = date.getMinutes();
        m = String(m).length == 1 ? '0'+m : m;
        let se = date.getSeconds();
        se = String(se).length == 1 ? '0'+se : se;
        return h+":"+m+":"+se;
      }
    },
  },
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