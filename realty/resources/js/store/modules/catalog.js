import AppService from '../../app.service'

export default {
  namespaced: true,

  state: {
    page: 1,
    items: []
  },

  mutations: {
    setPage (state, page) {
      state.page = page
    },

    setItems (state, items) {
      state.items = items
    }
  },

  actions: {
    async fetch ({ commit }) {
      const res = await AppService.getCatalog()
      commit('setItems', res.data.data)
    }
  }
}
