import Vue from 'vue'

export default {
    namespaced: true,

    state: {
        loading: false,
        data: null,
        sort: null,
        page: 1,
        perPage: 25
    },

    getters: {
        getItemById (state) {
            return id => {
                return state.data.data.find(item => item.id == id)
            }
        }
    },

    mutations: {
        setLoading (state, payload) {
            state.loading = payload
        },

        setData (state, payload) {
            state.data = payload
        },

        setSort (state, payload) {
            state.sort = payload
        },

        setPage (state, payload) {
            state.page = payload
        },

        setPerPage (state, payload) {
            state.perPage = payload
        }
    },

    actions: {
        fetchData ({ state, commit }, category) {
            const endpoint = `/catalog/${category}`

            const params = {
                sort: state.sort,
                page: state.page,
                per_page: state.perPage
            }

            return new Promise((resolve, reject) => {
                commit('setLoading', true)

                Vue.Http.get(endpoint, { params })
                    .then(response => {
                        commit('setData', response.data)
                        return resolve(response)
                    })
                    .catch(error => reject(error))
                    .finally(() => commit('setLoading', false))
            })
        },

        toggleFavorited ({ getters }, { category, id }) {
            const item = getters.getItemById(id)
            const endpoint = `/catalog/${category}/${id}/toggle-favorited`

            return new Promise((resolve, reject) => {
                Vue.Http.post(endpoint)
                    .then(response => {
                        item.is_favorited = !item.is_favorited
                        return resolve(response)
                    })
                    .catch(error => reject(error))
            })
        }
    }
}
