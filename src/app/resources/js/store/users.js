import Vue from 'vue'

export default {
  namespaced: true,

  state: {
    loading: false,
    current: null
  },

  mutations: {
    setLoading (state, loading) {
			state.loading = loading
    },

    setCurrent (state, user) {
      state.current = user
    }
  },

  actions: {
    async fetchCurrent ({ commit }) {
      commit('setLoading', true)

      try {
        const response = await Vue.Http.get('/users/current')
        commit('setCurrent', response.data)
        return response
      } catch (error) {
        console.log(error)
        return error
      } finally {
        commit('setLoading', false)
      }
    },

    async removeCurrent ({ commit }) {
      try {
        const response = await Vue.Http.delete('/users/current')
        commit('setCurrent', null)
        return response
      } catch (error) {
        console.log(error)
        return error
      }
    },

    async updateCurrent ({ commit }, data) {
      commit('setLoading', true)

      try {
        const response = await Vue.Http.put('/users/current', data)
        commit('setCurrent', response.data)
        return response
      } catch (error) {
        console.log(error)
        return error
      } finally {
        commit('setLoading', false)
      }
    }
  }
}
