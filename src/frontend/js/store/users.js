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
            const endpoint = '/users/current'

            return new Promise((resolve, reject) => {
                Vue.Http.get(endpoint)
                    .then(response => {
                        commit('setCurrent', response.data)
                        return resolve(response)
                    })
                    .catch(error => reject(error))
            })
        }
    }
}
