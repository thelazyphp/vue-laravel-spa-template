import AppService from '../../app.service'

export default {
  namespaced: true,

  state: {
    current: null
  },

  mutations: {
    setCurrent (state, user) {
      state.current = user
    }
  },

  actions: {
    async fetchCurrent ({ commit }) {
      const res = await AppService.getCurrentUser()
      commit('setCurrent', res.data)
    },

    async removeCurrent ({ commit }) {
      await AppService.removeCurrentUser()
      commit('setCurrent', null)
    },

    async updateCurrent ({ commit }, user) {
      const res = await AppService.updateCurrentUser(user)
      commit('setCurrent', res.data)
    }
  }
}
