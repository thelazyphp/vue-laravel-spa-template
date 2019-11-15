import Vue from 'vue'
import Vuex from 'vuex'

// import modules
import alert from './modules/alert'
import users from './modules/users'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        alert,
        users
    }
})
