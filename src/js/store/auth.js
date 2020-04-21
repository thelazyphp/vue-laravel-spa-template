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
            return new Promise((resolve, reject) => {
                Vue.Http.post('/auth/login', formData)
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
            return new Promise((resolve, reject) => {
                Vue.Http.post('/auth/logout')
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
