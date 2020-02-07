import request from '../utils/request'

export default {
    namespaced: true,

    state: {
        token: localStorage.getItem('token')
    },

    getters: {
        isAuth (state) {
            return !! state.token
        }
    },

    mutations: {
        setToken (state, token) {
            state.token = token
            localStorage.setItem('token', token)
        },

        removeToken (state) {
            state.token = null
            localStorage.removeItem('token')
        }
    },

    actions: {
        signIn ({ commit }, formData) {
            return new Promise((resolve, reject) => {
                request.post('/auth/login', formData)
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
                request.post('/auth/logout')
                    .then(response => resolve(response))
                    .catch(error => reject(error))
                    .finally(() => commit('removeToken'))
            })
        }
    }
}
