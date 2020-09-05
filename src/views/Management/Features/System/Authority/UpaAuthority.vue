<template>
  <div v-loading="loading">
    <div class="pagehead">
      <h1>编辑组</h1>
    </div>
    <el-form ref="sel_upa" :rules="rules" :model="sel_upa" label-width="100px">
      <el-form-item prop="authority_id" label="组编号">
        <el-input v-model="sel_upa.authority_id" placeholder="请输入组编号"></el-input>
      </el-form-item>
      <el-form-item prop="authority_name" label="组名称">
        <el-input v-model="sel_upa.authority_name" placeholder="请输入组名称"></el-input>
      </el-form-item>
      <el-form-item label="选择权限">
        <el-tree
          :data="data"
          show-checkbox
          ref="tree"
          :default-checked-keys="authorityRange"
          node-key="id"
          @check-change="handleCheckChange"
          :props="defaultProps">
        </el-tree>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="onSubmit('sel_upa')">修改</el-button>
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
      sel_upa:{},
      data: [],
      defaultProps: {
        children: 'children',
        label: 'label'
      },
      authorityRange: [],
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
    this.getNavData();
    this.getDeaData();
  },
  methods:{
    getNavData(){
      this.loading = true;
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
          this.loading = false;
          console.log(err);
            this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
    },
    getDeaData(){
      this.loading = true;
      requestAjax({
        type: "get",
        url: "/system/authority.php",
        data:{
          type: 'sel_authority',
          selobj: {
            authority_id: this.$route.query.authId,
          },
        },
        success:(res)=>{
          this.loading = false;
          this.sel_upa = JSON.parse(res)[0];
          let arr = JSON.parse(this.sel_upa.authority_range);
          let newarr = [];
          $.each(arr, function (i, v) { 
            $.each(arr[i]['child'], function (i2, v2) { 
              newarr.push(v2);
            });
          });
          // this.$refs.tree.setCheckedNodes(JSON.parse(this.sel_upa.authority_range)); 
          // this.$refs.tree.setCheckedNodes(newarr); 
          this.authorityRange = newarr;
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
    onSubmit(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.newarr = [];
          $.each(this.arr, (i, v)=>{ 
            if(v.type == 'parent'){
              let obj = {
                id: v.id,
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
          this.sel_upa.authority_range = [];
          this.sel_upa.authority_range = JSON.stringify(this.newarr,true);
          requestAjax({
            type: "GET",
            url: "/system/authority.php",
            data:{
              type: "upa_authority",
              obj: this.sel_upa,
              sel: 'id',
              val: this.sel_upa.id,
            },
            async: false,
            success:(res)=>{
              // console.log(this.sel_upa);
              // console.log(res);
              if(res){
                this.$message({
                  message: '恭喜你，修改权限信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "编辑权限组 "+this.sel_upa.authority_id,
                  type: "修改记录",
                  model: "权限组模块",
                  ip: sessionStorage.getItem('ip'),
                  user: this.$store.state.userId,
                  area: sessionStorage.getItem("area"),
                  brower: sysTool.GetCurrentBrowser(),
                  addTime: new Date().getTime(),
                }
                let arr = Object.values(obj);
                this.writeLog(arr);

                this.$router.go(-1);
              }else{
                this.$message.error('修改权限信息失败，请稍后再试！');
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
  }
}
</script>

<style>

</style>