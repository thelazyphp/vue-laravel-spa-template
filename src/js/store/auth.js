import Vue from 'vue'

export default {
    namespaced: true,

    state: {
        token: localStorage.getItem('token')
    },

    getters: {
        isAuth (state) {
            return !!state.token
        }
    },

    mutations: {
        setToken (state, payload) {
            state.token = payload
            localStorage.setItem('token', payload)
        },

        removeToken (state) {
            state.token = null
            localStorage.removeItem('token')
        }
    },

    actions: {
        signIn ({ commit }, formData) {
            const endpoint = '/auth/login'

            return new Promise((resolve, reject) => {
                Vue.Http.post(endpoint, formData)
                    .then(response => {
                        commit('setToken', response.data.access_token)
                        return resolve(response)
                    })
                    .catch(error => {
                        commit('removeToken')
                        return reject(error)
                    })
            })
        },

        signOut ({ commit }) {
            const endpoint = '/auth/logout'

            return new Promise((resolve, reject) => {
                Vue.Http.post(endpoint)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
                    .finally(() => {
                        commit('removeToken')
                        commit('users/setCurrent', null, { root: true })
                    })
            })
        }
    }
}
