import AppService from '../../app.service'

const findIndexById = (items, id) => items.findIndex(item => item.id == id)

export default {
  namespaced: true,

  state: {
    page: 1,
    items: [],
    current: null
  },

  mutations: {
    setPage (state, page) {
      state.page = page
    },

    setItems (state, items) {
      state.items = items
    },

    setCurrent (state, user) {
      state.current = user
    },

    removeItem (state, id) {
      const index = findIndexById(state.items, id)
      state.items.splice(index, 1)
    }
  },

  actions: {
    async fetch ({ commit }) {
      const res = await AppService.getUsers()
      commit('setItems', res.data.data)
    },

    async remove ({ commit }, id) {
      await AppService.removeUser(id)
      commit('removeItem', id)
    },

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
