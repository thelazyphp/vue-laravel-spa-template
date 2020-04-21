import Vue from 'vue'

export default {
    namespaced: true,

    state: {
        current: null
    },

    mutations: {
        setCurrent (state, payload) {
            state.current = payload
        }
    },

    actions: {
        fetchCurrent ({ commit }) {
            return new Promise((resolve, reject) => {
                Vue.Http.get('/users/current')
                    .then(response => {
                        commit('setCurrent', response.data)
                        return resolve(response)
                    })
                    .catch((error) => reject(error))
            })
        }
    }
}
