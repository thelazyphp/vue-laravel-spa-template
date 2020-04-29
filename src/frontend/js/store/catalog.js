import Vue from 'vue'

export default {
    namespaced: true,

    state: {
        table: 'db',
        search: null,
        filter: null,
        sort: null,
        page: 1,
        perPage: 25,
        isLoading: false,
        data: null,
        sources: null,
        total: null,
        favoritedTotal: null,
        filterProps: null,
        filterOptions: null,
    },

    getters: {
        getItemById (state) {
            return id => {
                return state.data.data.find(item => item.id == id)
            }
        },
    },

    mutations: {
        setTable (state, payload) {
            state.table = payload
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

        setSources (state, payload) {
            state.sources = payload
        },

        setTotal (state, payload) {
            state.total = payload
        },

        setFavoritedTotal (state, payload) {
            state.favoritedTotal = payload
        },

        incrementFavoritedTotal (state) {
            state.favoritedTotal++
        },

        decrementFavoritedTotal (state) {
            state.favoritedTotal--
        },

        setFilterProps (state, payload) {
            state.filterProps = payload
        },

        setFilterOptions (state, payload) {
            state.filterOptions = payload
        },
    },

    actions: {
        fetchData ({ state, commit }, { category, fetchFavorited }) {
            let endpoint = `/catalog/${category}`

            if (fetchFavorited) {
                endpoint += '/favorited'
            }

            const params = {
                search: state.search,
                filter: state.filter,
                sort: state.sort,
                page: state.page,
                per_page: state.perPage,
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

        favorite ({ getters, commit }, { category, id }) {
            const item = getters.getItemById(id)
            const endpoint = `/catalog/${category}/${id}/favorite`

            return new Promise((resolve, reject) => {
                Vue.Http.post(endpoint)
                    .then(response => {
                        commit('incrementFavoritedTotal')
                        item.is_favorited = true
                        return resolve(response)
                    })
                    .catch(error => reject(error))
            })
        },

        unfavorite ({ getters, commit }, { category, id }) {
            const item = getters.getItemById(id)
            const endpoint = `/catalog/${category}/${id}/unfavorite`

            return new Promise((resolve, reject) => {
                Vue.Http.post(endpoint)
                    .then(response => {
                        commit('decrementFavoritedTotal')
                        item.is_favorited = false
                        return resolve(response)
                    })
                    .catch(error => reject(error))
            })
        },

        toggleFavorited ({ getters, commit }, { category, id }) {
            const item = getters.getItemById(id)
            const endpoint = `/catalog/${category}/${id}/toggle-favorited`

            return new Promise((resolve, reject) => {
                Vue.Http.post(endpoint)
                    .then(response => {
                        if (item.is_favorited) {
                            commit('decrementFavoritedTotal')
                            item.is_favorited = false
                        } else {
                            commit('incrementFavoritedTotal')
                            item.is_favorited = true
                        }

                        return resolve(response)
                    })
                    .catch(error => reject(error))
            })
        },
    },
}
