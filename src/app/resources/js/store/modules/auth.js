import Vue from 'vue'

export default {
  namespaced: true,

  state: {
    token: localStorage.getItem('token')
  },

  getters: {
    check (state) {
      return !!state.token
    }
  },

  mutations: {
    removeToken (state) {
      state.token = null
      Vue.Http.removeToken()
      localStorage.removeItem('token')
    },

    setToken (state, token) {
      state.token = token
      Vue.Http.setToken(token)
      localStorage.setItem('token', token)
    }
  },

  actions: {
    async signIn ({ commit }, data) {
      try {
        const res = await Vue.Http.post('/auth/login', data)
        commit('setToken', res.data.access_token)
      } catch (error) {
        //

        console.log(error)
      }
    },

    async signOut ({ commit }) {
      try {
        await Vue.Http.post('/auth/logout')
        commit('removeToken')
      } catch (error) {
        //

        console.log(error)
      }
    },

    async signUp ({ commit }, data) {
      commit('setLoading', true)

      try {
				await Vue.Http.post('/auth/register', data)
      } catch (error) {
        //

        console.log(error)
      }
    }
  }
}
