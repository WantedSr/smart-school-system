<template>
  <div class="impAssets" v-loading="loading">
    <div class="pagehead">
      <h1>导入领用借用资产信息</h1>
    </div>
    <el-form ref="form" :model="form_imp" label-width="100px">
      <el-form-item label="导入文件">
        <el-upload
          class="upload-demo"
          ref="upload"
          action="https://jsonplaceholder.typicode.com/posts/"
          :on-preview="handlePreview"
          :on-remove="handleRemove"
          :file-list="fileList"
          :auto-upload="false">
          <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
          <el-button style="margin-left: 10px;" size="small" type="success" @click="submitUpload">上传</el-button>
          <el-button style="margin-left: 10px;" size="small" type="warning">下载模板</el-button>
          <el-button size="small" @click="onBack">取消</el-button>
          <!-- <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div> -->
        </el-upload>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data(){
    return{
      form_imp:{},
      loading: false,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    }
  }
}
</script>

<style>

</style>