import Http from '../../../utils/Http'

export default {
    loadItem ({ commit }, id) {
        return new Promise((resolve, reject) => {
            new Http().get(`/parsers/${id}`)
                .then(response => {
                    commit('SET_ITEM', response.data)
                    return resolve(response)
                })
                .catch(error => reject(error))
        })
    },

    loadItems ({ commit }) {
        return new Promise((resolve, reject) => {
            commit('SET_LOADING', true)

            new Http().get('/parsers')
                .then(response => {
                    commit('SET_LOADING', false)
                    commit('SET_ITEMS', response.data)
                    return resolve(response)
                })
                .catch(error => reject(error))
        })
    },

    removeItem ({ commit }, id) {
        return new Promise((resolve, reject) => {
            new Http().delete(`/parsers/${id}`)
                .then(response => {
                    commit('REMOVE_ITEM', id)
                    return resolve(response)
                })
                .catch(error => reject(error))
        })
    }
}
