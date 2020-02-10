import Http from '../../../utils/Http'

export default {
    loadItems ({ state, commit }) {
        return new Promise((resolve, reject) => {
            let query = []

            if (state.sort) {
                query.push('sort=' + state.sort)
            }

            if (state.search) {
                query.push('search=' + state.search)
            }

            Object.keys(state.filter).forEach(key => {
                query.push(`filter[${key}]=${state.filter[key]}`)
            })

            query = query.join('&')

            if (query) {
                query = '?' + query
            }

            commit('SET_LOADING', true)

            new Http().get(`/${state.itemsType}`)
                .then(response => {
                    commit('SET_ITEMS', response.data.data)
                    return resolve(response)
                })
                .catch(error => reject(error))
                .finally(() => {
                    commit('SET_LOADING', false)
                })
        })
    }
}
