<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>附件列表</h1>
    </div>
    <div>
      <el-table
        :data="tableData"
        border
        size="small"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="annex_name"
          label="附件名称"
          sortable>
        </el-table-column>
        <el-table-column
          prop="related"
          label="关联OA"
          sortable>
        </el-table-column>
        <el-table-column
          prop="path"
          label="附件路径"
          sortable>
        </el-table-column>
        <el-table-column
          prop="size"
          label="文件大小"
          width="130"
          sortable>
        </el-table-column>
        <el-table-column
          prop="date"
          label="上传时间"
          width="130"
          sortable>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return{
      n: this.$route.query.n,
      tableData:[
      ]
    }
  }
}
</script>

<style>

</style>