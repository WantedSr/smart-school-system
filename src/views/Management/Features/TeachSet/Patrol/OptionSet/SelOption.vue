<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>项目设置</h1>
    </div>

    <div style="margin-bottom: 12px">
      <el-button size="small" type="success" @click="onAdd">添加</el-button>
      <el-button size="small" type="danger" @click="onDel">删除</el-button>
    </div>

    <div class="selEvaluation" style="margin-bottom:12px">
      <el-table
        :data="tableData"
        size="mini"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        border
        style="width: 100%"
        >
        <el-table-column
          type="selection"
          fixed
          width="55">
        </el-table-column>
        <el-table-column
          prop="option_name"
          label="项目名称"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="num"
          label="选项数量"
          sortable>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          min-width="130"
          sortable>
          <template v-slot="scope">
            <el-button size="mini" @click="onOption(scope.row.option_id)" type="primary">选项</el-button>
            <el-button size="mini" @click="onUpa(scope.row.option_id)" type="info">编辑</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>

    <limit @setPage="setPage" :total="total" :sum="sum"></limit>

    <el-dialog title="添加项目" :visible.sync="dialogFormVisible">
      <el-form :model="form" ref="form" :rules="rules">
        <el-form-item prop="option_id" label="项目编号" :label-width="formLabelWidth">
          <el-input v-model="form.option_id" placeholder="请输入项目编号" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item prop="option_name" label="项目名称" :label-width="formLabelWidth">
          <el-input v-model="form.option_name" placeholder="请输入项目名称" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button size="small" @click="dialogFormVisible = false">取 消</el-button>
        <el-button size="small" type="primary" @click="onSubmit('form')">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
import Limit from "../../../Limit/main";
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{ 
      form: {
        option_id: "",
        option_name: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        created_user: this.$store.state.userId
      },

      n: this.$route.query.n,
      tableData: [],
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 50,
      loading: false,

      dialogFormVisible: false,
      
      rules:{
        option_id: [
          { required: true, message: '请输入项目编号', trigger: 'blur' },
        ],
        option_name: [
          { required: true, message: '请输入项目名称', trigger: 'blur' },
        ],
      },
    }
  },
  created(){
    this.getData();
  },
  computed:{
    getDate(){
      return (s=new Date().getTime())=>{
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
  methods:{
    setPage(page){
      this.page = page;
      this.getData()
    },
    getData(){
      this.loading = true;
      requestAjax({
        url: "/teach/Option.php",
        type: 'get',
        data:{
          type: "sel_limit_option",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj:{
            campus: this.$store.state.userCampus,
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.tableData = res;
          this.sum = res.length;
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
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: 'upa_option',
          optionId: id,
        }
      })
    },
    onOption(id){
      this.$router.push({
        query:{
          n: this.n,
          type: 'option_list',
          optionId: id,
        }
      })
    },
    onAdd(){
      requestAjax({
        type: 'get',
        url: "/teach/Option.php",
        data:{
          type: "get_option_id",
          campus: this.$store.state.userCampus,
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.form.option_id = res;
          this.form.option_name = "";
          this.dialogFormVisible = true;
          // console.log(res);
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.form.addTime = new Date().getTime();
          let arr = Object.values(this.form);

          requestAjax({
            type: "post",
            url: "/teach/Option.php",
            data: {
              type: "add_option",
              arr: arr,
            },
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '添加项目成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "添加项目 "+ this.form.option_id,
                  type: "添加记录",
                  model: "巡堂查课模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.dialogFormVisible = false;
                this.getData();
              }
              else{
                this.$message.error('添加失败，请稍后再试！');
              }
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
              });
            }
          })

        } else {
          console.log('error submit!!');
          return false;
        }
      });
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>