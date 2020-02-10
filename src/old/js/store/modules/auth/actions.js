import Http from '../../../utils/Http'

export default {
    login ({ commit }, data) {
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)

            new Http().post('/auth/login', data)
                .then(response => {
                    commit('SET_AUTH_DATA', response.data)
                    return resolve(response)
                })
                .catch(error => reject(error))
                .finally(() => {
                    commit('SET_LOADING', false)
                })
        })
    },

    logout ({ commit }) {
        return new Promise((resolve, reject) => {
            new Http().post('/auth/logout')
                .then(response => {
                    commit('REMOVE_AUTH_DATA')
                    return resolve(response)
                })
                .catch(error => reject(error))
        })
    },

    refreshToken ({ state, commit }) {
        return new Promise((resolve, reject) => {
            const data = {
                refresh_token: state.refreshToken
            }

            new Http().post('/auth/refresh-token', data)
                .then(response => {
                    commit('SET_AUTH_DATA', response.data)
                    return resolve(response)
                })
                .catch(error => reject(error))
        })
    }
}
