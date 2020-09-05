<template>
  <div>
    <div class="pagehead">
      <h1>菜单管理</h1>
    </div>
    <div style="margin-bottom: 12px">
      <el-row>
        <el-col :lg="20" :md="24" :sm="24" :xs="24">
          <el-form :inline="true" :model="sel" class="demo-form-inline">
            <el-form-item label="">
              <el-select v-model="sel.nav_type" placeholder="导航位置">
                <el-option label="一级导航" value="1"></el-option>
                <el-option label="二级导航" value="2"></el-option>
                <el-option label="三级导航" value="3"></el-option>
                <el-option label="四级导航" value="4"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item v-if="sel.nav_type >= 2" label="">
              <el-select @change="selNav2" v-model="sel.nav_1" placeholder="一级导航">
                <el-option v-if="nav1List.length > 0" label="所有一级导航" value="all"></el-option>
                <el-option v-for="(item,i) in nav1List" :key="'1-'+i" :label="item.nav_name" :value="item.nav_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item v-if="sel.nav_type >= 3" label="">
              <el-select @change="selNav3" v-model="sel.nav_2" placeholder="二级导航">
                <el-option v-if="nav2List.length > 0" label="所有二级导航" value="all"></el-option>
                <el-option v-for="(item,i) in nav2List" :key="'2-'+i" :label="item.nav_name" :value="item.nav_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item v-if="sel.nav_type >= 4" label="">
              <el-select v-model="sel.nav_3" placeholder="三级导航">
                <el-option v-if="nav3List.length > 0" label="所有三级导航" value="all"></el-option>
                <el-option v-for="(item,i) in nav3List" :key="'3-'+i" :label="item.nav_name" :value="item.nav_id"></el-option>
              </el-select>
            </el-form-item>
            <el-form-item>
              <el-button type="primary" size="small" @click="onSubmit">查询</el-button>
              <el-button type="success" size="small" @click="onAdd">添加</el-button>
              <el-button type="danger" size="small" @click="onDel">删除</el-button>
            </el-form-item>
          </el-form>
        </el-col>
        <el-col :lg="4" :md="24" :sm="24" :xs="24">
          <el-input
            placeholder="输入关键字搜索"
            v-model="search">
            <i slot="prefix" class="el-input__icon el-icon-search"></i>
          </el-input>
        </el-col>
      </el-row>
    </div>
    <div class="box">
      <el-table
        border
        size="small"
        :data="tableData.filter(data => !search || data.nav_id.toLowerCase().includes(search.toLowerCase()) || data.nav_name.toLowerCase().includes(search.toLowerCase()))"
        ref="multipleTable"
        tooltip-effect="dark"
        @selection-change="handleSelectionChange"
        v-loading="loading"
        element-loading-text="拼命加载中"
        element-loading-spinner="el-icon-loading"
        element-loading-background="rgba(0, 0, 0, 0.8)"
        style="width: 100%">
        <el-table-column
          type="selection"
          width="50">
        </el-table-column>
        <el-table-column
          prop="nav_id"
          label="导航ID"
          align="center"
          width="120"
          sortable>
        </el-table-column>
        <el-table-column
          prop="nav_name"
          label="导航名称"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="nav_up_id"
          label="上级导航"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="nav_lever"
          label="导航级别"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="nav_group"
          label="导航组别"
          align="center"
          sortable>
        </el-table-column>
        <el-table-column
          prop="state"
          label="状态"
          align="center"
          width="120"
          sortable>
          <template slot-scope="scope">
            <el-tag size="small" :type="scope.row.nav_state == '1' ? 'success' : 'danger'">{{scope.row.nav_state == '1' ? '启用' : "禁用"}}</el-tag>
          </template>
        </el-table-column>
        <el-table-column
          prop="do"
          label="操作"
          width="200"
          align="center">
          <template v-slot="scope">
            <el-button size="mini" type="primary" @click="onUpa(scope.row.nav_id)">编辑</el-button>
            <el-button size="mini" @click="onStart(scope.row.nav_id,scope.row.nav_state)" :type="scope.row.nav_state == '1' ? 'danger' : 'success'">{{scope.row.nav_state == '1' ? '禁用' : "启用"}}</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <limit @setPage="setPage" :total="total" :sum="sum"></limit>
  </div>
</template>

<script>
import Limit from "../../Limit/main";
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      sel:{
        nav_type: "1",
        nav_1: "",
        nav_2: "",
        nav_3: "",
      },
      tableData:[],
      n: this.$route.query.n,
      multipleTable: [],
      search: "",
      sum: 0,
      page: 1,
      total: 20,
      loading: false,
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
  methods:{
    setPage(page){
      this.page = page;
      this.onSubmit();
    },
    getnav(col="*",lever=1,upnav=null,type='data',start=0,num=15){
      this.loading = true;
      let navlist = [];
      requestAjax({
        url: "/system/menu.php",
        type: 'get',
        data:{
          reqType: "sel_menu",
          col: col,
          lever: lever,
          upnav: upnav,
          start: start,
          num: num,
          type: type,
        },
        async: false,
        success:(res)=>{
          this.loading = false;
          navlist = JSON.parse(res);
          // console.log(navlist);
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
      return navlist;
    },
    getnavSum(col="*",lever=1,upnav=null,start=0,num=15){
      let navlist = [];
      requestAjax({
        url: "/system/menu.php",
        type: 'get',
        data:{
          reqType: "sel_menu",
          type: "data",
          col: "COUNT(*)",
          lever: lever,
          upnav: upnav,
          start: start,
          num: num,
        },
        async: false,
        success:(res)=>{
          // console.log(res);
          res = JSON.parse(res)[0]["COUNT(*)"];
          this.sum = parseInt(res);
        },
        error:(err)=>{
          console.log(err);
          this.$notify.error({
            title: '错误',
            message: '服务器有误！,请稍后再试！'
          });
        }
      });
      return navlist;
    },
    onAdd(){
      this.$router.push({
        query:{
          n: this.n,
          type: 'add',
        },
      })
    },
    onUpa(id){
      this.$router.push({
        query:{
          n: this.n,
          type: 'upa',
          navId: id,
        },
      })
    },
    selNav2(v){
      if(this.sel.nav_type == '1') return;
      if(v == 'all') v = null;
      this.sel.nav_2 = '';
      this.$emit("getNav2",v,'data');
    },
    selNav3(v){
      if(this.sel.nav_type == '1' || this.sel.nav_type == '2') return;
      let upnav = this.sel.nav_1;
      if(this.sel.nav_2 == 'all') upnav = null;
      else upnav = this.sel.nav_2;
      this.sel.nav_3 = '';
      this.$emit("getNav3",upnav,'data');
    },
    onSubmit(){
      this.loading = true;
      this.search = "";
      let start = (this.page-1)*this.total;
      if(this.sel.nav_type == '1'){
        this.tableData = this.getnav("*",1,0,'list',start,this.total);
        this.getnavSum("*",1,0,start,this.total);
        this.loading = false;
      }
      else if(this.sel.nav_type == '2' && this.sel.nav_1 != ''){
        let upnav = this.sel.nav_1 == 'all' ? null : this.sel.nav_1;
        this.tableData = [];
        this.tableData = this.getnav("*",2,upnav,'list',start,this.total);
        this.getnavSum("*",2,upnav,start,this.total);
        this.loading = false;
      }
      else if(this.sel.nav_type == '3' && this.sel.nav_1 != '' && this.sel.nav_2 != ''){
        this.tableData = [];
        let upnav;
        if(this.sel.nav_2 != 'all'){
          upnav = this.sel.nav_2;
        }else{
          if(this.sel.nav_1 != 'all'){
            upnav = this.sel.nav_1;
            let parent = this.getnav("*",2,upnav,'data',start,this.total);
            let arr = [];
            for(let i=0;i<parent.length;i++){
              let row = parent[i];
              let child = this.getnav("*",3,row['nav_id'],'data',start,this.total);
              $.each(child, function (i, v) { 
                arr.push(v);
              });
            }
            this.sum = arr.length;
            this.tableData = arr;
            this.loading = false;
            return;
          }
        }
        this.loading = false;
        this.tableData = this.getnav("*",3,upnav,'list',start,this.total);
        this.getnavSum("*",3,upnav,start,this.total);
      }
      else if(this.sel.nav_type == '4' && this.sel.nav_1 != '' && this.sel.nav_2 != '' && this.sel.nav_3 != ""){
        this.tableData = [];
        let upnav;
        if(this.sel.nav_3 != 'all'){
          upnav = this.sel.nav_3;
          console.log(upnav);
          this.tableData = this.getnav("*",4,upnav,'list',start,this.total);
          this.getnavSum("*",4,upnav,start,this.total);
          this.loading = false;
        }else{
          if(this.sel.nav_2 != 'all'){
            upnav = this.sel.nav_2;
            let parent = this.getnav("*",3,upnav,'data',start,this.total);
            let arr = [];
            for(let i=0;i<parent.length;i++){
              let row = parent[i];
              let child = this.getnav("*",4,row['nav_id'],'data',start,this.total);
              $.each(child, function (i, v) { 
                arr.push(v);
              });
            }
            this.sum = arr.length;
            this.tableData = arr;
            this.loading = false;
            return;
          }
          else if(this.sel.nav_1 != 'all'){
            upnav = this.sel.nav_1;
            let parent = this.getnav("*",2,upnav,'data',start,this.total);
            let arr = [];
            for(let i=0;i<parent.length;i++){
              let row = parent[i];
              let child = this.getnav("*",3,row['nav_id'],'data',start,this.total);
              $.each(child, (i, v)=>{ 
                let $upid = v.nav_id;
                let child2 = this.getnav("*",4,$upid,'data',start,this.total);
                $.each(child2, (i2, v2)=>{ 
                  arr.push(v2);
                });
              });
            }
            this.sum = arr.length;
            this.tableData = arr;
            this.loading = false;
            return;
          }
          else{
            this.tableData = this.getnav("*",4,null,'list',start,this.total);
            this.getnavSum("*",4,null,start,this.total);
            this.loading = false;
            return;   
          }
        }
      }
      else{
        this.loading = false;
        this.$message('请选择选项!');
      }
    },
    handleSelectionChange(val) {
      this.multipleTable = val;
    },
    onStart(id,state){
      state = state == '1' ? '0' : '1';
      requestAjax({
        type: 'get',
        url: "/system/menu.php",
        data: {
          reqType: 'upa_menu',
          obj:{
            nav_state: state,
          },
          sel: "nav_id",
          val: id,
        },
        success:(res)=>{
          if(res){
            this.$message({
              message: '修改成功',
              type: 'success'
            });
            this.onSubmit();
          }else{
            this.$message.error('修改失败，请稍后再试！');
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
    },
  },
  components:{
    Limit,
  }
}
</script>

<style>

</style>