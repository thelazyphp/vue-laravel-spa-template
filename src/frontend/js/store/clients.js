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
        search: null,
        sort: null,
        page: 1,
        perPage: 25,
        isLoading: false,
        data: null,
    },

    getters: {
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
    },

    mutations: {
        setSearch (state, payload) {
            state.search = payload
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

        removeItem (state, payload) {
            state.data.data.splice(
                getItemIndexById(state.data.data, payload), 1
            )
        },
    },

    actions: {
        fetchData ({ state, commit }) {
            const endpoint = '/clients'

            let params = {}

            if (state.search !== null) {
                params['search'] = state.search
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
                        return resolve(response)
                    })
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },

        create ({ commit }, formData) {
            const endpoint = '/clients'

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.post(endpoint, formData)
                    .then(response => resolve(response))
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },

        delete ({ commit }, id) {
            const endpoint = `/clients/${id}`

            return new Promise((resolve, reject) => {
                commit('setIsLoading', true)

                Vue.Http.delete(endpoint)
                    .then(response => {
                        commit('removeItem', id)
                        return resolve(response)
                    })
                    .catch(error => reject(error))
                    .finally(() => commit('setIsLoading', false))
            })
        },
    },
}
