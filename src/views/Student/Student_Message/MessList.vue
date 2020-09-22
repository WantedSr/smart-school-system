<template>
  <div class="MessList por box">
    <el-page-header @back="goBack" content="我的通知"></el-page-header>
    <el-table
      :data="showTable"
      size="small"
      v-loading="loading"
      stripe
      ref="multipleTable"
      tooltip-effect="dark"
      @selection-change="handleSelectionChange"
      element-loading-text="拼命加载中"
      element-loading-spinner="el-icon-loading"
      element-loading-background="rgba(0, 0, 0, 0.8)"
      style="width: 100%">
      <el-table-column
        prop="date"
        label="发布日期"
        sortable
        min-width="100"
        column-key="date">
        <template v-slot="scope">
          {{ getDate(scope.row.addTime) }}
        </template>
      </el-table-column>
      <el-table-column
        prop="title"
        min-width="250"
        label="通知名称">
        <template v-slot="scope">
          <p size="small" href="javascript:;" @click="toLink(scope.row.message_id,scope.row.type)" :underline="false">{{scope.row.title}}</p>
        </template>
      </el-table-column>
      <el-table-column
        prop="read"
        label="情况"
        min-width="100">
        <template v-slot="scope">
          <el-tag size="small" :type="scope.row.read > 0 ? '' : 'success'">{{ scope.row.read > 0 ? '已读' : 'new' }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column
        prop="type"
        label="类别"
        min-width="100">
        <template v-slot="scope">
          <el-tag size="small" :type="scope.row.type == 'message' ? 'primary' : 'danger'">{{ scope.row.type == 'message' ? '通知' : '作业' }}</el-tag>
        </template>
      </el-table-column>
    </el-table>
    <div class="pagination poa">
      <el-row>
        <el-col :span="4">
          <p style="color: #999;font-size: 14px">总共{{sum}}条记录</p>
        </el-col>
        <el-col :span="19" style="text-align: right">
          <el-pagination
            background
            layout="prev, pager, next"
            :pager-count="5"
            :page-size="total"
            @current-change="setPage"
            :small="true"
            :hide-on-single-page="false"
            :total="sum">
          </el-pagination>
        </el-col>
      </el-row>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      tableData: [],
      showTable: [],
      loading: false,

      page: 1,
      total: 50,
    }
  },
  created(){
    this.getMessage();
  },
  methods:{
    setPage(page){
      this.page = page;
      let start = ((this.page-1)*this.total);
      let end;
      if(start + this.total >= this.tableData.length){
        end = this.tableData.length;
      }else{
        end = start + this.total;
      }
      this.showTable = [];
      for(let i = start;i < end ; i++){
        this.showTable.push(this.tableData[i]);
      }
    },
    goBack(){
      this.$router.push("/student/home");
    },
    getMessage(){
      this.loading = true;
      requestAjax({
        type: 'post',
        url: "/msg.php",
        data:{
          action: "getMessageList",
          type: 'student',
          request: JSON.stringify({
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
            grade: this.$store.state.userGrade,
            class: this.$store.state.userClass,
            userid: this.$store.state.userId,
            semester: this.$store.state.semester,
          }),
        },
        async: true,
        success:res=>{
          this.loading = false;
          res = JSON.parse(res);
          if(res.data.length > 0){
            this.tableData = res.data;
            this.setPage(1);
          }
          console.log(res);
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
    toLink(id,type){
      this.$router.push({
        path: "/student/message/"+id,
        query:{
          type: type,
        }
      });
    }
  },
  computed:{
    sum(){
      return this.tableData.length;
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
  },
}
</script>

<style scoped>
  .MessList{
    min-height: 755px;
    overflow: hidden;
    padding-bottom: 80px;
  }
  .MessList>.el-page-header{
    margin-bottom: 24px;
  }
  .pagination{
    margin: 0 auto;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
  }
  @media screen and (max-width:768px){
    /* .pagination{
      width: 110%;
      position: relative;
      left: -5%;
      margin-bottom: 24px;
    } */
  }
</style>