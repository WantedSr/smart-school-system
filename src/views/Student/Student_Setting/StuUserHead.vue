<template>
  <div class="sethead">
    <el-upload
      class="upload-demo"
      drag
      action="上传地址" 
      multiple>
      <i class="el-icon-upload"></i>
      <div class="el-upload__text">将文件拖到此处，或<em>点击上传</em></div>
      <div class="el-upload__tip" slot="tip">只能上传jpg/png文件，且不超过500kb</div>
    </el-upload>
  </div>
</template>

<script>
export default {

}
</script>

<style>
  .sethead{
    text-align: center;
  }
</style>