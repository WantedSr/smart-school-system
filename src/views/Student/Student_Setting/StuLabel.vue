<template>
  <div class="myLabel">
    <el-form :inline="false" :model="mylabel" class="demo-form-inline">
      <el-form-item label="标签一">
        <el-select v-model="mylabel.label1" placeholder="选择你的个性标签">
          <el-option label="成熟" value="成熟"></el-option>
          <el-option label="冷静" value="冷静"></el-option>
          <el-option label="美丽" value="美丽"></el-option>
          <el-option label="聪慧" value="聪慧"></el-option>
          <el-option label="健壮" value="健壮"></el-option>
          <el-option label="帅气" value="帅气"></el-option>
          <el-option label="热心" value="热心"></el-option>
          <el-option label="大方" value="大方"></el-option>
          <el-option label="乐观" value="乐观"></el-option>
          <el-option label="自信" value="自信"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="标签二">
        <el-select v-model="mylabel.label2" placeholder="选择你的个性标签">
          <el-option label="成熟" value="成熟"></el-option>
          <el-option label="冷静" value="冷静"></el-option>
          <el-option label="美丽" value="美丽"></el-option>
          <el-option label="聪慧" value="聪慧"></el-option>
          <el-option label="健壮" value="健壮"></el-option>
          <el-option label="帅气" value="帅气"></el-option>
          <el-option label="热心" value="热心"></el-option>
          <el-option label="大方" value="大方"></el-option>
          <el-option label="乐观" value="乐观"></el-option>
          <el-option label="自信" value="自信"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="标签三">
        <el-select v-model="mylabel.label3" placeholder="选择你的个性标签">
          <el-option label="成熟" value="成熟"></el-option>
          <el-option label="冷静" value="冷静"></el-option>
          <el-option label="美丽" value="美丽"></el-option>
          <el-option label="聪慧" value="聪慧"></el-option>
          <el-option label="健壮" value="健壮"></el-option>
          <el-option label="帅气" value="帅气"></el-option>
          <el-option label="热心" value="热心"></el-option>
          <el-option label="大方" value="大方"></el-option>
          <el-option label="乐观" value="乐观"></el-option>
          <el-option label="自信" value="自信"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit">确定  </el-button>
      </el-form-item>
    </el-form>  
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data() {
    return {
      mylabel: {
        label1: '',
        label2: '',
        label3: ''
      }
    }
  },
  computed:{
    label(){
      return JSON.parse(this.labelData);
    }
  },
  methods: {
    onSubmit() {
      let arr = [];
      $.each(this.mylabel, function (i, v) { 
        arr.push(v);
      });
      let str = arr.join(',');
      requestAjax({
        type: "post",
        url: "/stuSetting.php",
        data:{
          type: "setLabel",
          token: localStorage.getItem('Authorization'),
          label: str,
        },
        success:(res)=>{
          if(res != "error"){
            if(res){
              this.$message({
                message: '恭喜你，修改成功！',
                type: 'success'
              });
            }else{
              this.$message.error('修改失败！');
            }
          }
        },
        error:(err)=>{
          console.log(err);
          this.$message.error('服务器有误，请稍后重试或联系管理员！');
        }
      })
    }
  }
}
</script>

<style>

</style>