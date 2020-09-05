<template>
  
  <div class="subpage" v-loading="loading">
    <div v-if="toDef">
      <sel-status></sel-status>
    </div>
    <div v-else-if="toAdd">
      <add-status></add-status>
    </div>
    <div v-else-if="toUpa">
      <upa-status></upa-status>
    </div>
  </div>

</template>

<script>
import * as sysTool from "assets/js/systemTool";
import {requestAjax} from "network/request_ajax"; 
import SelStatus from './selStatus';
import addStatus from './addStatus';
import upaStatus from './upaStatus';
export default {
  data(){
    return{
      loading: false,
      ip: "",
      area: "",
      brower: "",
      os: "",
    }
  },
  computed:{
    toDef(){
      if(this.$route.query.type == 'sel' || this.$route.query.type == undefined){
        return true;
      }
      return false;
    },
    toAdd(){
      if(this.$route.query.type == 'add'){
        return true;
      }
      return false;
    },
    toUpa(){
      if(this.$route.query.type == 'upa'){
        return true;
      }
      return false;
    }
  },
  created(){
    this.$store.commit("getUser");
  },
  mounted(){
    this.ip = sessionStorage.getItem('ip')
    this.area = sessionStorage.getItem('area')
    this.brower = sysTool.GetCurrentBrowser()
    this.os = sysTool.GetOs()
    // console.log('ip，地区，浏览器，操作系统，：',  this.ip, this.area,this.brower, this.os)
  },
  methods:{
    // getUserIP(onNewIP) {
    //   let MyPeerConnection = window.RTCPeerConnection || window.mozRTCPeerConnection || window.webkitRTCPeerConnection;
    //   let pc = new MyPeerConnection({
    //     iceServers: []
    //   });
    //   let noop = () => {};
    //   let localIPs = {};
    //   let ipRegex = /([0-9]{1,3}(\.[0-9]{1,3}){3}|[a-f0-9]{1,4}(:[a-f0-9]{1,4}){7})/g;
    //   let iterateIP = (ip) => {
    //     if (!localIPs[ip]) onNewIP(ip);
    //     localIPs[ip] = true;
    //   };
    //   pc.createDataChannel('');
    //   pc.createOffer().then((sdp) => {
    //     sdp.sdp.split('\n').forEach(function (line) {
    //       if (line.indexOf('candidate') < 0) return;
    //       line.match(ipRegex).forEach(iterateIP);
    //     });
    //     pc.setLocalDescription(sdp, noop, noop);
    //   }).catch((reason) => {

    //   });
    //   pc.onicecandidate = (ice) => {
    //     if (!ice || !ice.candidate || !ice.candidate.candidate || !ice.candidate.candidate.match(ipRegex)) return;
    //     ice.candidate.candidate.match(ipRegex).forEach(iterateIP);
    //   };
    // }
  },
  components:{
    SelStatus,
    addStatus,
    upaStatus,
  }
}
</script>

<style>

</style>