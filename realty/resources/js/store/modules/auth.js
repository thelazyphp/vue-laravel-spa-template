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
      try {
        const res = await AppService.signIn(credentials)
        commit('setToken', res.data.access_token)
      } catch (error) {
        //
      }
    },

    async signOut ({ commit }) {
      try {
        await AppService.signOut()
        commit('setToken', null)
        commit('users/setCurrent', null, { root: true })
      } catch (error) {
        //
      }
    }
  }
}
