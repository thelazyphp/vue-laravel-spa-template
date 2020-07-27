import AppService from '../../app.service'

export default {
  namespaced: true,

  state: {
    items: []
  },

  getters: {
    queryParam (state, getters) {
      return getters.text.join(',')
    },

    text (state) {
      return state.items.map(item => item.text)
    }
  },

  mutations: {
    setItems (state, items) {
      state.items = items
    },

    addItem (state, item) {
      state.items.push(item)
    },

    removeItem (state, index) {
      state.items.splice(index, 1)
    }
  },

  actions: {
    async fetch ({ commit }) {
      const res = await AppService.getTags()
      commit('setItems', res.data)
    },

    async remove({ state, commit }, index) {
      const id = state.items[index].id
      await AppService.removeTag(id)
      commit('removeItem', index)
    },

    async create ({ commit }, text) {
      const res = await AppService.createTag(text)
      commit('addItem', res.data)
    }
  }
}
