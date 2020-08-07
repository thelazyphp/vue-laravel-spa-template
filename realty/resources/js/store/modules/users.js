import AppService from '../../app.service'

const findIndexById = (items, id) => items.findIndex(item => item.id == id)

export default {
  namespaced: true,

  state: {
    current: null,
    page: 1,
    items: [],
    lastPage: null
  },

  mutations: {
    setCurrent (state, user) {
      state.current = user
    },

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
    },

    removeItem (state, id) {
      const index = findIndexById(state.items, id)
      state.items.splice(index, 1)
    }
  },

  actions: {
    async fetch ({ state, commit }) {
      let params = {
        page: state.page
      }

      try {
        const res = await AppService.getUsers(params)
        commit('setItems', res.data.data)
        commit('setLastPage', res.data.meta.last_page)
      } catch (error) {
        //
      }
    },

    async fetchNext ({ commit, dispatch }) {
      commit('incrementPage')
      await dispatch('fetch')
    },

    async fetchPrev ({ commit, dispatch }) {
      commit('decrementPage')
      await dispatch('fetch')
    },

    async remove ({ commit }, id) {
      try {
        await AppService.removeUser(id)
        commit('removeItem', id)
      } catch (error) {
        //
      }
    },

    async fetchCurrent ({ commit }) {
      try {
        const res = await AppService.getCurrentUser()
        commit('setCurrent', res.data)
      } catch (error) {
        //
      }
    },

    async removeCurrent ({ commit }) {
      try {
        await AppService.removeCurrentUser()
        commit('setCurrent', null)
      } catch (error) {
        //
      }
    },

    async updateCurrent ({ commit }, user) {
      try {
        const res = await AppService.updateCurrentUser(user)
        commit('setCurrent', res.data)
      } catch (error) {
        //
      }
    }
  }
}
