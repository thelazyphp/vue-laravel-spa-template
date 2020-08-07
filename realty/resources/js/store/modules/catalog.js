import AppService from '../../app.service'

export default {
  namespaced: true,

  state: {
    page: 1,
    items: [],
    lastPage: null
  },

  mutations: {
    setPage (state, page) {
      state.page = page
    },

    incrementPage (state) {
      state.page++
    },

    decrementPage (state) {
      state.page--
    },

    setItems (state, items) {
      state.items = items
    },

    setLastPage (state, page) {
      state.lastPage = page
    }
  },

  actions: {
    async fetchNext ({ commit, dispatch }) {
      commit('incrementPage')
      await dispatch('fetch')
    },

    async fetchPrev ({ commit, dispatch }) {
      commit('decrementPage')
      await dispatch('fetch')
    },

    async fetch ({ state, commit }) {
      let params = {
        page: state.page
      }

      try {
        const res = await AppService.getCatalog(params)
        commit('setItems', res.data.data)
        commit('setLastPage', res.data.meta.last_page)
      } catch (error) {
        //
      }
    }
  }
}
