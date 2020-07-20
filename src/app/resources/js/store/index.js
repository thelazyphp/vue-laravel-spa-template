import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'
import users from './users'
import posts from './posts'
import groups from './groups'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    auth,
    users,
    posts,
    groups
  }
})
