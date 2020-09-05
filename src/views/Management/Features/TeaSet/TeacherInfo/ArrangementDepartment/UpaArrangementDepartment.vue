<template>
  <div class="upaTeacherInfo" v-loading="loading">
    <div class="pagehead">
      <h1>编辑教师学历信息</h1>
    </div>
    <div>
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
              :titles="['未设置', '已设置']"
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
        <!-- <el-form-item>
          <el-button type="primary" @click="onUpa('form')">编辑</el-button>
          <el-button @click="onBack">取消</el-button>
        </el-form-item> -->
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
        url: "/TeaManagement/TeaInfo/ArrangementDepartment.php",
        data:{
          type: "sel_tea",
          col: "userid,username",
          campus: this.$store.state.userCampus,
          department: this.department,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
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
      console.log(this.setTea);
    },
    submitUpload() {
      this.$refs.upload.submit();
    },
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePreview(file) {
      console.log(file);
    },
    
    handleChange(value, direction, movedKeys) {
      // console.log(value);       // 当前值
      // console.log(direction);   // 移动方向
      // console.log(movedKeys);   // 发生移动的值
      requestAjax({
        type: "get",
        url: "/TeaManagement/TeaInfo/ArrangementDepartment.php",
        data:{
          type: "upa_tea",
          department: this.department,
          set: direction == 'left' ? 'unset' : 'set',
          arr: movedKeys,
        },
        success:(res)=>{
          res = JSON.parse(res);
          if(res){
            this.$message({
              message: '设置成功',
              type: 'success'
            });
            
            // 日志写入
            let obj = {
              content: "修改教师所在部门 教师: "+ JSON.stringify(movedKeys),
              type: "修改记录",
              model: "教师部门设置模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }
            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);

          }
          else{
            this.$message.error('设置失败，请稍后再试！');
          }
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