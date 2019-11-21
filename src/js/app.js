import Vue from 'vue'

import './mixins'
import './bootstrap'

import i18n from './lang'
import store from './store'
import router from './router'
import App from './components/App'

Vue.config.productionTip = false

new Vue({
    i18n,
    store,
    router,
    render: h => h(App)
}).$mount('#app')
