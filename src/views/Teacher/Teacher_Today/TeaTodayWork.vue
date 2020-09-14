<template>
  <div class="todayWork box" v-loading="loading">
    <el-page-header @back="goBack" content="我的事务"></el-page-header>
    <div class="head">
      <h4 class="por">当日事务 <small class="poa">ToDoDay</small></h4>
    </div>
    <div>
      <el-table
        :data="todoData"
        style="width: 100%"
        size="mini"
        >
        <el-table-column
          prop="title"
          label="事务名称"
          min-width="150"
          sortable>
        </el-table-column>
        <el-table-column
          prop="date"
          label="时间"
          min-width="100"
          sortable>
          <template v-slot="scope">
            {{ getTime(scope.row.start_time) }}
          </template>
        </el-table-column>
        <el-table-column
          prop="state"
          sortable
          min-width="100"
          label="状态">
          <template v-slot="scope">
            <el-tag :type=" getState(scope.row.start_time,scope.row.end_time) == 0 ? 'primary' : getState(scope.row.start_time,scope.row.end_time) == 1 ? 'success' : 'danger' " size="small">{{  getState(scope.row.start_time,scope.row.end_time) == 0 ? '待完成' : getState(scope.row.start_time,scope.row.end_time) == 1 ? '完成中' : '已超时'  }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="dp"
          min-width="90"
          label="查看">
          <template v-slot="scope">
            <el-link :underline="false" @click="seeToDo(scope)">查看</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog
      title="事务详情"
      :visible.sync="dialogFormVisible"
      width="50%">
      <el-form ref="form" :model="form" label-width="80px">
        <el-form-item label="事务名称">
          {{ form.title }}
        </el-form-item>
        <el-form-item label="事务时间">
          {{ getTime(form.start_time) }}
        </el-form-item>
        <el-form-item label="结束时间">
          {{ form.start_time }}
        </el-form-item>
        <el-form-item label="结束时间">
          {{ form.end_time }}
        </el-form-item>
        <el-form-item label="当前状态">
          <el-tag :type=" getState(form.start_time,form.end_time) == 0 ? 'primary' : getState(form.start_time,form.end_time) == 1 ? 'success' : 'danger' " size="small">{{  getState(form.start_time,form.end_time) == 0 ? '待完成' : getState(form.start_time,form.end_time) == 1 ? '完成中' : '已超时'  }}</el-tag>
        </el-form-item>
        <el-form-item label="事务详情">
          {{ form.content }}
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data(){
    return{
      dialogFormVisible: false,

      form:{},
    }
  },
  props:{
    todoData:{
      type: Array,
      required: true,
      default: [],
    },
    loading:{
      type: Boolean,
      required: false,
      default: false,
    }
  },
  computed:{
    getState(){
      return (s,e)=>{
        if(!s || !e) return null;
        let date = new Date();
        let start = s.split(':');
        let end = e.split(':');
        let starthour = parseInt(start[0]),startmin = parseInt(start[1]);
        let endhour = parseInt(end[0]),endmin = parseInt(end[1]);
        let nowhour = date.getHours(),nowmin = date.getMinutes();
        if(starthour > nowhour){
          return 0
        }
        else if(starthour == nowhour){
          if(startmin > nowmin){
            return 0;
          }else{
            if(endhour == nowhour && endmin > nowmin){
              return 1;
            }
            return 2;
          }
        }
        else if(endhour > nowhour){
          return 1;
        }
        else{
          if(endhour == nowhour){
            if(endmin > nowmin){
              return 1;
            }
            return 2
          }
          else{
            return 2;
          }
        }
      }
    },
    getTime(){
      return (s)=>{
        if(s == undefined) return null;
        let hour = parseInt(s.split(":")[0]);
        if(hour >= 4 && hour < 12){
          return "早上";
        }
        else if(hour >= 12 && hour < 20){
          return "下午";
        }
        else if(hour >= 20 || hour < 4){
          return "晚上"
        }
      }
    },
  },
  methods:{
    goBack(){
      this.$router.go(-1);
    },
    seeToDo(scope){
      this.form = this.todoData[scope.$index];
      this.dialogFormVisible = true;
    },
    onSubmit(){
      this.dialogFormVisible = false;
    },
  }
}
</script>

<style>
  .todayWork{
    height: 755px;
  }
  .todayWork>.el-page-header{
    margin-bottom: 24px;
  }
  .todayWork>.head{
    padding-bottom: 12px;
    border-bottom: 1px solid #f0f0f0;
  }
</style>