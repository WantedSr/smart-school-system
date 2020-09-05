const webpack = require("webpack");

module.exports = {
  configureWebpack: {
    resolve:{
      // 设置文件尾缀省略
      extensions: [],
      // 设置别名
      alias: {
        // "@": "src",    // 默认自带的
        "assets": "@/assets",
        "common": "@/common",
        "components": "@/components",
        "network": "@/network",
        "views": "@/views",
        "plugin": "@/plugin",
      },
    },
    externals: { 
      vue: "Vue",       // 通过 cdn 引入 vue
      vuex: "Vuex",     // 通过 cdn 引入 vuex
      'vue-router': 'VueRouter',    // 通过 cdn 引入 vue-router
      'jquery': '$',    // 通过 cdn 引入 jquery 
      'echarts': 'echarts', // 通过 cdn 引入 echarts
      'element-ui': 'ELEMENT',   // 通过 cdn 引入 element-ui
      // "particles.js": "particles.js",   // 通过 cdn 引入 particles.js
      // 'xlsx': 'xlsx',
    },
  },
}