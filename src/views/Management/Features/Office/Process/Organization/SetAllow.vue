<template>
  <div class="upaTeacherInfo" v-loading="loading">

    <div class="pagehead">
      <h1>OA发起权限设置</h1>
    </div>

    <div>
      <el-form ref="form" label-position="left" :rules="rules" :model="form" label-width="110px">
        <el-form-item label="部门选择">
          <el-select @change="getData" size="small" v-model="department" placeholder="选择部门">
            <el-option v-for="(item,i) in depData" :key="'1-'+i" :label="item.department_name" :value="item.department_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item v-if="department != ''" label="人员安排">
          <div style="text-align: left">
            <el-transfer
              style="text-align: left; display: inline-block;"
              v-model="setTea"
              filterable
              :titles="['不允许', '允许']"
              :button-texts="['到左边', '到右边']"
              :format="{
                noChecked: '${total}',
                hasChecked: '${checked}/${total}'
              }"
              @change="handleChange"
              :data="teaData">
            </el-transfer>
          </div>
        </el-form-item>
        <el-form-item>
          <el-button size="small " @click="onBack">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax"; 
export default {
  data(){
    
    // const generateData = _ => {
    //   const data = [];
    //   for (let i = 1; i <= 15; i++) {
    //     data.push({
    //       key: i,
    //       label: `备选项 ${ i }`,
    //     });
    //   }
    //   return data;
    // };

    return{

      // teaData: generateData(),
      teaData: [],
      
      setTea: [],
      renderFunc(h, option) {
        return <span>{ option.key } - { option.label }</span>;
      },

      department: "",

      form:{},
      loading: false,

    }
  },
  props:{
    depData:{
      type: Array,
      require: true,
    }
  },  
  created(){
    // this.getData();
    // console.log(this.teaData);
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getData(v){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Process/Allow.php",
        data:{
          type: "sel_allow",
          col: "teacher",
          campus: this.$store.state.userCampus,
          department: this.department,
        },
        async: true,
        success:(res)=>{
          this.loading = false;
          res = JSON.parse(res);
          // console.log(res);
          this.teaData = res[0];
          this.setTea = res[1];
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
    onUpa(){
      // console.log(this.setTea);
    },
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleRemove(file, fileList) {
      // console.log(file, fileList);
    },
    handlePreview(file) {
      // console.log(file);
    },
    
    handleChange(value, direction, movedKeys) {
      // console.log(value);       // 当前值
      // console.log(direction);   // 移动方向
      // console.log(movedKeys);   // 发生移动的值
      requestAjax({
        type: "post",
        url: "/Office/Process/Allow.php",
        data:{
          type: "upa_allow",
          department: this.department,
          campus: this.$store.state.userCampus,
          school: this.$store.state.userSchool,
          set: direction == 'left' ? 'unset' : 'set',
          arr: movedKeys,
          userId: this.$store.state.userId,
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          let errArr = [];
          $.each(res, (i, v)=>{ 
            if(!v){
              errArr.push(arr[i]);
            }
          });
          if(errArr.length == 0){
            this.$message({
              message: '设置成功',
              type: 'success'
            });
          }else{
            let str = errArr.join("-");
            this.$message({
              message: '部门教师设置有误，打开 F12 -> console 查看',
              type: 'warning'
            });
            console.log("教师 " + str + "设置权限有误！");
          }

          
          // 日志写入
          let obj = {
            content: "修改教师oa权限 教师: "+ JSON.stringify(movedKeys),
            type: "修改记录",
            model: "oa权限设置模块",
            ip: sessionStorage.getItem('ip'),
            user: this.$store.state.userId,
            area: sessionStorage.getItem("area"),
            brower: sysTool.GetCurrentBrowser(),
            addTime: new Date().getTime(),
          }
          let arr = Object.values(obj);
          this.$store.commit("writeLog",arr);

        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
    }
  }
}
</script>

<style scoped>
  .transfer-footer {
    margin-left: 20px;
    padding: 6px 5px;
  }
</style>