<template>
  <div class="subpage">
    <div class="pagehead">
      <h1>发起OA</h1>
    </div>
    <div class="oa clearfix">
      <ul>
        <li class="flex">
          <el-link :underline="false" href="#">
            <span class="icon el-icon-camera-solid"></span>
            <p>教学仪器维修</p>
          </el-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {

}
</script>

<style>
  .oa ul li{
    float: left;
    width: 100px;
    height: 100px;
    border: 1px dashed #ccc;
    text-align: center;
  }
  .oa>ul>li .icon{
    font-size: 30px;
  }
  .oa ul li p{
    font-size: 14px;
  }
</style>