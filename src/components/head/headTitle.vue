<!--
 * @Author: wanted
 * @Date: 2021-03-16 11:08:59
 * @LastEditTime: 2021-03-16 11:10:32
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \aqschool\src\components\head\headTitle.vue
-->
<template>
  <div class="pagehead">
    <h1>{{title}}</h1>
  </div>
</template>

<script>
export default {
  props:{
    title: {
      type: String,
      default: "Hello World"
    }
  }
}
</script>

<style>

</style>