import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth.js'
import users from './users.js'
import alert from './alert.js'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        auth,
        users,
        alert
    }
})
