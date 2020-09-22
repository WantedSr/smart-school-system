<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>公开课任务列表</h1>
    </div>

      <el-form size="small" :model="form" label-width="100px">
        <el-form-item label="听课人员">
          <div style="text-align: left">
            <el-transfer
              v-model="setTea"
              style="text-align: left; display: inline-block;"
              filterable
              :titles="['未设置', '已设置']"
              :button-texts="['到左边', '到右边']"
              :format="{
                noChecked: '${total}',
                hasChecked: '${checked}/${total}'
              }"
              @change="handleChange"
              :data="teacherData">
            </el-transfer>
          </div>
        </el-form-item>
        <el-form-item label="教学设想">
          <el-input 
          v-model="form.wanna"
          class="teachEva1"
          type="textarea" 
          placeholder="请填写教学设想，字数在100到500之间"
          :autosize="{ minRows: 8,}">
          </el-input>
        </el-form-item>
        <el-form-item label="课堂亮点">
          <el-input 
          v-model="form.light"
          class="teachEva2"
          type="textarea" 
          placeholder="请填写教学设想，字数在100到500之间"
          :autosize="{ minRows: 8,}">
          </el-input>
        </el-form-item>
        <!-- <el-form-item label="课堂图片">
          <el-upload
            action="https://jsonplaceholder.typicode.com/posts/"
            list-type="picture-card"
            :on-preview="handlePictureCardPreview"
            :on-remove="handleRemove">
            <i class="el-icon-plus"></i>
          </el-upload>
        </el-form-item> -->
        <el-form-item label="">
          <el-button size="small" @click="onBack" type="">返回</el-button>
          <el-button size="small" @click="onSubmit" type="primary">提交</el-button>
        </el-form-item>
      </el-form>

  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
import Limit from "../../Limit/main";
export default {
  data(){
    return{

      teaData: [],

      setTea: [],
      renderFunc(h, option) {
        return <span>{ option.key } - { option.label }</span>;
      },

      loading: false,

      form: {},

      departmentData: [],

      evaluationId: this.$route.query.evaId,

      img: "",

    }
  },
  computed:{
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
    }
  },
  created(){
    this.getTeacher();
    this.getData();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    onSubmit(){
      this.loading = true;
      requestAjax({
        url: "/teach/ReportEvaluation.php",
        type: 'post',
        data:{
          type: "set_evaluation",
          id: this.evaluationId,
          teacher: this.setTea,
          wanna: this.form.wanna,
          light: this.form.light,
          img: this.img,
          school: this.$store.state.userSchool,
          campus: this.$store.state.userCampus,
          informant: this.$store.state.userId,
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          if(res[0]){
            this.$message({
              message: '公开课填写成功',
              type: 'success'
            });

            // 日志写入
            let obj = {
              content: "填写公开课信息 "+ JSON.stringify(arr),
              type: res[1] == true ? "添加记录" : '修改记录',
              model: "公开课填写模块",
              ip: sessionStorage.getItem('ip'),
              user: this.$store.state.userId,
              area: sessionStorage.getItem("area"),
              brower: sysTool.GetCurrentBrowser(),
              addTime: new Date().getTime(),
            }

            let arr = Object.values(obj);
            this.$store.commit("writeLog",arr);
            
            this.$router.go(-1);
          }
          else{
            this.$message.error('填写失败，请稍后再试！');
          }
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
      console.log(value);       // 当前值
      console.log(direction);   // 移动方向
      console.log(movedKeys);   // 发生移动的值
      this.setTea = value;
    },

    getTeacher(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/teach/ReportEvaluation.php",
        async: false,
        data:{
          type: "sel_tea",
          selobj: {
            'campus': this.$store.state.userCampus,
            'type': '1',
          }
        },
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.teacherData = res;
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
    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/teach/ReportEvaluation.php",
        data:{
          type: "sel_evaluation_tea",
          col: "*",
          selobj:{
            campus: this.$store.state.userCampus,
            department: this.form.department,
            evaluation: this.evaluationId,
          }
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res);
          // console.log(res);
          this.loading = false;
          this.setTea = JSON.parse(res['teacher']);
          this.form.wanna = res['content'];
          this.form.light = res['highlight'];
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

    
    handleRemove(file, fileList) {
      console.log(file, fileList);
    },
    handlePictureCardPreview(file) {
      this.dialogImageUrl = file.url;
      this.dialogVisible = true;
    }
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>