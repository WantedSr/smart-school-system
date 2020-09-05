import Vue from 'vue'
import App from './App.vue'

import router from './router'
// import Router from 'vue-router'
// const routerPush = Router.prototype.push
// Router.prototype.push = function push(location) {
//   return routerPush.call(this, location).catch(error=> error)
// }
import store from './store'
// import $ from "jquery"

// 引入 ElementUI
import ElementUI from 'element-ui'
// import 'element-ui/lib/theme-chalk/index.css'
// import 'element-ui/lib/theme-chalk/display.css';
Vue.use(ElementUI);

// 引入echarts
import echarts from 'echarts';
Vue.prototype.echarts = echarts;
Vue.use(echarts);

// import XLSX from "xlsx";
// Vue.use(XLSX);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
