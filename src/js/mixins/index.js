import Vue from 'vue'

// import mixins
import alert from './alert'
import errors from './errors'
import currentUser from './currentUser'

Vue.mixin(alert)
Vue.mixin(errors)
Vue.mixin(currentUser)
