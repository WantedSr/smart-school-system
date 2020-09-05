<template>
  <div class="subpage" v-loading="loading">
    <div class="pagehead">
      <h1>宿舍查询</h1>
    </div>

    <el-form :inline="true" :model="sel" class="demo-form-inline">
      <el-form-item label="">
        <el-select @change="setValue" size="small" v-model="sel.where" placeholder="查询范围">
          <el-option label="日" value="day"></el-option>
          <el-option label="月" value="month"></el-option>
          <el-option label="学期" value="semester"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item v-if="sel.where == 'semester'" label="">
        <el-select @change="how" size="small" v-model="sel.semester" placeholder="查询学期">
          <el-option v-for="(item,i) in semesterData" :key="'s-'+i" :label="item.semester" :value="item.semesterId"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'day'">
        <el-date-picker
          size="small"
          v-model="sel.time"
          type="date"
          format="yyyy-MM-dd"
          value-format="timestamp"
          placeholder="选择日期">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="" v-if="sel.where == 'month'">
        <el-date-picker
          size="small"
          v-model="sel.month"
          format="yyyy-MM"
          value-format="timestamp"
          type="month"
          placeholder="选择月份">
        </el-date-picker>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="how" size="small" v-model="sel.department" placeholder="选择部门">
          <el-option v-for="(item,i) in departmentData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="how" size="small" v-model="sel.type" placeholder="查询方式">
          <el-option label="按班级" value="class"></el-option>
          <el-option label="按宿舍" value="dormroom"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" v-if="sel.type == 'class'">
        <el-select @change="getStu" size="small" v-model="sel.class" placeholder="查询班级">
          <el-option label="所有班级" v-if="classData.length > 0" value="all"></el-option>
          <el-option v-for="(item,i) in classData" :key="'class-'+i" :label="item.class_name" :value="item.class"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="" v-else-if="sel.type == 'dormroom'">
        <el-select @change="getDormStu" size="small" v-model="sel.dormroom" placeholder="查询宿舍">
          <el-option label="所有宿舍" v-if="dormroomData.length > 0" value="all"></el-option>
          <el-option v-for="(item,i) in dormroomData" :key="'dormitory-'+i" :label="item.dormroom_name" :value="item.dormroom_id"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-select @change="clearTable" size="small" v-model="sel.student" placeholder="查询学生">          
          <el-option label="所有学生" v-if="stuData.length > 0" value="all"></el-option>
          <el-option v-for="(item,i) in stuData" :key="'student-'+i" :label="sel.type == 'class' ? item.username : item.student_name" :value="sel.type == 'class' ? item.userid : item.student"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="">
        <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
      </el-form-item>
    </el-form>

    <div class="seldormroom" v-if="choose">
      <el-table
        :data="
        sel.type == 'class' ? 
          sel.student != 'all' ? 
            tableData2
          : sel.class != 'all' ? 
            stuData
          :
            classData
        : sel.type == 'dormroom' ? 
          sel.student != 'all' ? 
            tableData2
          : sel.dormroom != 'all' ? 
            stuData
          : 
            dormroomData
        : ''"
        size="small"
        border
        stripe
        ref="multipleTable"
        tooltip-effect="dark"
        style="width: 100%">
        <el-table-column          
          v-if="sel.type == 'class' ? sel.class == 'all' && sel.student == 'all' : sel.dormroom == 'all' && sel.student == 'all'"
          :prop="sel.type == 'class' ? 'class_name' : 'dormroom_name'"
          label="班级/宿舍"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          v-if="sel.type == 'class' ? sel.class != 'all' ? true : sel.student != 'all' ? true : false : sel.dormroom != 'all' ? true : sel.student != 'all' ? true : false"
          :prop="sel.type == 'class' ? sel.student != 'all' ? 'student_name' : sel.class != 'all' ? 'username' : 'student_name' : 'student_name'"
          label="姓名"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          v-if="sel.type == 'class' ? sel.class == 'all' && sel.student == 'all' : sel.dormroom == 'all' && sel.student == 'all'"
          :prop="sel.type == 'class' ? 'class_num' :'people_num'"
          label="人数"
          min-width="100"
          sortable>
        </el-table-column>
        <el-table-column
          prop="score"
          label="扣分"
          sortable>
          <template v-slot="scope">
            {{
              sel.type == 'class' ? 
                sel.student != 'all' ? 
                  scope.row.score
                : sel.class != 'all' ? 
                  tableData.filter(item=>item.student == scope.row.student).length > 0 ? tableData.filter(item=>item.student == scope.row.student)[0]['score'] : 0
                :
                  getScore(tableData.filter(item=>item.class == scope.row.class))
              : sel.type == 'dormroom' ? 
                sel.student != 'all' ? 
                  scope.row.score
                : sel.dormroom != 'all' ? 
                  tableData.filter(item=>item.student == scope.row.student).length > 0 ? tableData.filter(item=>item.student == scope.row.student)[0]['score'] : 0
                : 
                  getScore(tableData.filter(item=>item.dormroom == scope.row.dormroom_id))
              : ''

              + "分"
            
            }}
          </template>
        </el-table-column>
        
        <el-table-column
          v-for="(item,i) in attendanceData"
          :key="'attendance-'+i"
          :label="item.option_name">
          <template v-slot="scope">
            {{
              sel.type == 'class' ? 
                sel.student != 'all' ? 
                  tableData.filter(item2=>item2.attendance == item.option_id).length
                : sel.class != 'all' ? 
                  tableData.filter(item2=>item2.student == scope.row.student && item2.attendance == item.option_id).length
                :
                  tableData.filter(item2=>item2.class == scope.row.class && item2.attendance == item.option_id).length
              : sel.type == 'dormroom' ? 
                sel.student != 'all' ? 
                  tableData.filter(item2=>item2.attendance == item.option_id).length
                : sel.dormroom != 'all' ? 
                  tableData.filter(item2=>item2.student == scope.row.student && item2.attendance == item.option_id).length
                : 
                  tableData.filter(item2=>item2.dormroom == scope.row.dormroom_id && item2.attendance == item.option_id).length
              : '-'
            }}
          </template>
        </el-table-column>

        <el-table-column
          v-for="(item,i) in disciplineData"
          :key="'discipline-'+i"
          :label="item.option_name">
          <template v-slot="scope">
            {{
              sel.type == 'class' ? 
                sel.student != 'all' ? 
                  tableData.filter(item2=>item2.discipline.includes(item.option_id)).length
                : sel.class != 'all' ? 
                  tableData.filter(item2=>item2.student == scope.row.student && item2.discipline.includes(item.option_id)).length
                :
                  tableData.filter(item2=>item2.class == scope.row.class && item2.discipline.includes(item.option_id)).length
              : sel.type == 'dormroom' ? 
                sel.student != 'all' ? 
                  tableData.filter(item2=>item2.discipline.includes(item.option_id)).length
                : sel.dormroom != 'all' ? 
                  tableData.filter(item2=>item2.student == scope.row.student && item2.discipline.includes(item.option_id)).length
                : 
                  tableData.filter(item2=>item2.dormroom == scope.row.dormroom_id && item2.discipline.includes(item.option_id)).length
              : ''
            }}
          </template>
        </el-table-column>
        <!-- <el-table-column
          min-width="100"
          label="日期"
          prop="addTime"
        >
          <template v-slot="scope">
            {{ getDate(scope.row.addTime) }}
          </template>
        </el-table-column> -->

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
        where: "day",
        time: new Date().setHours(0,0,0,0),
        month: "",
        semester: "",
        department: this.$store.state.userDepartment,
        type: "class",
        class: "",
        dormroom: "",
        student: "",
      },
      tableData:[],

      n: this.$route.query.n,
      multipleTable: [],

      tableData:[],
      tableData2: [],
      
      attendanceData: [],
      disciplineData: [],

      departmentData: [],
      dormroomData: [],
      classData: [],
      stuData: [],
      semesterData: [],

      loading: false,
    }
  },
  created(){

    
    this.loading = true;
    requestAjax({
      url: "/Dormitory/getOption.php",
      type: 'get',
      data:{
        type: "sel_option",
        col: "*",
        campus: this.$store.state.userCampus,
      },
      async: false,
      success:(res)=>{

        this.loading = false;
        res = JSON.parse(res);
        this.attendanceData = res[0];
        this.attendanceData.sort((a,b)=> a.id - b.id);
        this.disciplineData = res[1];
        this.disciplineData.sort((a,b)=> a.id - b.id);
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

    this.getSemester();
    this.getDepartment();
    this.how();
    
  },
  methods:{
    onSubmit(){
      let obj = {
        type: "sel_more_attendance",
        where: this.sel.where,
        sel_type: this.sel.type,
        semester: this.sel.where == "semester" ? this.sel.semester : this.$store.state.semester,
        campus: this.$store.state.userCampus,
        department: this.sel.department,
      }

      if(this.sel.where == 'day'){
        obj['time'] = this.sel.time;
      }else if(this.sel.where == 'month'){
        obj['month'] = this.sel.month;
      }

      if(this.sel.type == 'class'){
        if(this.sel.student != 'all'){
          obj['student'] = this.sel.student;
        }else if(this.sel.class != 'all'){
          obj['class'] = this.sel.class;
        }
      }else if(this.sel.type == 'dormroom'){
        if(this.sel.student != 'all'){
          obj['student'] = this.sel.student;
        }else if(this.sel.dormroom != 'all'){
          obj['dormroom'] = this.sel.dormroom;
        }
      }

      this.tableData = [];
      this.loading = true;
      requestAjax({
        url: "/Dormitory/getOption.php",
        type: "get",
        data:obj,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.tableData = res;
          this.tableData2 = [this.tableData[0]];
        },
        async: false,
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
    getSemester(){
      this.loading = true;
      requestAjax({
        url: "/base/semester.php",
        type: 'get',
        data:{
          type: "sel_semester",
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.semesterData = res;
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
    getDepartment(campus){
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
          res = JSON.parse(res);
          this.departmentData = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    },
    getClass(){
      this.sel.class = '';
      this.loading = true;
      requestAjax({
        url: "/teach/SemesterClass.php",
        type: 'get',
        data:{
          type: "sel_class_name",
          department: this.sel.department,
          selobj: {
            'semester': this.sel.where == 'semester' ? this.sel.semester : this.$store.state.semester,
            'department': this.sel.department,
            'status': "1",
          },
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.classData = res;
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
    },
    getDormitory(){
      this.sel.dormroom = "";
      this.sel.student = "";
      this.loading = true;
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_more_dormroom",
          col: "*",
          buildtype: '学生宿舍',
          campus: this.$store.state.userCampus,
          department: this.sel.department,
          semester: this.sel.where == 'semester' ? this.sel.semester : this.$store.state.semester,
        },
        async: true,
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
      })
    },
    how(){
      this.stuData = [];
      if(this.sel.type == 'class'){
        this.getClass();
      }
      else if(this.sel.type == 'dormroom'){
        this.getDormitory();
      }
    },
    getStu(){
      this.stuData = [];
      this.sel.student = '';
      this.loading = true;
      let obj = {
        campus: this.$store.state.userCampus,
        job: "S1",
        department: this.sel.department,
      }
      if(this.sel.class != 'all'){
        obj['class'] = this.sel.class;
      }
      requestAjax({
        type: "get",
        url: "/StuManagement/StuInfo/StuLibrary.php",
        data:{
          type: "sel_stu",
          col: "userid,username,class,school,campus",
          selobj: obj,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.stuData = res;
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
    },
    getDormStu(){
      this.stuData = [];
      this.loading = true;
      let obj = {
        campus: this.$store.state.userCampus,
        semester: this.sel.where == 'semester' ? this.sel.semester : this.$store.state.userSemester,
        department: this.sel.department,
      }

      if(this.sel.dormroom != 'all'){
        obj['dormroom'] = this.sel.dormroom;
      }
      requestAjax({
        type: "get",
        url: "/Dormitory/stuArrangement.php",
        data:{
          type: "sel_dormroom_name",
          col: "*",
          selobj: obj
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          this.stuData = res;
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
    clearTable(){
      this.tableData = [];
    }
  },
  computed:{
    choose(){
      if(this.sel.where == 'day'){
        if(this.sel.type == 'class'){
          if(this.sel.time != '' && this.sel.department != "" && this.sel.class != "" && this.sel.student != ''){
            return true;
          }
        }else if(this.sel.type == 'dormroom'){
          if(this.sel.time != '' && this.sel.department != "" && this.sel.dormroom != "" && this.sel.student != ''){
            return true;
          }
        }
      }
      else if(this.sel.where == 'month'){
        if(this.sel.type == 'class'){
          if(this.sel.month != '' && this.sel.department != "" && this.sel.class != "" && this.sel.student != ''){
            return true;
          }
        }else if(this.sel.type == 'dormroom'){
          if(this.sel.month != '' && this.sel.department != "" && this.sel.dormroom != "" && this.sel.student != ''){
            return true;
          }
        }
      }
      else if(this.sel.where == 'semester'){
        if(this.sel.type == 'class'){
          if(this.sel.semester != '' && this.sel.department != "" && this.sel.class != "" && this.sel.student != ''){
            return true;
          }
        }else if(this.sel.type == 'dormroom'){
          if(this.sel.semester != '' && this.sel.department != "" && this.sel.dormroom != "" && this.sel.student != ''){
            return true;
          }
        }
      }
      else{
        return false;
      }
    },
    getScore(){
      return (arr)=>{
        if(arr.length > 0){
          // console.log(arr);
          let sum = 0;
          $.each(arr, (i, v)=>{ 
            sum += parseInt(v.score);
          });
          return sum + " 分";
        }else{
          return '0 分';
        }
      }
    },
    getDate(){
      return (s)=>{
        let date = new Date();
        date.setTime(s);
        let y = date.getFullYear();
        let m = date.getMonth() + 1;
        m = String(m).length == 1 ? '0'+m : m;
        let d = date.getDate();
        d = String(d).length == 1 ? '0'+d : d;
        return y+"-"+m+"-"+d;
      }
    },
  },
}
</script>

<style>

</style>