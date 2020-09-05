<template>
  <div id="app" v-loading.fullscreen.lock="fullscreenLoading">
    <router-view/>
  </div>
</template>

<script>
export default {
  data(){
    return{
      fullscreenLoading: false,
      imglist: document.getElementsByTagName("img"),
      imgarr: [],
      okImg: 0,
    }
  },
  created(){
    // this.fullscreenLoading = true;
    // for(let i=0;i<this.imglist.length;i++){
    //   this.imgarr.push(this.imglist[i].src);
    // }
    // for(let r=0;r<this.imgarr.length;r++) {
    //   let imgobj = new Image();
    //   imgobj.src = this.imgarr[r];
    //   imgobj.onload = (even)=>{
    //     this.okImg ++;
    //     if(this.okImg >= this.imgarr.length){
    //       this.fullscreenLoading = false;
    //     }
    //   };
    // }
    // setInterval(()=>{
    //   this.$store.commit("getUser");
    // },36000)
  },
}
</script>

<style>
  @import url("assets/css/base.css");
</style>
