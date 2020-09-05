<template>
  <div class="limit">
    <el-row>
      <el-col :span="6">
        <p style="color: #999;font-size: 14px">总共{{sum}}条记录</p>
      </el-col>
      <el-col :span="18" style="text-align: right">
        <el-pagination
          background
          layout="prev, pager, next"
          :pager-count="5"
          :page-size="total"
          @current-change="toPage"
          :small="true"
          :hide-on-single-page="false"
          :total="sum">
        </el-pagination>
      </el-col>
    </el-row>
  </div>
</template>

<script>
export default {
  data(){
    return{
      pagesize: 15, // 单页数据显示数量
      pagercount: 5, // 页码显示数量
      page: 1,    // 当前页数
    }
  },
  props:{
    sum:{   // 数据总数
      type: Number,
      require: true,
    },
    total:{
      type: Number,
      require: true,
    }
  },
  methods:{
    toPage(v){
      this.page = v;
      this.$emit('setPage',this.page);
    },
  }
}
</script>

<style>

</style>