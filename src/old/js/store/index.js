import Vue from 'vue'
import Vuex from 'vuex'

// import modules
import nca from './modules/nca'
import auth from './modules/auth'
import alert from './modules/alert'
import users from './modules/users'
import objects from './modules/objects'
import sources from './modules/sources'
import parsers from './modules/parsers'

Vue.use(Vuex)

export default new Vuex.Store({
    modules: {
        nca,
        auth,
        alert,
        users,
        objects,
        sources,
        parsers
    }
})
