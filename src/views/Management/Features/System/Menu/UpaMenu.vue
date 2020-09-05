<template>
  <div class="upaMenu">
    <div class="pagehead">
      <h1>修改菜单</h1>
    </div>
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="100px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="nav_id" label="导航ID">
          <el-input style="width: 250px" v-model="sel_upa.nav_id" placeholder="导航ID"></el-input>
        </el-form-item>
        <el-form-item prop="nav_name" label="导航名称">
          <el-input style="width: 250px" v-model="sel_upa.nav_name" placeholder="导航名称"></el-input>
        </el-form-item>
        <el-form-item v-if="sel_upa.nav_lever == '1'" prop="nav_group" label="导航顺序">
          <el-input style="width: 250px" v-model="sel_upa.nav_group" placeholder="导航顺序"></el-input>
        </el-form-item>
        <el-form-item prop="nav_lever" label="导航级别">
          <el-select @change="selNavId1" v-model="sel_upa.nav_lever" placeholder="导航级别">
            <el-option label="一级导航" value="1"></el-option>
            <el-option label="二级导航" value="2"></el-option>
            <el-option label="三级导航" value="3"></el-option>
            <el-option label="四级导航" value="4"></el-option>
          </el-select>
        </el-form-item>
        <div v-if="sel_upa.nav_lever != 1">
          <el-form-item label="上级导航1">
            <el-select @change="selNav2" v-model="nav_lever_up1" placeholder="上级导航1">
              <el-option v-for="(item,i) in nav1List" :key="'1-'+i" :label="item.nav_name" :value="item.nav_id"></el-option>
            </el-select>
          </el-form-item>
          <div v-if="sel_upa.nav_lever != 2">
            <el-form-item label="上级导航2">
              <el-select @change="selNav3" v-model="nav_lever_up2" placeholder="上级导航2">
                <el-option v-for="(item,i) in nav2List" :key="'2-'+i" :label="item.nav_name" :value="item.nav_id"></el-option>
              </el-select>
            </el-form-item>
            <div v-if="sel_upa.nav_lever != 3">
              <el-form-item label="上级导航3">
                <el-select @change="selNavId4" v-model="nav_lever_up3" placeholder="上级导航3">
                  <el-option v-for="(item,i) in nav3List" :key="'3-'+i" :label="item.nav_name" :value="item.nav_id"></el-option>
                </el-select>
              </el-form-item>
            </div>
          </div>
        </div>
        <div v-if="sel_upa.nav_lever == 2">
          <el-form-item label="选择导航图标">
            <el-button @click="opIcon" size='small' icon="star-on">选择图标</el-button>
            <span style="margin-left: 6px">{{sel_upa.icon}}</span>
          </el-form-item>
        </div>
        <div style="margin-bottom: 24px" v-if="sel_upa.nav_lever != 1">
          <el-form-item label="导航链接">
            <el-input style="width: 400px" v-model="sel_upa.nav_url" placeholder="链接地址"></el-input>
          </el-form-item>
          <el-alert
            title="注意：若导航级别为 3 ，且设置了导航的链接地址url后，则不可以作为父级导航，导航等级为2和4时，必须设置导航url"
            :closable="false"
            type="warning"
            show-icon>
          </el-alert>
          <el-alert
            v-if="sel_upa.nav_lever == 2"
            title="注意：导航级别为2时才能也必须选择导航图标"
            :closable="false"
            type="warning"
            show-icon>
          </el-alert>
          <el-alert
            title="注意：只有导航级别为1、2、3可作为父级导航"
            :closable="false"
            type="warning"
            show-icon>
          </el-alert>
        </div>
        <el-form-item label="">
          <el-button type="success" @click="onUpa('sel_upa')">修改</el-button>
          <el-button @click="onBack">取消</el-button>
        </el-form-item>
      </el-form>
    </div>
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
      nav_lever_up1: "",
      nav_lever_up2: "",
      nav_lever_up3: "",
      sel_upa:{
        nav_id: "",
        nav_name: "",
        nav_up_id: "",
        nav_lever: "1",
        nav_group: "",
        nav_url: "",
        nav_state: '1',
        nav_time: "",
        nav_memo: "1",
        icon: "",
      },
      drawer: false,
      rules:{
        nav_id: [
          { required: true, message: '请输入导航名称', trigger: 'blur' },
        ],
        nav_name: [
          { required: true, message: '请选择导航编码', trigger: 'blur' }
        ],
        nav_lever: [
          { required: true, message: '请选择导航等级', trigger: 'change' }
        ],
        nav_group: [
          { required: true, message: '请输入导航排序', trigger: 'blur' }
        ],
      },
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
        "reading",
      ],
    }
  },
  methods:{
    handleClose(done) {
      this.drawer = false;
    },
    opIcon(){
      this.drawer = true;
    },
    selNav2(v){
      this.nav_lever_up2 = '';
      this.$emit("getNav2",v,'data');
    },
    selNav3(v){
      this.nav_lever_up3 = '';
      this.$emit("getNav3",v,'data');
    },
    getNavId(upnav='0'){
      let num = 0;
      requestAjax({
        type: 'get',
        url: "/system/menu.php",
        data:{
          reqType: "get_menu_id",
          upnav: upnav,
        },
        async: false,
        success:(res)=>{
          res = parseInt(JSON.parse(res)[0]['COUNT(*)']);
          num = res;
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      })
      return num;
    },
    selNavId1(){
      this.sel_upa.nav_group = '';
      this.nav_lever_up1 = '';
    },
    onBack(){
      this.$router.go(-1);
    },
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if(this.sel_upa.nav_lever == '2' && this.nav_lever_up1 == ''){
            this.$message('请选择导航选项!');
            return false;
          }
          if(this.sel_upa.nav_lever == '2' && this.sel_upa.icon == ''){
            this.$message('请选择导航图标!');
            return false;
          }
          if(this.sel_upa.nav_lever == '2' && this.sel_upa.nav_url == ''){
            this.$message('请选择导航链接!');
            return false;
          }
          if(this.sel_upa.nav_lever == '3' && this.nav_lever_up2 == ''){
            this.$message('请选择导航选项!');
            return false;
          }
          if(this.sel_upa.nav_lever == '4' && this.nav_lever_up3 == ''){  
            this.$message('请选择导航选项!');
            return false;
          }
          if(this.sel_upa.nav_lever == '4' && this.sel_upa.nav_url == ''){  
            this.$message('请选择导航链接!');
            return false;
          }
          if(this.sel_upa.nav_lever == '1'){
            if(String(this.sel_upa.nav_group).length == 1){
              this.sel_upa.nav_group = '00'+this.sel_upa.nav_group;
            }else if(String(this.sel_upa.nav_group).length == 2){
              this.sel_upa.nav_group = '0'+this.sel_upa.nav_group;
            }
            this.sel_upa.nav_up_id = '0';
          }else if(this.sel_upa.nav_lever == '2'){
            this.sel_upa.nav_up_id = this.nav_lever_up1;
          }else if(this.sel_upa.nav_lever == '3'){
            this.sel_upa.nav_up_id = this.nav_lever_up2;
          }else if(this.sel_upa.nav_lever == '4'){
            this.sel_upa.nav_up_id = this.nav_lever_up3;
          }
          requestAjax({
            type: "get",
            url: "/system/menu.php",
            data: {
              reqType: "upa_menu",
              obj: this.sel_upa,
              sel: "id",
              val: this.sel_upa.id,
            },
            success:(res)=>{
              if(res){
                this.$message({
                  message: '修改学校成功',
                  type: 'success'
                });

                
                // 日志写入
                let obj = {
                  content: "修改菜单 "+this.sel_upa.nav_id,
                  type: "修改记录",
                  model: "菜单模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                };
                let arr = Object.values(obj);
                this.writeLog(arr);

                this.$router.go(-1);
              }
              else{
                this.$message.error('修改失败，请稍后再试！');
              }
            },
            error:(err)=>{
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
    getIcon(v){
      this.sel_upa.icon = v;
      this.handleClose();
    },
    getNavData(){
      requestAjax({
        type: "get",
        url: "/system/menu.php",
        data:{
          reqType: "sel_menu",
          selobj: {
            'nav_id': this.$route.query.navId,
          },
          col: "*",
          upnav: 1,
        },
        async: false,
        success:(res)=>{
          // console.log(res);
          res = JSON.parse(res)[0];
          this.sel_upa = res;
          // if(this.sel_upa.nav_lever == 2){

          // }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器错误，请稍后再试!'
          });
        }
      })
    },
    writeLog(arr){
      requestAjax({
        type: "get",
        url: "/system/systemLog.php",
        data:{
          type: 'add_log',
          arr: arr,
        },
        async: false,
        success:(res)=>{
          res = JSON.parse(res);
          if(res){
            return;
          }else{
            console.log("日志填写失败！");
          }
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器出错，请稍后再试！',
            duration: 0,
          });
        }
      });
    },
  },
  created(){
    this.getNavData();
    if(this.sel_upa.nav_lever > 1){
      this.nav_lever_up1 = this.sel_upa.nav_up_id;
      if(this.sel_upa.nav_lever > 2){
        this.selNav2(this.nav_lever_up1);
        this.nav_lever_up2 = this.sel_upa.nav_up_id;
        this.nav_lever_up1 = this.sel_upa.nav_up_id.substr(0,4);
      }
    }
  },
  props:{
    nav1List:{
      type: Array,
      require: true,
    },
    nav2List:{
      type: Array,
      require: true,
    },
    nav3List:{
      type: Array,
      require: true,
    },
  },
}
</script>

<style>

</style>