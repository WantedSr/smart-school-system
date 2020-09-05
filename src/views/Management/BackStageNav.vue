<template>
  <div class="backstagenav clearfix">
    <div class="logo f_left">
      <h4><img src="~assets/images/school_logo.png" alt="no_logo"><span>智慧校园管理系统</span></h4>
    </div>
    <div class="time f_right hidden-xs-only">
      <h4>{{getTime}}</h4>
    </div>
  </div>
</template>

<script>
export default {
  data(){
    return {
    }
  },
  props:{
  },
  computed:{
    getTime(){
      let date = new Date();
      let y = date.getFullYear();
      let m = date.getMonth()+1;
      let d = date.getDate();
      return y+"年"+m+"月"+d+"日";
    },
  },
  methods: {
  },
}
</script>

<style>
  .backstagenav{
    width: 100%;
    height: 30px;
    background-color: #34495e;
    overflow: hidden;
    padding: 5px 0;
  }
  .logo h4{
    color: #FFF;
    font-size: 14px;
    font-weight: 400;
    margin-left: 20px;
    position: relative;
  }
  .logo>h4>img{
    position: absolute;
    height: 18px;
    top: 1px;
  }
  .logo>h4>span{
    margin-left: 24px;
  }
  .time{
    color: #FFF;
    margin-right: 20px;
    font-size: 14px;
    font-weight: 400;
  }
</style>