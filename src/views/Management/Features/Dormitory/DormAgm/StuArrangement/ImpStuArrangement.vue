<template>
  <div class="impCourse">
    <div class="pagehead">
      <h1>导入学期学生宿舍分配信息</h1>
    </div>
    <el-form ref="form" :model="form_imp" label-width="100px">
      <el-form-item label="导入文件">
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
          <el-button size="small" @click="onBack">取消</el-button>
          <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
        </el-upload>
      </el-form-item>
    </el-form>
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
        semester: {
          text: "所在学期",
          type: "String",
        },
        department: {
          text: "所在部门",
          type: "String",
        },
        class: {
          text: "所在班级",
          type: "String",
        },
        student: {
          text: "学生",
          type: "String",
        },
        build: {
          text: "所在宿舍楼",
          type: "String",
        },
        dormroom: {
          text: "所在宿舍",
          type: "Number",
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
        obj['campus'] = this.$store.state.userCampus;
        obj['school'] = this.$store.state.userSchool;
        obj['created_user'] = this.$store.state.userId;
        obj['addTime'] = new Date().getTime();
        return obj;
      });

      this.excelData = arr;
      // console.log(this.excelData);
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
          url: "/Dormitory/stuArrangement.php",
          data:{
            type: 'imp_dormroom',
            arr: this.excelData,
          },
          success:(res)=>{
            res = JSON.parse(res);
            // console.log(res);
            let arr = [];
            $.each(res, (i, v)=>{ 
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
              let obj = {
                content: "导入学期学生宿舍安排信息 操作人：" + this.$store.state.userId,
                  type: "添加记录",
                model: "学生宿舍安排模块",
                ip: sessionStorage.getItem('ip'),
                user: this.$store.state.userId,
                area: sessionStorage.getItem("area"),
                brower: sysTool.GetCurrentBrowser(),
                addTime: new Date().getTime(),
              }
              let arr = Object.values(obj);
              this.$store.commit("writeLog",arr);

            }

            this.$router.go(-1); 
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
      location.href = this.$store.state.endUrl + "/Dormitory/学生宿舍安排导入模板.xlsx"
    },
    handleRemove(){
      this.excelData = [];
    }
  }
}
</script>

<style>

</style>