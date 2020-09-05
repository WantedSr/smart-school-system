<template>
  <div class="subpage" v-loading='loading'>
    <div class="pagehead">
      <h1>作息时间</h1>
    </div>
    <div class="workrest">
      <el-table
        :data="tableData"
        :default-sort="{prop:'schedule_order',order:'descending'}"
        style="width: 100%">
        <el-table-column
          prop="schedule_order"
          :sort-method="toAsc"
          :sortable="true"
          width="80"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="schedule_name"
          sortable
          label="节次">
        </el-table-column>
        <el-table-column
          label="时间">
          <template v-slot="scope">
            {{scope.row.schedule_start}}~{{scope.row.schedule_end}}
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return {
      tableData:[],
      loading: false,
    }
  },
  created(){
    this.getData();
  },
  methods:{
    getData(){
      this.loading = true;
      requestAjax({
        url: "/base/schedule.php",
        type: 'get',
        data:{
          type: "sel_schedule",
          selobj: {
            'campus':this.$store.state.userCampus,
            'status': '1',
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.tableData = res;
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    toAsc(a,b){
      let val1 = a.deadline
      let val2 = b.deadline
      return val2 - val1
    },
  }
}
</script>

<style>

</style>