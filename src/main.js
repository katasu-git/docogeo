// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import LongPress from 'vue-directive-long-press'
import VueLazyload from 'vue-lazyload'
 
Vue.use(VueLazyload, {
  preLoad: 1.3,
  error: 'https://dummyimage.com/130x120/ccc/999.png&text=Not+Found',
  loading: 'https://dummyimage.com/130x120/dcdcdc/999.png&text=Now loading',
  attempt: 1
})

Vue.config.productionTip = false
Vue.directive('long-press', LongPress)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>',
})
