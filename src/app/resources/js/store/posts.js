import Vue from 'vue'

export default {
	namespaced: true,

	state: {
    loading: false,
    search: null,
    sort: null,
		page: 1,
    items: [],
    total: 0,
		lastPage: 1
	},

	getters: {
		queryParams (state) {
			let params = {
        page: state.page
      }

      if (state.sort) {
				params['sort'] = state.sort
      }

      if (state.search) {
				params['search'] = state.search
      }

			return params
		}
	},

	mutations: {
		setLoading (state, loading) {
			state.loading = loading
		},

		setSearch (state, search) {
			state.search = search
    },

    setSort (state, sort) {
			state.sort = sort
    },

		setPage (state, page) {
			state.page = page
		},

		incrementPage (state) {
			state.page++
		},

		setItems (state, items) {
			state.items = items
		},

		appendItems (state, items) {
			state.items = state.items.concat(items)
    },

    setTotal (state, total) {
			state.total = total
    },

    incrementTotal (state) {
			state.total++
		},

		setLastPage (state, lastPage) {
			state.lastPage = lastPage
		}
	},

	actions: {
		async fetch ({ getters, commit }) {
			commit('setLoading', true)

			try {
				const response = await Vue.Http.get('/posts', {
					params: getters.queryParams
				})

        commit('setItems', response.data.data)
        commit('setTotal', response.data.meta.total)
        commit('setLastPage', response.data.meta.last_page)

        return response
			} catch (error) {
        console.log(error)
				return error
			} finally {
				commit('setLoading', false)
			}
		},

		async fetchNext ({ getters, commit }) {
			commit('setLoading', true)
			commit('incrementPage')

			try {
				const response = await Vue.Http.get('/posts', {
					params: getters.queryParams
				})

        commit('appendItems', response.data.data)
        commit('setTotal', response.data.meta.total)
        commit('setLastPage', response.data.meta.last_page)

        return response
			} catch (error) {
        console.log(error)
				return error
			} finally {
				commit('setLoading', false)
			}
		}
	}
}
