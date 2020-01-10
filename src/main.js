// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import router from './router'
import LongPress from 'vue-directive-long-press'
import { VLazyImagePlugin } from "v-lazy-image";
import VueLocalStorage from 'vue-localstorage'
import 'leaflet/dist/leaflet.css'

Vue.use(VLazyImagePlugin);
Vue.config.productionTip = false
Vue.directive('long-press', LongPress)
Vue.use(VueLocalStorage)

/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  components: { App },
  template: '<App/>',
  localStorage: {
    stringKey: {
      type: String
    },
    numberKey: {
      type: Number
    },
    booleanKey: {
      type: Boolean,
      default: true
    },
    listKey: {
      type: Array
    },
    objectKey: {
      type: Object,
      default: {
        val: 'default'
      }
    },
    noTypeKey: ''
  }
})
