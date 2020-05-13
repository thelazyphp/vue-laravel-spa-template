import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth.js'
import users from './users.js'
import catalog from './catalog.js'
import clients from './clients.js'
import compilations from './compilations.js'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        auth,
        users,
        catalog,
        clients,
        compilations,
    },
})
