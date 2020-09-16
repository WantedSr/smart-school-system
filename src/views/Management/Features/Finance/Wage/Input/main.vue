<template>
  <div class="subpage" 
    v-loading="loading"
    element-loading-text="拼命加载中"
    element-loading-spinner="el-icon-loading"
    element-loading-background="rgba(0, 0, 0, 0.8)">
    <div class="pagehead">
      <h1>工资录入</h1>
    </div>

    <el-upload
      action="https://jsonplaceholder.typicode.com/posts/"
      class="upload-demo"
      ref="upload"
      accept=".xlsx, .xls"
      :on-preview="handlePreview"
      :on-remove="handleRemove"
      :file-list="fileList"
      :limit="1"
      :on-change="handle"
      :auto-upload="false">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传</el-button>
      <el-button style="margin-left: 10px;" size="small" @click="down" type="warning">下载模板</el-button>
      <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
    </el-upload>

    <div class="wage_warning">
      <el-alert
        title="注意:"
        type="warning"
        :closable="false"
        show-icon>
      </el-alert>
      <el-alert
        title="模板识别成功后，您需要核对该数据中的人员信息与系统中是否匹配，若模板识别错误请点击“下载模板”下载内置模板或联系技术支持"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="严格按照模板要求上传数据"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="导入的数据必须位于excel表格的sheet1表里:"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="导入的文件不得超过5M"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="如果导入数据有误，可通过重新上传进行覆盖，确保数据准确后再推送"
        :closable="false"
        type="warning">
      </el-alert>
    </div>

    <div class="wageTable">
      <el-table
        border 
        :data="excelData"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        stripe
        size="mini"
        style="width: 100%">
        <el-table-column width="30" type="index"></el-table-column>
        <el-table-column
          prop="filename"
          min-width="120"
          sortable
          label="文件名">
        </el-table-column>
        <el-table-column
          prop="imp_date"
          sortable
          min-width="100"
          label="导入时间">
          <template v-slot="scope">
            {{getDate(scope.row.addTime)}}
          </template>
        </el-table-column>
        <el-table-column
          prop="user_num"
          sortable
          label="推送人数">
        </el-table-column>
        <el-table-column
          prop=""
          sortable
          label="操作">
          <template v-slot="scope">
            <el-button size="mini" @click="onBack(scope.row.id)" type="danger">撤回</el-button>
            <el-button size="mini" @click="push(scope.row.id)" type="success">推送</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
import xlsx from "xlsx";
// const XLSX = require('xlsx');
import {readFile} from "assets/js/readFile";
export default {
  data(){
    return{

      
      form_imp:{},

      character:{
        teacher: {
          text: "教师编号",
          type: "String",
        },
        teacher_name: {
          text: "姓名",
          type: "String",
        },
        semester: {
          text: "所在学期",
          type: "String",
        },
        department: {
          text: "部门",
          type: "String",
        },
        user_id:{
          text: "身份证",
          type: "String",
        },
        job_wage:{
          text: "岗位工资",
          type: "Number",
        },
        salary_wage:{
          text: "薪级工资",
          type: "Number",
        },
        base_performance_wage:{
          text: "基础绩效工资",
          type: "Number",
        },
        special_salary_wage:{
          text: "特岗津贴",
          type: "Number",
        },
        teacher_age_wage:{
          text: "教护龄津贴",
          type: "Number",
        },
        house_wage:{
          text: "住房补贴",
          type: "Number",
        },
        only_child_wage:{
          text: "独生子女费",
          type: "Number",
        },
        one_day_donation:{
          text: "一日捐",
          type: "Number",
        },
        persion_insurance:{
          text: "养老保险金",
          type: "Number",
        },
        occupational_pension:{
          text: "职业年金",
          type: "Number",
        },
        house_fund:{
          text: "住房公积金",
          type: "Number",
        },
        unemployment_insurance:{
          text: "失业保险金",
          type: "Number",
        },
        medical_insurance:{
          text: "医疗保险金",
          type: "Number",
        },
        personal_income_tax:{
          text: "个人所得税",
          type: "Number",
        },
        reward_performance_deduction:{
          text: "奖励绩效扣除",
          type: "Number",
        },
        should_sum:{
          text: "应发小计",
          type: "Number",
        },
        should_deduct:{
          text: "应扣小计",
          type: "Number",
        },
        actual_sum:{
          text: "实发金额",
          type: "Number",
        },
        bank_card:{
          text: "卡号",
          type: "String",
        },
      },

      excelData: [],

      
      sum: 0,
      page: 1,
      total: 15,

      tableData:[],

      filename: "",

      loading: false,
    }
  },
  created(){
    this.onSubmit();
  },
  methods:{
    setPage(page){
      this.page = page;
      this.onSubmit()
    },
    // 采集 EXCEL 的数据
    async handle(ev){

      this.filename = ev.name;
      this.loading = true;

      let file = ev.raw;
      if(!file) return false;

      // 读取FILE 中的数据
      let data = await readFile(file);    // 获取文件的二进制数据
      let workbook = xlsx.read(data,{ type: 'binary' });   // 通过xlsx 插件生成一个excel 目录

      // 拿到第一表的数据
      let worksheet = workbook.Sheets[workbook.SheetNames[0]]
      // 将表的是数据进行JSON格式化（插件自带的功能）
      data= xlsx.utils.sheet_to_json(worksheet);

      let arr = data.map((value,index,arr)=>{
        let obj = {};
        for(let key in this.character){
          if(!this.character.hasOwnProperty(key)) break;
          let v = this.character[key],
            text = v.text,
            type = v.type;
          v = value[text] || "";
          type === 'String' ? (v = String(v)) : null;
          type === 'Number' ? (v = Number(v)) : 0;
          obj[key] = v;
        }
        obj['school'] = this.$store.state.userSchool;
        obj['campus'] = this.$store.state.userCampus;
        obj['created_user'] = this.$store.state.userId;
        obj['addTime'] = new Date().getTime();
        return obj;
      });

      this.excelData = arr;
      this.loading = false;
      console.log(this.excelData);
    },
    // 上传服务器
    submitUpload(){
      if(this.excelData.length == 0){
        this.$message({
          message: '请上传具有实际意义的文件！',
          type: 'warning'
        });
      }else{
        requestAjax({
          type: 'post',
          url: "/Finance/revenue.php",
          data:{
            type: 'imp_wage',
            arr: this.excelData,
            file: this.filename,
            semester: this.$store.state.semester,
            department: this.$store.state.userDepartment,
            campus: this.$store.state.userCampus,
            school: this.$store.state.userSchool,
            user: this.$store.state.userId,
          },
          success:(res)=>{
            res = JSON.parse(res);
            console.log(res);
            if(res[0]){
              let arr = [];
              $.each(res[1], (i, v)=>{ 
                if(!v){
                  arr.push((i+1));
                }
              });
              if(arr.length != 0){
                this.$message({
                  message: '导入部分失败！请打开 F12 查看',
                  type: 'warning'
                });
                let str = arr.join(',');
                console.log('数据第 ' + str + " 条添加失败");
              }else{
                this.$message({
                  message: '数据导入成功!',
                  type: 'success'
                });
                
                // 日志写入
                // let obj = {
                //   content: "导入课程信息",
                //   type: "添加记录",
                //   model: "课程模块",
                //   ip: sessionStorage.getItem('ip'),
                //   user: this.$store.state.userId,
                //   area: sessionStorage.getItem("area"),
                //   brower: sysTool.GetCurrentBrowser(),
                //   addTime: new Date().getTime(),
                // }
                // let arr = Object.values(obj);
                // this.$store.commit("writeLog",arr);
  
              }

            }
            else{
              this.$message({
                message: '导入文件失败！请稍后再试或联系网络管理员！',
                type: 'warning'
              });
              return;
            }
            // this.$router.go(-1);
          },
          error:(err)=>{
            console.log(err);
            this.$notify.error({
              title: '错误',
              message: '服务器错误，请稍后再试!'
            });
          }
        })
      }
    },
    down(){
      location.href = this.$store.state.endUrl + "/Finance/工资发放导入模板.xlsx"
    },
    handleRemove(){
      this.excelData = [];
    },
    SelWage(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Finance/revenue.php",
        data:{
          type: "sel_limit_wage",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj:{
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
          // console.log(res);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err); 
        }
      })
    },
    SelWageNum(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Finance/revenue.php",
        data:{
          col: "COUNT(*)",
          type: "sel_limit_wage",
          start: (this.page-1)*this.total,
          num: this.total,
          selobj:{
            campus: this.$store.state.userCampus,
            department: this.$store.state.userDepartment,
          },
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res)[0];
          this.sum = parseInt(res['COUNT(*)']);
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
        }
      })
    },
    onSubmit(){
      this.SelWage();
      this.SelWageNum();
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
}
</script>

<style>
  .upload-demo{
    margin-bottom: 12px;
  }
  .wage_warning{
    margin-bottom: 12px;
  }
</style>