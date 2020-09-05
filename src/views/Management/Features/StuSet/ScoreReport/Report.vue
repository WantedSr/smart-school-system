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
      :auto-upload="false">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <el-button slot="trigger" size="small">下载模板</el-button>
      <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传</el-button>
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
        title="新窗口中需要选择及填写该数据的年月（如2017年8月）、数据类型（如：类型）"
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
        title="如果导入数据有误，可通过下方表格进行撤回"
        :closable="false"
        type="warning">
      </el-alert>
    </div>
    <div class="wageTable">
      <el-table
        border 
        :data="tableData"
        size="mini"
        stripe
        style="width: 100%">
        <el-table-column width="30" type="index"></el-table-column>
        <el-table-column
          prop="file_id"
          width="90"
          sortable
          label="编号">
        </el-table-column>
        <el-table-column
          prop="imp_date"
          sortable
          label="导入日期">
        </el-table-column>
        <el-table-column
          prop="summary"
          sortable
          label="摘要">
        </el-table-column>
        <el-table-column
          prop="type"
          label="类型">
        </el-table-column>
        <el-table-column
          prop="person_num"
          label="人数">
          <template slot-scope="scope">
            {{scope.row.success_num}}/{{scope.row.person_num}}
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作">
          <template>
            <el-button size="small" type="danger">撤回</el-button>
            <el-button size="small" type="success">推送</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      tableData:[
        {
          file_id: "000001",
          imp_date: "2020-04-15",
          summary: "这是一篇示例文件",
          person_num: 250,
          type: "期末",
          success_num: 250,
        }
      ]
    }
  },
  methods:{
    submitUpload() {
      this.$refs.upload.submit();
    },
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