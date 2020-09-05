<template>
  <div class="selTeacherInfo">
    <div class="pagehead">
      <h1>教师资格证查询</h1>
    </div>
    <div style="margin-bottom: 24px">
      <el-row>
        <el-col :lg="18" :md="18" :sm="16" :xs="12">      
          <el-button type="danger" size="small" @click="onDel">删除</el-button>
        </el-col>
        <el-col :lg="6" :md="6" :sm="8" :xs="12">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="teaInfoTable box">
      <el-table
        :data="tableData.filter(data => !search || data.teacher_name.toLowerCase().includes(search.toLowerCase()) || data.qualification_name.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        size="mini"
        border
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          width="50"
          type="selection">
        </el-table-column>
        <el-table-column
          prop="username"
          label="教师"
          sortable>
        </el-table-column>
        <el-table-column
          prop="qualification_name"
          sortable
          min-width="150"
          label="证书名称">
        </el-table-column>
        <el-table-column
          prop="qualification_id"
          sortable
          min-width="100"
          label="证书编号">
        </el-table-column>
        <el-table-column
          prop="qualification_profession"
          sortable
          min-width="95"
          label="教学专业">
        </el-table-column>
        <el-table-column
          prop="qualification_type"
          sortable
          min-width="150"
          label="证书种类">
        </el-table-column>
        <el-table-column
          prop="qualification_mechanism"
          sortable
          min-width="130"
          label="颁发机构">
        </el-table-column>
        <el-table-column
          prop="qualification_time"
          sortable
          min-width="100"
          label="颁发时间">
        </el-table-column>
        <el-table-column
          prop="created_user"
          sortable
          min-width="100"
          label="创建人">
        </el-table-column>
        <el-table-column
          prop="addTime"
          label="创建时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          min-width="180"
          label="操作">
          <template v-slot="scope">
            <el-button size="mini" @click="seeCertificate(scope.row.certificate_img)">查看证书</el-button>
            <el-button size="mini" type="primary" @click="onUpa(scope.row.id)">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
    <el-dialog
      title="证书图片"
      :visible="dialogVisble"
      width="50%"
      center
      :append-to-body="true"
      :before-close="handleClose">
      <el-image
      :src="src"
      fit="fill">
        <div slot="placeholder" class="image-slot">
          加载中<span class="dot">...</span>
        </div>
        <div slot="error" class="error-image-slot">
          <i class="el-icon-picture-outline"></i>
          暂无图片
        </div>
      </el-image>
    </el-dialog>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax"; 
import Limit from "../../../Limit/main";
export default {
  data(){
    return{
      tableData:[],
      n: this.$route.query.n,
      search: "",
      multipleTable: [],
      sum: 0,
      page: 1,
      total: 15,
      loading: false,
      dialogVisble: false,
      src: "https://cube.elemecdn.com/6/94/4d3ea53c084bad6931a56d5158a48jpeg.jpeg",
    }
  },
  computed:{
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
    }
  },
  created(){
    this.getData();
    this.getDataSum();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(){
      this.search = '';
      let start = parseInt(this.page - 1) * this.total;
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaQualification.php",
        data:{
          type: "sel_limit_tea",
          start: start,
          num: this.total,
          selobj: {
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.tableData = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getDataSum(){
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/TeaQualification.php",
        data:{
          type: "sel_tea",
          col: "COUNT(*)",
          selobj: {
            'campus':this.$store.state.userCampus,
          }
        },
        success:(res)=>{
          // console.log(res);
          res = JSON.parse(res)[0]["COUNT(*)"];
          // this.loading = false;
          this.sum = parseInt(res);
        },
        error:(err)=>{
          // this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: "upa",
          quaId: id,
        }
      });
    },
    seeCertificate(id){
      this.src = id;
      this.dialogVisble = true;
    },
    handleClose(done) {
      this.dialogVisble = false;  
    }
  },
  components:{
    Limit,
  }
}
</script>

<style scope>
  .error-image-slot{
    width: 100%;
    /* min-height: 300px; */
    text-align: center;
  }
  .el-dialog--center .el-dialog__body{
    text-align: center;
    /* line-height: 300px; */
  }
</style>