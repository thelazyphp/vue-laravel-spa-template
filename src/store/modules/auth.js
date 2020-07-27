import AppService from '../../app.service'

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
    setToken (state, token) {
      state.token = token
    }
  },

  actions: {
    async signIn ({ commit }, credentials) {
      const res = await AppService.signIn(credentials)
      commit('setToken', res.data.access_token)
    },

    async signOut ({ commit }) {
      await AppService.signOut()
      commit('setToken', null)
      commit('users/setCurrent', null, { root: true })
    }
  }
}
