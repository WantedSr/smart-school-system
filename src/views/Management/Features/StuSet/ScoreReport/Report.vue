<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>成绩录入</h1>
    </div>
    <el-upload
      class="upload-demo"
      ref="upload"
      action="https://jsonplaceholder.typicode.com/posts/"
      :on-preview="handlePreview"
      :on-remove="handleRemove"
      :file-list="fileList"
      :on-change="handle"
      :limit="1"
      :auto-upload="false">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传</el-button>
      <el-button style="margin-left: 10px;" size="small" @click="down" type="warning">下载模板</el-button>
      <el-button size="small" @click="onBack">取消</el-button>
      <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
      <div slot="tip" class="el-upload__tip">选择完文件后点击上传即可</div>
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
        title="导入的成绩所对应科目必须与系统内设置的课程一致"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="建议按照课程分批导入，减少处理时间。"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="导入的数据必须位于excel表格的sheet1表里，导入的文件不得超过5M"
        :closable="false"
        type="warning">
      </el-alert>
      <el-alert
        title="如果导入数据有误，可通过查询导入进行撤回"
        :closable="false"
        type="warning">
      </el-alert>
    </div>
    <div class="wageTable">
      <el-table
        border 
        :data="excelData"
        size="mini"
        stripe
        style="width: 100%">
        <el-table-column min-width="30" align="center" label="序号" type="index"></el-table-column>
        <el-table-column
          prop="semester"
          min-width="100"
          sortable
          label="学期">
        </el-table-column>
        <el-table-column
          prop="userid"
          min-width="100"
          sortable
          label="学生学号">
        </el-table-column>
        <el-table-column
          prop="username"
          min-width="100"
          sortable
          label="学生姓名">
        </el-table-column>
        <el-table-column
          prop="course"
          min-width="100"
          sortable
          label="科目">
        </el-table-column>
        <el-table-column
          prop="score"
          min-width="100"
          label="分数">
        </el-table-column>
        <el-table-column
          prop="rank"
          min-width="100"
          label="排名">
        </el-table-column>
      </el-table>
    </div>
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
      form:{
        title: "",
        semester: this.$store.state.semester,
        department: this.$store.state.userDepartment,
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },

      character:{
        userid: {
          text: "学生学号",
          type: "String",
        },
        username: {
          text: "学生姓名",
          type: "String",
        },
        class: {
          text: "班级",
          type: "String",
        },
        course: {
          text: "科目",
          type: "String",
        },
        score: {
          text: "分数",
          type: "String",
        },
        rank:{
          text: "班级排名",
          type: "String",
        },
        semester:{
          text: "所在学期",
          type: "String",
        },
      },

      excelData: [],
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    // 采集 EXCEL 的数据
    async handle(ev){
      // console.log(ev);
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
          type === 'string' ? (v = String(v)) : null;
          type === 'number' ? (v = Number(v)) : null;
          obj[key] = v;
        }
        obj['department'] = this.$store.state.userDepartment;
        obj['campus'] = this.$store.state.userCampus;
        obj['school'] = this.$store.state.userSchool;
        obj['created_user'] = this.$store.state.userId;
        obj['addTime'] = new Date().getTime();
        return obj;
      });

      this.excelData = arr;
      this.form.title = ev.name;
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
        this.form.addTime = new Date().getTime();
        let imparr = Object.values(this.form);
        requestAjax({
          type: 'post',
          url: "/StuSet/Score.php",
          data:{
            action: 'imp',
            arr: JSON.stringify(this.excelData),
            form: JSON.stringify(imparr),
          },
          success:(res)=>{
            res = JSON.parse(res);
            console.log(res);
            let data = res.data;
            let arr = [];
            $.each(data, (i, v)=>{ 
              if(!v){
                arr.push((i+1));
              }
            });
            if(arr.length == this.excelData.length){
              this.$message.error('导入失败');
            }
            else if(arr.length > 0){
              this.$message({
                message: '导入部分失败！请打开 F12 查看',
                type: 'warning'
              });
              let str = arr.join(',');
              console.log('数据第 ' + str + " 条添加失败");
              
              // 日志写入
              let obj = {
                content: "导入学生成绩信息",
                type: "添加记录",
                model: "成绩导入模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }else{
              this.$message({
                message: '数据导入成功!',
                type: 'success'
              });
              
              // 日志写入
              let obj = {
                content: "导入学生成绩信息",
                type: "添加记录",
                model: "成绩导入模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }
            location.reload();
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
      location.href = this.$store.state.endUrl + "/StuSet/学生成绩导入模板.xlsx"
    },
    handleRemove(){
      this.excelData = [];
    }
  }
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