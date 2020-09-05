<template>
  <div class="classtable">
    <div class="classHead">
      <h1>我的课表</h1>
    </div>
    <div class="table box">
      <el-table 
        :data="tableData" 
        :span-method="objectSpanMethod" 
        border  
        style="100%">
        <el-table-column fixed label="" width="50" prop="time"></el-table-column>
        <el-table-column label="" width="100" prop="class"></el-table-column>
        <el-table-column label="周一" prop="monday"></el-table-column>
        <el-table-column label="周二" prop="tuesday"></el-table-column>
        <el-table-column label="周三" prop="wednesday"></el-table-column>
        <el-table-column label="周四" prop="thursday"></el-table-column>
        <el-table-column label="周五" prop="firday"></el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      tableData:[
        {
          time: "上午",
          class: "第一节课",
          monday: "升旗",
          thursday: "图形图像处理",
        },
        {
          class: "第二节课",
          monday: "班会",
          thursday: "图形图像处理",
        },
        {
          class: "第三节课",
          tuesday: "图形图像处理",
          firday: "计算机基础"
        },
        {
          class: "第四节课",
          tuesday: "图形图像处理",
          firday: "计算机基础"
        },
        {
          time: "下午",
          class: "第五节课",
          monday: "图形图像处理",
          wednesday: "图形图像处理",
          firday: "英语"
        },
        {
          time: "下午",
          class: "第六节课",
          monday: "图形图像处理",
          wednesday: "图形图像处理",
          firday: "英语"
        },
        {
          time: "晚修",
          class: "第七节课",
          wednesday: "自习",
        },
        {
          class: "第八节课",
          wednesday: "自习",
        },
      ],
    }
  },
  methods:{
    objectSpanMethod({ row, column, rowIndex, columnIndex }) {
      if (columnIndex === 0) {
        if (rowIndex === 0) {
          return {
            rowspan: 4,
            colspan: 1
          };
        }
        else if(rowIndex === 4 || rowIndex === 6){
          return {
            rowspan: 2,
            colspan: 1
          };
        }
        else {
          return {
            rowspan: 0,
            colspan: 0
          };
        }
      }
    },
  }
}
</script>

<style>
  .classHead{
    margin-bottom: 20px;
  }
  .classHead > h1{
    font-size: 24px;
    font-weight: 500;
    color: #333;
  }
</style>