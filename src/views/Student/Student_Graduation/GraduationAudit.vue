<template>
  <div class="graduationAudit box por">
    <el-page-header @back="goBack" content="我的通知"></el-page-header>
    <div class="info">
      <p>姓名：<span>{{username}}</span></p>
      <p>本学期职业素养分：<span>150 分</span></p>
      <p>累计学分：<span>80 分</span></p>
    </div>
    <div class="condition">
      <el-table
        :data="tableData"
        style="width: 100%">
        <el-table-column
          prop="date"
          label="姓名"
          width="70">
          <template>
            {{username}}
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          width="150"
          label="审核项目">
        </el-table-column>
        <el-table-column
          prop="address"
          min-width="200"
          label="条件">
        </el-table-column>
        <el-table-column
          prop="will"
          label="目标值">
        </el-table-column>
        <el-table-column
          prop="now"
          label="已获得">
          <template slot-scope="scope">
            <el-tag
            :type="scope.row.will > scope.row.now ? 'danger' : 'success'"
            disable-transitions>{{scope.row.now}}</el-tag>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="type poa">
      <p>是否允许毕业：<span>否</span></p>
      <p>是否审核：<span>否</span></p>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
      username: "李某某",
      tableData: [{
        date: '2016-05-02',
        name: '总学分',
        address: '总学分不得低于180.',
        will: 180,
        now: 80,
      }, {
        date: '2016-05-04',
        name: '专业实践任务学分',
        address: '通过专业实践任务课程获得的学分不得少于7分.',
        will: 7,
        now: 5,
      }, {
        date: '2016-05-01',
        name: '毕业设计学分',
        address: '通过毕业设计课程获得的学分不得少于1分.',
        will: 1,
        now: 1,
      }, {
        date: '2016-05-03',
        name: '通用类证书',
        address: '至少获得 1 个通用类证书.',
        will: 1,
        now: 1,
      }, {
        date: '2016-05-03',
        name: '技能类证书',
        address: '至少获得 1 个技能类证书.',
        will: 1,
        now: 0,
      }]
    }
  },
  methods:{
    goBack(){
      this.$router.go(-1);
    }
  }
}
</script>

<style>
  .graduationAudit{
    min-height: 755px;
    padding-bottom: 120px;
  }
  .graduationAudit>.el-page-header{
    margin-bottom: 20px;
  }
  .graduationAudit > .info{
    padding: 0 12px;
    margin-bottom: 20px;
  }
  .graduationAudit>.info>p{
    margin-bottom: 6px;
    color: #666;
    font-size: 15px;
  }
  .graduationAudit>.info>p>span{
    margin-left: 12px;
    color: rgba(231, 76, 60,1.0);
  }
  .graduationAudit>.condition{
    padding: 0 12px;
    margin-bottom: 20px;
  }
  .graduationAudit>.type{
    padding: 0 12px;
    margin-bottom: 20px;
    bottom: 20px;
    left: 20px;
  }
  .graduationAudit>.type>p{
    margin-bottom: 6px;
    color: #666;
    font-size: 15px;
  }
  .graduationAudit>.type>p>span{
    margin-left: 12px;
    color: rgba(231, 76, 60,1.0);
  }
</style>