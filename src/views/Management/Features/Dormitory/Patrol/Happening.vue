<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>宿舍概况</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="getDormitory" size="small" v-model="sel.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-date-picker
          size="small"
          @change="getAttendance"
          v-model="sel.time"
          type="date"
          format="yyyy-MM-dd"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
    </el-form>

    <div class="happening" v-if="onSel">
      <el-table
        :data="dormroomData"
        size="small"
        border
        stripe
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        ref="multipleTable"
        tooltip-effect="dark"
        style="width: 100%">
        <el-table-column
          type="index"
          width="50"
          align="center"
          label="序号">
        </el-table-column>
        <el-table-column
          prop="dormroom_name" 
          sortable
          min-width="80"
          align="center"
          label="宿舍">
        </el-table-column>
        <el-table-column
          prop="people_num" 
          sortable
          min-width="80"
          align="center"
          label="人数">
          <template v-slot="scope">
            {{ scope.row.people_num + " 人" }}
          </template>
        </el-table-column>
        <el-table-column
          sortable
          min-width="80"
          align="center"
          label="登记人">
          <template v-slot="scope">
            {{ attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0 ? attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['dormitory_name'] : "~" }}
          </template>
        </el-table-column>
        <el-table-column label="情况" align="center">
          <el-table-column
            prop="morning" 
            sortable
            align="center"
            min-width="100"
            label="早上">
            <template v-slot="scope">
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0">
                <div v-html="getfor(attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['morning']['attendance'])"></div>
              </div>
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0">
                <div v-html="getfor2(attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['morning']['discipline'])"></div>
              </div>
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length == 0 && attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length == 0">
                <span>-</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="afternoon" 
            sortable
            align="center"
            min-width="100"
            label="中午">
            <template v-slot="scope">
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0">
                <div v-html="getfor(attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['noon']['attendance'])"></div>
              </div>
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0">
                <div v-html="getfor2(attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['noon']['discipline'])"></div>
              </div>
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length == 0 && attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length == 0">
                <span>-</span>
              </div>
            </template>
          </el-table-column>
          <el-table-column
            prop="night" 
            sortable
            align="center"
            min-width="100"
            label="晚上">
            <template v-slot="scope">
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0">
                <div v-html="getfor(attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['afternoon']['attendance'])"></div>
              </div>
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length > 0">
                <div v-html="getfor2(attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id)[0]['afternoon']['discipline'])"></div>
              </div>
              <div v-if="attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length == 0 && attendanceData.filter(item=>item.dormroom == scope.row.dormroom_id).length == 0">
                <span>-</span>
              </div>
            </template>
          </el-table-column>
        </el-table-column>
      </el-table>
    </div>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        time: new Date().setHours(0,0,0,0),
        department: this.$store.state.userDepartment,
      },
      tableData:[],
      
      departmentData: [],
      dormroomData: [],

      attendanceData: [],
      loading: false,
    }
  },
  created(){
    this.getDepartment();
    this.getDormitory();
  },
  methods:{
    getDepartment(campus){
      this.loading = true;
      requestAjax({
        url: "/base/department.php",
        type: 'get',
        data:{
          type: "sel_department",
          sel: 'campus',
          val: campus ? campus : this.$store.state.userCampus,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.departmentData = res;
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
    getDormitory(){
      this.loading = true;
      requestAjax({
        url: "/Dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_more_dormroom",
          col: "*",
          buildtype: '学生宿舍',
          campus: this.$store.state.userCampus,
          department: this.sel.department,
          semester: this.$store.state.semester,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.dormroomData = res;
          // console.log(res);
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
      this.getAttendance();
    },
    getAttendance(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Dormitory/getOption.php",
        data:{
          type: "get_more_attendance",
          col: 'dormroom,dormitory,campus,semester,department',
          department: this.sel.department,
          semester: this.$store.state.semester,
          date: this.sel.time,
          campus: this.$store.state.userCampus,
        },
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.attendanceData = res;
          // console.log(res);
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
    }
  },
  computed:{
    onSel(){
      if(this.sel.time != '' && this.sel.department != ''){
        return true;
      }
      return false;
    },
    getfor(){
      return (arr)=>{
        let str = "";
        if(arr.length > 0){
          for(let i=0;i<arr.length;i++){
            let that = arr[i];
            str += that['student'] + '-' + that['attendance'];
            str += "<br>";
          }
          return str;
        }
        else{
          return "-";
        }
      }
    },
    getfor2(){
      return (arr)=>{
        let str = "";
        if(arr.length > 0){
          for(let i=0;i<arr.length;i++){
            let that = arr[i];
            let str2 = that['discipline'].join("-");
            str += that['student'] + '-' + str2;
            str += "<br>";
          }
          return str;
        }
        else{
          return "-";
        }
      }
    }
  },
}
</script>

<style>
  .happening table{
    text-align: center;
  }
</style>