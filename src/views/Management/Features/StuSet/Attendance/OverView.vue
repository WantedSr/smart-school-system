<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>总览</h1>
    </div>

    <el-row :gutter="20">
      <el-col :lg="8">
        <div class="pagehead2">
          <h2>今日课堂</h2>
        </div>
        <el-form label-width="80px">
          <el-form-item class="now-list" v-for="(item,i) in ClassOptionData" :key="'class-'+i" :label="item.option_name">
            <el-tag size="small">{{ ClassData[item.option_id] != undefined && ClassData[item.option_id].length > 0 ? ClassData[item.option_id][0]['count(attendance)'] : 0 }}</el-tag>
          </el-form-item>
        </el-form>
      </el-col>
      <el-col :lg="8">
        <div class="pagehead2">
          <h2>今日早段</h2>
        </div>
        <el-form ref="form" :model="form" label-width="80px">
          <el-form-item class="now-list" v-for="(item,i) in EarlyOptionData" :key="'class-'+i" :label="item.option_name">
            <el-tag size="small">{{ EarlyData[item.option_id] != undefined && EarlyData[item.option_id].length > 0 ? EarlyData[item.option_id][0]['count(attendance)'] : 0 }}</el-tag>
          </el-form-item>
        </el-form>
      </el-col>
      <el-col :lg="8">
        <div class="pagehead2">
          <h2>今日课间操</h2>
        </div>
        <el-form ref="form" :model="form" label-width="80px">
          <el-form-item class="now-list" v-for="(item,i) in BetweenOptionData" :key="'class-'+i" :label="item.option_name">
            <el-tag size="small">{{ BetweenData[item.option_id] != undefined && BetweenData[item.option_id].length > 0 ? BetweenData[item.option_id][0]['count(attendance)'] : 0 }}</el-tag>
          </el-form-item>
        </el-form>
      </el-col>
    </el-row>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      form: {},

      time: new Date().setHours(0,0,0,0),

      ClassOptionData: [],
      EarlyOptionData: [],
      BetweenOptionData: [],
      ClassData: [],
      EarlyData: [],
      BetweenData: [],
    }
  },
  methods:{
    getOption(model){
      let arr = [];
      this.loading = true;
      requestAjax({
        url: "/StuSet/OverView.php",
        type: 'get',
        data:{
          type: "sel_option",
          col: "id,option_id,option_name,model,type",
          campus: this.$store.state.userCampus,
          model: model,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          res.sort((a,b)=> a.id - b.id);
          arr = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
      return arr;
    },
    getNum(model){
      let arr = [];
      this.loading = true;
      requestAjax({
        url: "/StuSet/OverView.php",
        type: 'get',
        data:{
          type: "get_num",
          campus: this.$store.state.userCampus,
          department: this.$store.state.userDepartment,
          semester: this.$store.state.semester,
          date: this.time,
          model: model,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          arr = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
      return arr;
    }
  },
  created(){
    this.ClassOptionData = this.getOption('课堂登记');
    this.BetweenOptionData = this.getOption('课间操登记');
    this.EarlyOptionData = this.getOption('早段登记');
    this.ClassData = this.getNum('course');
    this.BetweenData = this.getNum('between');
    this.EarlyData = this.getNum('early');
  }
}
</script>

<style>
  .info{
    text-align: right;
    padding-right: 36px;
  }
  .info ul li{
    margin-bottom: 12px;
  }
  .now-list{
    margin-bottom: 0;
  }
</style>