import 'bootstrap'
import Vue from 'vue'
import App from './App'
import store from './store'
import router from './router'
import AppService from './app.service'

Vue.config.productionTip = false
AppService.init()

new Vue({
  store,
  router,
  render: h => h(App)
}).$mount('#app')
