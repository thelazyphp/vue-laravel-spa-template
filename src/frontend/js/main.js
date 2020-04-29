import './bootstrap'
import Vue from 'vue'
import App from './App'
import store from './store'
import router from './router'
import Http from './plugins/Http'

Vue.config.productionTip = false

Vue.use(Http, { baseURL: process.env.MIX_API_URL })

if (store.state.auth.token) {
    Http.setAccessToken(store.state.auth.token)
}

Vue.component('VPagination', require('laravel-vue-pagination'))

new Vue({
    store,
    router,
    render: h => h(App),
}).$mount('#app')
