<template>
  <div>

    <div class="pagehead">
      <h1>配置审批表单</h1>
    </div>

    <div class="box">
      <el-table
        :data="tableData"
        border 
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        size="mini"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="40">
        </el-table-column>
        <el-table-column
          prop="approval_name"
          label="申请名称"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="approvers"
          label="审批人"
          min-width="250"
          sortable>
          <template v-slot="scope">
            <span v-if="scope.row.approval_type == '统一设置'">{{getTea(scope.row.approvers)}}</span>
            <span v-else-if="scope.row.approval_type == '分部设置'">
              <div v-for="(item,i) in scope.row.approvers" :key="'dep-'+i">
                <b>{{item.dep_name}}:&nbsp;&nbsp;&nbsp;</b>
                {{ getTea(item.dep_teacher) }}
              </div>
              <!-- 123 -->
            </span>
          </template>
        </el-table-column>
        <el-table-column
          prop="queue"
          label="流程顺序"
          min-width="90"
          sortable>
          <template v-slot="scope">
            <el-input type="number" placeholder="审批流程" size="mini" min='0' v-model="scope.row.queue"></el-input>
          </template>
        </el-table-column>
        <el-table-column
          prop="end"
          label="可结束"
          min-width="120"
          sortable>
          <template slot-scope="scope">
            <el-select size="mini" v-model="scope.row.end" placeholder="是否可结束">
              <el-option label="可结束" value="1"></el-option>
              <el-option label="不可结束" value="0"></el-option>
            </el-select>
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="100"
          sortable>
          <template v-slot="scope">
            <el-input type="number" placeholder="审批金额" size="mini" min='0' v-model="scope.row.money"></el-input>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <div style="margin-top: 24px">
      <el-button size="small" @click="onBack">返回</el-button>
      <el-button type="primary" @click="onSet" size="small">提交</el-button>
    </div>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{

      n: this.$route.query.n,
      tableData:[],
      
      multipleTable: [],
      sum: 0,
      page: 1,
      total: 50,

      loading: false,
    }
  },
  created(){
    this.onSubmit();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Form.php",
        data: {
          type: "sel_approval",
          form: this.$route.query.proId,
          campus: this.$store.state.userCampus,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
          console.log(res);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    onSubmit(){
      this.getData();
    },
    setPage(page){  
      this.page = page;
      this.onSubmit()
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onDel(){
      let arr = [];
      $.each(this.multipleTable, function (i, v) { 
        arr.push(v.id);
      });
      if(arr.length != 0){
        this.$confirm('是否确定删除该记录？', '确认删除？', {
          distinguishCancelAndClose: true,
          confirmButtonText: '确定',
          cancelButtonText: '放弃'
        })
        .then(()=>{
          requestAjax({
            url: "/Office/Process/Approval.php",
            type: 'post',
            data:{
              type: "del_approval",
              arr: arr,
              sel: 'id',
            },
            async: false,
            success:(res)=>{
              res = JSON.parse(res);
              // console.log(res);
              if(res){
                this.$message({
                  message: '删除成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "删除组织架构信息 "+ JSON.stringify(arr),
                  type: "删除记录",
                  model: "组织架构模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);
                
                this.onSubmit();
              }else{
                this.$message.error('删除失败，请稍后再试！');
              }
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            }
          })
        })
        .catch(action=>{
          this.$message('取消操作');
        })
      }else{
        this.$message.error('请选择选项后在进行操作！');
      }
    },
    setState(id,state){
      this.loading = true;
      requestAjax({
        type: 'post',
        url: "/Office/Form.php",
        data:{
          type: "upa_form",
          obj: {
            state: state == '1' ? '0' : '1',
          },
          sel: 'id',
          val: id,
        },
        success:(res)=>{
          res = JSON.parse(res);
          if(res){
            this.$message({
              message: '恭喜你，修改成功',
              type: 'success'
            });
          
            // 日志写入
            let obj = {
              content: "修改审批表单状态 表单"+ id,
              type: "修改记录",
              model: "审批表单模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);

            this.onSubmit();
          }
          else{
            this.$message.error('修改失败，请稍后再试！');
          }
        }
      })
    },
    onSet(){
      let arr = [];
      $.each(this.tableData,(i, v)=>{ 
        let obj = {
          form_id: this.$route.query.proId,
          approval_id: v.approval_id,
          queue: v.queue,
          end: v.end,
          money: v.money,
          campus: this.$store.state.userCampus,
          school: this.$store.state.userSchool,
          created_user: this.$store.state.userId,
          addTime: new Date().getTime()
        };
        arr.push(obj);
      });
      this.loading = true;
      requestAjax({
        type: "post",
        url: "/Office/Form.php",
        data:{
          type: "set_approval",
          arr: arr,
          campus: this.$store.state.userCampus,
          form: this.$route.query.proId,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          let errArr = [];
          $.each(res,(i, v)=>{ 
            if(!v){
              errArr.push(this.tableData[i].approval_name);
            }
          });
          if(errArr.length <= 0){
            this.$message({
              message: '设置成功',
              type: 'success'
            });
          
            // 日志写入
            let obj = {
              content: "设置审批流程信息" + JSON.stringify(this.$route.query.proId),
              type: "修改记录",
              model: "审批流程模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);
            this.$router.go(-1);
          }
          else if(errArr.length > 0 && errArr.length < res.length){
            this.$message({
              message: '部分数据设置有误，请打开F12->console查看',
              type: 'warning'
            });
            console.error(errArr.join('-')+'数据设置有误!');
            console.log('可尝试稍后再设置或者联系网络管理员!');
            this.$router.go(-1);
          }else{
            this.$message.error('设置失败，请稍后再试！');
          }
          console.log(res);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
  },
  computed:{
    getTea(){
      return (teaData)=>{
        if(teaData.length > 0){
          return teaData.join("-");
        }
        return '-';
      }
    }
  },
  components:{
  }
} 
</script>

<style>

</style>