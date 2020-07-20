import Vue from 'vue'
import Http from '../plugins/Http'

export default {
  namespaced: true,

  state: {
		loading: false,
    token: localStorage.getItem('token')
  },

  getters: {
    check (state) {
      return !!state.token
    }
  },

  mutations: {
    setLoading (state, loading) {
      state.loading = loading
    },

    removeToken (state) {
      state.token = null
      Http.removeToken()
      localStorage.removeItem('token')
    },

    setToken (state, token) {
      state.token = token
      Http.setToken(token)
      localStorage.setItem('token', token)
    }
  },

  actions: {
    async signIn ({ commit }, data) {
      commit('setLoading', true)

      try {
        const response = await Vue.Http.post('/auth/login', data)
        commit('setToken', response.data.access_token)
        return response
      } catch (error) {
        console.log(error)
        return error
      } finally {
        commit('setLoading', false)
      }
    },

    async signOut ({ commit }) {
      commit('setLoading', true)

      try {
        const response = await Vue.Http.post('/auth/logout')
        return response
      } catch (error) {
        console.log(error)
        return error
      } finally {
				commit('removeToken')
				commit('setLoading', false)
      }
    },

    async signUp ({ commit, dispatch }, data) {
      commit('setLoading', true)

      try {
				await Vue.Http.post('/auth/register', data)

        return await dispatch('signIn', {
          username: data.email,
          password: data.password
        })
      } catch (error) {
        console.log(error)
        return error
      } finally {
          commit('setLoading', false)
      }
    }
  }
}
