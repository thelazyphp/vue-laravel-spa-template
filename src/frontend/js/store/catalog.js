import Vue from 'vue'

const getItemById = (items, id) => {
    return items.find(item => item.id == id)
}

const getItemIndexById = (items, id) => {
    return items.findIndex(item => item.id == id)
}

export default {
    namespaced: true,

    state: {
        category: 'apartments',
        search: null,
        filter: null,
        sort: null,
        page: 1,
        perPage: 25,
        isLoading: false,
        data: null,
    },

    getters: {
        isFilterClear (state) {
            return state.filter === null
        },

        getItems (state) {
            return state.data ? state.data.data : []
        },

        getItemById (state) {
            return id => {
                return getItemById(state.data.data, id)
            }
        },

        getItemIndexById (state) {
            return id => {
                return getItemIndexById(state.data.data, id)
            }
        },

        getFilterOptions (state) {
            return state.data ? state.data.meta.filter_options : null
        },
    },

    mutations: {
        setCategory (state, payload) {
            state.category = payload
        },

        setSearch (state, payload) {
            state.search = payload
        },

        setFilter (state, payload) {
            state.filter = payload
        },

        setSort (state, payload) {
            state.sort = payload
        },

        setPage (state, payload) {
            state.page = payload
        },

        setPerPage (state, payload) {
            state.perPage = payload
        },

        setIsLoading (state, payload) {
            state.isLoading = payload
        },

        setData (state, payload) {
            state.data = payload
        },
    },

    actions: {
        filterItems ({ commit, dispatch }) {
            commit('setPage', 1)
            return dispatch('fetchData')
        },

        fetchData ({ state, commit }) {
            let endpoint = '/catalog'

            let params = {}

            params['category'] = state.category

            if (state.search !== null) {
                params['search'] = state.search
            }

            if (state.filter !== null) {
                params['filter'] = state.filter
            }

            if (state.sort !== null) {
                params['sort'] = state.sort
            }

            if (state.page !== null) {
                params['page'] = state.page
            }

            if (state.perPage !== null) {
                params['per_page'] = state.perPage
            }

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.get(endpoint, { params })
                    .then(response => {
                        commit('setData', response.data)
                        window.scrollTo({ top: 0 })
                        return resolve(response)
                    })
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },
    },
}
