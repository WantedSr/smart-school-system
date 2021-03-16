<template>
  <div class="sethead" v-loading="loading">
    <div class="userHead">
      <el-avatar :src="imgUrl" :size="120" icon="el-icon-user"></el-avatar>
    </div>
    <el-upload
      class="upload-demo"
      ref="upload"
      action="https://jsonplaceholder.typicode.com/posts/"
      :on-change="getFile"
      :show-file-list="false"
      :file-list="fileList"
      :auto-upload="false">
      <el-button slot="trigger" size="small" type="primary">选取文件</el-button>
      <div slot="tip" class="el-upload__tip">只能上传jpg/png文件，且不超过500kb</div>
    </el-upload>
  </div>
</template>

<script>
import {requestAjax} from "network/request_ajax";
export default {
  data(){
    return{
      imgUrl: "",
      loading: false,
      
      maxsize: 5 * 1024 * 1024,      // 文件上传最大内存
      mime: ["image/jpeg","image/png","image/gif"],     // 允许文件mime类型
      alone_ext: ['jpg','jpeg','png','gif'],      // 允许的文件后缀名
    }
  },
  created(){
    this.loading = true;
    requestAjax({
      type: 'post',
      url: "/userhead.php",
      data:{
        action: "getUserHead",
        request: JSON.stringify({
          school: this.$store.state.userSchool,
          campus: this.$store.state.userCampus,
          userid: this.$store.state.userId,
        }),
      },
      async: true,
      success:res=>{
        this.loading = false;
        res = JSON.parse(res);
        if(res.data.length > 0){
          let data = res.data[0];
          this.imgUrl = this.$store.state.endUrl + "/userhead/" + data.user_head;
        }else{
          this.imgUrl = "";
        }
        // console.log(res);
      },
      error:err=>{
        this.loading = false;
        console.error(err);
      }
    })
  },
  methods:{
    getFile(f) {
      // console.log(f);
      let file = f.raw;
      // console.log(file);
      if(this.mime.findIndex(item=>item==file.type) == -1){
        this.$message({
          type: "warning",
          message: "请选择合适类型的文件",
        });
        return false;
      }
      if(this.maxsize <= file.size){
        this.$message({
          type: 'warning',
          message: "文件大小不能大于5M",
        });
        return false;
      }
      this.loading = true;
      let formdata = new FormData();
      formdata.append("img",file);
      formdata.append("request",JSON.stringify({
        school: this.$store.state.userSchool,
        campus: this.$store.state.userCampus,
        userid: this.$store.state.userId,
      }));
      formdata.append("action",'setUserHead');
      requestAjax({
        type: "post",
        url: "/userhead.php",
        data: formdata,
        dataType: 'json', // 传递数据的格式
        async: false, // 这是重要的一步，防止重复提交的
        cache: false,  // 设置为false，上传文件不需要缓存。
        contentType: false, // 设置为false,因为是构造的FormData对象,所以这里设置为false。
        processData: false, // 设置为false,因为data值是FormData对象，不需要对数据做处理。
        success:res=>{
          this.loading = false;
          // res = JSON.parse(res);
          console.log(res);
          if(res.data.type == 'success'){
            let data = res.data;
            let filename = data.info;
            this.$message({
              type: "success",
              message: "头像上传成功！",
            });
            this.imgUrl = this.$store.state.endUrl + '/userhead/' + filename;
            location.reload();
          }else{
            this.$message.error("头像上传失败");
          }
        },
        error:err=>{
          this.loading = false;
          console.error(err);
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

<style>
  .sethead{
    text-align: center;
  }
  
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }
  
  .userHead{
    width: 120px;
    height: 120px;
    border-radius: 50%;
    color: #FFF;
    text-align: center;
    margin: 30px auto;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #f0f0f0;
    box-shadow: 2px 5px 5px rgba(88,88,88,.2);
  }
  .userHead i{
    font-size: 40px;
    position: relative;
    top: 10px;
  }
</style>