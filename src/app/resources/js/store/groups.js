import Vue from 'vue'

const getIndexById = (items, id) => items.findIndex(item => item.id == id)

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

    updateItem (state, { id, item }) {
      const index = getIndexById(state.items, id)
      state.items[index] = item
    },

    removeItem (state, id) {
      const index = getIndexById(state.items, id)
      state.items.splice(index, 1)
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
				const response = await Vue.Http.get('/groups', {
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
				const response = await Vue.Http.get('/groups', {
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
  },
  
  async create ({ commit }, data) {
    commit('setLoading', true)

    try {
      const response = await Vue.Http.post('/groups', data)
      commit('appendItems', response.data)
      return response
    } catch (error) {
      console.log(error)
      return error
    } finally {
      commit('setLoading', false)
    }
  },

  async update ({ commit }, { id, data }) {
    commit('setLoading', true)

    try {
      const response = await Vue.Http.put(`/groups/${id}`, data)

      commit('updateItem', {
        id,
        item: response.data
      })

      return response
    } catch (error) {
      console.log(error)
      return error
    } finally {
      commit('setLoading', false)
    }
  },

  async remove ({ commit }, id) {
    try {
      const response = await Vue.Http.delete(`/groups/${id}`)
      commit('removeItem', id)
      return response
    } catch (error) {
      console.log(error)
      return error
    } finally {
      commit('setLoading', false)
    }
  }
}
