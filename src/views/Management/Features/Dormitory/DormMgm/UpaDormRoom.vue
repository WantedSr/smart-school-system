<template>
  <div class="upaDormRoom">
    <div class="pagehead">
      <h1>编辑宿舍房间</h1>
    </div>
    
    <div class="sel_upa">
      <el-form label-position="left" ref="sel_upa" :rules="rules" label-width="80px" :model="sel_upa" class="demo-form-inline">
        <el-form-item prop="dormroom_name" label="房间名称">
          <el-input style="width: 250px" v-model="sel_upa.dormroom_name" placeholder="房间名称"></el-input>
        </el-form-item>
        <el-form-item prop="dormroom_bed_num" label="可用床位">
          <el-input style="width: 250px" v-model="sel_upa.dormroom_bed_num" placeholder="可用床位"></el-input>
        </el-form-item>
        <el-form-item prop="dormroom_bed_num" label="宿舍楼">
          <el-select v-model="sel_upa.build" placeholder="选择宿舍楼">
            <el-option v-for="(item,i) in buildData" :key="'b-'+i" :label="item.build_name" :value="item.build_id"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item prop="dormroom_id" label="房间编号">
          <el-input style="width: 250px" v-model="sel_upa.dormroom_id" placeholder="输入房间编号"></el-input>
        </el-form-item>
        <el-form-item label="">
          <el-button size="small" type="success" @click="onUpa('sel_upa')">修改</el-button>
          <el-button size="small" @click="onBack">取消</el-button>
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
    return{
      sel_upa:{},
      rules:{
        dormroom_id: [
          { required: true, message: '请输入宿舍房间编码', trigger: 'blur' },
        ],
        dormroom_name: [
          { required: true, message: '请输入宿舍房间名称', trigger: 'blur' }
        ],
        dormroom_bed_num: [
          { required: true, message: '请输入宿舍房间床位数量', trigger: 'blur' }
        ],
        build: [
          { required: true, message: '请选择宿舍房间所在宿舍楼', trigger: 'change' }
        ],
      },
    }
  },
  created(){
    this.getData("*",{
      'dormroom_id':this.$route.query.roomId,
    });
  },
  props:{
    buildData:{
      type: Array,
      require: true,
    }
  },
  methods:{
    onBack(){
      this.$router.go(-1);
    },
    getData(col,selobj){
      requestAjax({
        url: "/dormitory/dormroom.php",
        type: 'get',
        data:{
          type: "sel_dormroom",
          col: col,
          selobj: selobj,
        },
        async: true,
        success:(res)=>{
          res = JSON.parse(res)[0];
          this.sel_upa = res;
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
    onUpa(formName){
      this.$refs[formName].validate((valid) => {
        if (valid) {
          this.loading = true;
          requestAjax({
            url: "/dormitory/dormroom.php",
            type: 'get',
            data:{
              type: "upa_dormroom",
              sel: 'id',
              val: this.sel_upa.id,
              obj: this.sel_upa,
            },
            async: true,
            success:(res)=>{
              if(res){
                this.$message({
                  message: '修改宿舍房间信息成功',
                  type: 'success'
                });

                // 日志写入
                let obj = {
                  content: "修改宿舍记录 "+this.sel_upa.dormroom_id,
                  type: "修改记录",
                  model: "宿舍模块",
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
                message: '服务器有误！,请稍后再试！'
              });
            }
          })
        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
  }
}
</script>

<style>

</style>