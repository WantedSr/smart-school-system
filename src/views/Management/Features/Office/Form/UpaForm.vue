<template>
  <div class="addPlacement">
    <div class="pagehead">
      <h1>修改审批表单</h1>
    </div>
    <el-form ref="form" :rules="rules" :model="form" label-width="100px">
      <el-form-item prop="form_name" label="申请名称">
        <el-input v-model="form.form_name" placeholder="请输入审批名称"></el-input>
      </el-form-item>
      <el-form-item prop="title" label="申请题头">
        <el-input v-model="form.title" placeholder="请输入审批题头"></el-input>
      </el-form-item>
      <el-form-item prop="alias" label="别名">
        <el-input v-model="form.alias" placeholder="请输入别名"></el-input>
      </el-form-item>
      <el-form-item prop="unit" label="单位选项">
        <el-input v-model="form.unit" placeholder="请输入单位"></el-input>
      </el-form-item>
      <el-form-item label="选择导航图标">
        <el-button @click="opIcon" size='small' icon="star-on">选择图标</el-button>
        <span style="margin-left: 6px">{{form.icon}}</span>
      </el-form-item>
      <el-form-item prop="authority" label="申请权限">
        <el-select size='small' v-model="form.authority" placeholder="申请权限">
          <el-option label="所有人" value="所有人"></el-option>
          <el-option label="权限组" value="权限组"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item prop="content" label="表单提示">
        <el-input type="textarea" v-model="form.content" placeholder="表单提示"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onUpa('form')">修改</el-button>
        <el-button @click="onBack">取消</el-button>
      </el-form-item>
    </el-form>
    
    <el-drawer
      title="选择导航图标"
      :visible.sync="drawer"
      direction="ltr"
      :before-close="handleClose">
      <el-row class="iconlist">
        <el-col v-for="(item,i) in iconlist" :key="'icon-'+i" :lg="6" :md="8" :sm="8" :xs="12">
          <div class="iconItem">
            <el-button @click="getIcon(item)" :class="'el-icon-'+item"></el-button>
          </div>
        </el-col>
      </el-row>
    </el-drawer>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      form:{
        form_id: "",
        form_name: "",
        title: "",
        alias: "",
        unit: "",
        authority: "",
        icon: "",
        content: "",
        process: "",
        state: "1",
        campus: this.$store.state.userCampus,
        school: this.$store.state.userSchool,
        created_user: this.$store.state.userId,
        addTime: "",
      },

      
      rules:{
        form_name: [
          { required: true, message: '请输入导航名称', trigger: 'blur' },
        ],
        title: [
          { required: true, message: '请选择导航编码', trigger: 'blur' }
        ],
        alias: [
          { required: true, message: '请选择导航等级', trigger: 'blur' }
        ],
        unit: [
          { required: true, message: '请选择导航等级', trigger: 'blur' }
        ],
        authority: [
          { required: true, message: '请选择导航等级', trigger: 'change' }
        ],
        content: [
          { required: true, message: '请选择导航等级', trigger: 'blur' }
        ],
      },

      drawer: false,

      iconlist:[
        "delete-solid","question","success","picture-outline",
        "s-tools","upload","download","bell","s-cooperation",
        "user","s-order","s-promotion","s-marketing","user-solid",
        "phone","s-data","edit","folder","document-copy",
        "warning-outline","document","collection","school",
        "price-tag","chat-dot-round","location","delete","setting",
        "s-help","help","picture-outline-round","camera","camera-solid",
        "video-camera-solid","video-camera","s-release","s-shop",
        "s-opportunity","share","s-finance","s-flag","finished",
        "milk-tea","apple","sugar","truck","service","basketball","watch","sunrise",
        "fork-spoon","food","burger","coffee","ice-cream-square",
      ],

    }
  },
  created(){
    this.getData();
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    opIcon(){
      this.drawer = true;
    },
    getIcon(v){
      this.form.icon = v;
      this.handleClose();
    },
    handleClose(done) {
      this.drawer = false;
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            type: "post",
            url: "/Office/Form.php",
            data: {
              type: "upa_form",
              obj: this.form,
              sel: 'id',
              val: this.form.id,
            },
            success:(res)=>{
              this.loading = false;
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，修改审批表单成功',
                  type: 'success'
                });
              
                // 日志写入
                let obj = {
                  content: "修改审批表单信息 表单"+ JSON.stringify(this.form.form_id),
                  type: "修改记录",
                  model: "审批表单模块",
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
                this.$message.error('修改失败，请稍后再试！');
              }
            },
            error:(err)=>{
              this.loading = false;
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器错误，请稍后再试!'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },

    getData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/Office/Form.php",
        data:{
          type: "sel_form",
          selobj: {
            campus: this.$store.state.userCampus,
            id: this.$route.query.proId,
          },
        },
        success:(res)=>{
          res = JSON.parse(res);
          this.form = res[0];
        },
        error:(err)=>{
          this.loading = false;
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
  }
}
</script>

<style>

  .el-drawer{
    overflow-y: scroll;
  }
  .iconlist{
  }
  .iconItem{
    text-align: center;
    margin-bottom: 12px;
  }
  .iconItem button{
    font-size: 30px;
  }
  @media screen and (max-width:992px){
    .iconItem button{
      font-size: 18px;
    }
  }
  @media screen and (max-width:768px){
    .iconItem button{
      font-size: 14px;
    }
  }
</style>