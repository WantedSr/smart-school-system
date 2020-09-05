<template>
  <div class="todayWork box">
    <el-page-header @back="goBack" content="我的事务"></el-page-header>
    <div class="head">
      <h4 class="por">04月22日 事务 <small class="poa">ToDoDay</small></h4>
    </div>
    <div>
      <el-table
        :data="tableData"
        style="width: 100%"
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
          sortable>
        </el-table-column>
        <el-table-column
          prop="session"
          label="节次">
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态">
          <template v-slot="scope">
            <el-tag :type="gettype(scope.row.state)" size="small">{{scope.row.state}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="dp"
          label="查看">
          <template>
            <el-link :underline="false" @click="seeToDo">查看</el-link>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <el-dialog
      title="事务详情"
      :visible.sync="dialogFormVisible"
      width="30%"
      :before-close="handleClose">
      <el-form ref="form" :model="form" label-width="80px">
        <el-form-item label="事务名称">
          <el-input disabled v-model="form.title"></el-input>
        </el-form-item>
        <el-form-item label="事务时间">
          <el-select disabled v-model="form.date" placeholder="请选择事务使劲啊">
            <el-option label="上午" value="上午"></el-option>
            <el-option label="中午" value="中午"></el-option>
            <el-option label="下午" value="下午"></el-option>
            <el-option label="晚修" value="晚修"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="事务节次">
          <el-select disabled v-model="form.session" placeholder="请选择事务使劲啊">
            <el-option label="第一节" value="第一节"></el-option>
            <el-option label="第二节" value="第二节"></el-option>
            <el-option label="第三节" value="第三节"></el-option>
            <el-option label="第四节" value="第四节"></el-option>
            <el-option label="第五节" value="第五节"></el-option>
            <el-option label="第六节" value="第六节"></el-option>
            <el-option label="第七节" value="第七节"></el-option>
            <el-option label="晚修第一节" value="晚修第二节"></el-option>
            <el-option label="晚修第二节" value="晚修第一节"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="结束时间">
          <el-time-picker
            disabled
            is-range
            v-model="form.end"
            range-separator="-"
            start-placeholder="开始时间"
            end-placeholder="结束时间"
            placeholder="选择时间范围">
          </el-time-picker>
        </el-form-item>
        <el-form-item label="当前状态">
          <el-radio-group disabled v-model="form.state">
            <el-radio :label="1">已完成</el-radio>
            <el-radio :label="2">待完成</el-radio>
            <el-radio :label="3">已超时</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="事务详情">
          <el-input disabled type="textarea" v-model="form.message"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="onSubmit">立即创建</el-button>
          <el-button @click="onSubmit">取消</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data(){
    return{
      tableData: [
        {
          title: "早上三四节数学考试",
          date: "上午",
          session: "第三节",
          state: "已完成",
        },
        {
          title: "中午午休上传成绩",
          date: "中午",
          session: "午休",
          state: "待完成",
        },
        {
          title: "下午第七节学生会开会",
          date: "下午",
          session: "第七节",
          state: "已超时",
        },
      ],
      dialogFormVisible: false,
      form:{
        title: "早上三四节数学考试",
        date: "上午",
        session: "第三节",
        state: "1",
        end: [new Date(2016, 9, 10, 8, 40), new Date(2016, 9, 10, 9, 40)],
        message: "请各位同学与4月22号上午第三节准时到教师进行数学考试！",
      },
    }
  },
  computed:{
    gettype(){
      return function(type){
        if(type == '已完成'){
          return "success";
        }else if(type == "待完成"){
          return "primary";
        }else if(type == '已超时'){
          return 'danger';
        }
      }
    }
  },
  methods:{
    goBack(){
      this.$router.go(-1);
    },
    seeToDo(){
      this.dialogFormVisible = true;
    },
    onSubmit(){
      this.dialogFormVisible = false;
    },
    handleClose(done) {
      this.$confirm('确认关闭？')
      .then(_ => {
        done();
      })
      .catch(_ => {});
    }
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