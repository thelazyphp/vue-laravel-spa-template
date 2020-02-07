import request from '../utils/request'

export default {
    namespaced: true,

    state: {
        current: null
    },

    mutations: {
        setCurrent (state, user) {
            state.current = user
        }
    },

    actions: {
        fetchCurrent ({ commit }) {
            return new Promise((resolve, reject) => {
                request.get('/users/current')
                    .then(response => {
                        commit('setCurrent', response.data)
                        return resolve(response)
                    })
                    .catch(error => reject(error))
            })
        }
    }
}
