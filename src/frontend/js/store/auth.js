import Vue from 'vue'
import Http from '../plugins/Http'

export default {
    namespaced: true,

    state: {
        isLoading: false,
        token: localStorage.getItem('token'),
    },

    getters: {
        isAuth (state) {
            return !!state.token
        },
    },

    mutations: {
        setIsLoading (state, payload) {
            state.isLoading = payload
        },

        removeToken (state) {
            state.token = null
            Http.forgetAccessToken()
            localStorage.removeItem('token')
        },

        setToken (state, payload) {
            state.token = payload
            Http.setAccessToken(payload)
            localStorage.setItem('token', payload)
        },
    },

    actions: {
        signIn ({ commit }, formData) {
            const endpoint = '/auth/login'

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.post(endpoint, formData)
                    .then(response => {
                        commit('setToken', response.data.access_token)
                        return resolve(response)
                    })
                    .catch(error => {
                        commit('removeToken')
                        commit('users/setCurrent', null, { root: true })
                        return reject(error)
                    })
                    .finally(() => commit('setIsLoading', false))
            })
        },

        signOut ({ commit }) {
            const endpoint = '/auth/logout'

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.post(endpoint)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
                    .finally(() => {
                        commit('setIsLoading', false)
                        commit('removeToken')
                        commit('users/setCurrent', null, { root: true })
                    })
            })
        },
    },
}
