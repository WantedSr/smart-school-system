<template>
  <div>
    <div class="pagehead">
      <h1>添加组</h1>
    </div>
    <el-form ref="sel_add" :rules="rules" :model="sel_add" label-width="100px">
      <el-alert
        title="关于组的编号设定"
        type="warning"
        :closable="false"
        style="margin-bottom: 12px"
        description="组的分类为三类->学生，教师，管理员。学生的编号开头为 S，教师开头编号为 T，管理员开头编号为 D，其中文员，外包人员人员皆隶属于教师。可在这三者的基础上细分，如教师类设立德育管理员，实习管理员，管理员设立用户管理员，数据管理员等"
        show-icon>
      </el-alert>
      <!-- <el-alert
        title="可在这三者的基础上细分，如教师类设立德育管理员，实习管理员，管理员设立用户管理员，数据管理员等"
        type="warning"
        style="margin-bottom: 12px"
        :closable="false">
      </el-alert> -->
      <el-form-item prop="authority_id" label="组编号">
        <el-input v-model="sel_add.authority_id" placeholder="请输入组编号"></el-input>
      </el-form-item>
      <el-form-item prop="authority_name" label="组名称">
        <el-input v-model="sel_add.authority_name" placeholder="请输入组名称"></el-input>
      </el-form-item>
      <el-form-item label="选择权限">
        <el-tree
          :data="data"
          show-checkbox
          v-loading="loading"
          ref="tree"
          node-key="id"
          @check-change="handleCheckChange"
          :props="defaultProps">
        </el-tree>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('sel_add')">创建</el-button>
        <el-button @click="onBack">取消</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel_add:{
        authority_id: "",
        authority_name: "",
        authority_range: "",
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        status: '1',
        created_user: this.$store.state.userId,
      },
      data: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      rules:{
        authority_name: [
          { required: true, message: '请输入组名称', trigger: 'blur' },
        ],
        authority_id: [
          { required: true, message: '请选择组代码', trigger: 'blur' }
        ],
      },
      arr: [],
      newarr: [],
      loading: false,
    }
  },
  created(){
    requestAjax({
      type: "get",
      url: "/system/menu.php",
      data:{
        reqType: "sel_menu",
        type: 'data',
        upnav: 'a',
        selobj:{
          nav_lever: 1,
        },
      },
      success:(res)=>{
        let arr = JSON.parse(res);
        let newarr = [];
        $.each(arr, function (i, v) {
          let obj = {
            id: v.nav_id,
            label: v.nav_name,
            type: "parent",
          }
          requestAjax({
            type: 'get',
            url: "/system/menu.php",
            data:{
              reqType: "sel_menu",
              type: 'data',
              upnav: "a",
              selobj:{
                nav_up_id: v.nav_id,
                nav_lever: 2,
              },
            },
            async: false,
            success:(res2)=>{
              let arr2 = JSON.parse(res2);
              let newarr2 = [];
              $.each(arr2, function (i2, v2) { 
                let obj2 = {
                  id: v2.nav_id,
                  label: v2.nav_name,
                  up_id: v.nav_id,
                  type: 'child',
                }
                newarr2.push(obj2);
              });
              obj['children'] = newarr2;
              newarr.push(obj);
            },
            error:(err)=>{
              console.log(err);
              this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            }
          })
        });
        this.data = newarr;
        this.loading = false;
      },
      error:(err)=>{
        console.log(err);
      }
    });
  },
  methods:{
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          if(this.arr.length != 0){
            this.newarr = [];
            $.each(this.arr, (i, v)=>{ 
              if(v.type == 'parent'){
                let obj = {
                  id: v.id,
                  label: v.label,
                  child: [],
                }
                this.newarr.push(obj);
                // console.log(this.newarr);
              }
              else if(v.type == 'child'){
                let num = 0;
                $.each(this.newarr,(i2, v2)=>{ 
                  if(v2.id == v.up_id){
                    this.newarr[i2].child.push(v.id);
                    return;
                  }
                  else{
                    num ++;
                  }
                });
                if(this.newarr.length == num){
                  this.newarr.push({
                    id: v.up_id,
                    child: [],
                  });
                  this.newarr[this.newarr.length-1].child.push(v.id);
                }
              }
            });
            this.sel_add.authority_range = JSON.stringify(this.newarr);
          }
          let addarr = Object.values(this.sel_add);
          addarr.push(new Date().getTime());

          requestAjax({
            type: "post",
            url: "/system/authority.php",
            data:{
              type: "add_authority",
              arr: addarr,
            },
            async: true,
            success:(res)=>{
              res = JSON.parse(res);
              if(res){
                this.$message({
                  message: '恭喜你，添加组信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "添加权限组 "+this.sel_add.authority_id,
                  type: "添加记录",
                  model: "权限组模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.$store.commit("writeLog",arr);

                this.$router.go(-1);
              }else{
                this.$message.error('添加组失败，请稍后再试！');
              }
            },
            error:(err)=>{
              console.log(err);
                this.$notify.error({
                title: '错误',
                message: '服务器有误！,请稍后再试！'
              });
            }
          });
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    onBack(){
      this.$router.go(-1);
    },
    handleCheckChange () {
      let res = this.$refs.tree.getCheckedNodes();
      // let res = this.$refs.tree.getCheckedKeys(true);
      // console.log(res);
      let arr = []
      res.forEach((item) => {
        arr.push({
          id: item.id,
          label: item.label,
          type: item.type,
          up_id: item.up_id ? item.up_id : 0,
        })
      })
      this.arr = arr;
    },
  }
}
</script>

<style>

</style>