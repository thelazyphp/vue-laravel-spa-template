import AppService from '../../app.service'

const getIndexById = (items, id) => items.findIndex(item => item.id == id)

export default {
	namespaced: true,

	state: {
    search: null,
    sort: '-updated_at',
		page: 1,
    items: [],
    total: null,
    lastPage: null
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

    addItem (state, item) {
			state.items.push(item)
    },

		addItems (state, items) {
			state.items = state.items.concat(items)
    },

    setTotal (state, total) {
			state.total = total
    },

    incrementTotal (state) {
			state.total++
    },

    decrementTotal (state) {
			state.total--
		},

		setLastPage (state, lastPage) {
			state.lastPage = lastPage
    },

    removeItem (state, id) {
      const index = getIndexById(state.items, id)
      state.items.splice(index, 1)
    },

    updateItem (state, { id, item }) {
      const index = getIndexById(state.items, id)
      state.items[index] = item
    }
	},

	actions: {
    async create ({ commit }, group) {
      const res = await AppService.createGroup(group)
      commit('addItem', res.data)
      commit('incrementTotal')
    },

    async remove ({ commit }, id) {
      await AppService.removeGroup(id)
      commit('removeItem', id)
      commit('decrementTotal')
    },

    async update ({ commit }, { id, group }) {
      const res = await AppService.updateGroup(id, group)

      commit('updateItem', {
        id,
        item: res.data
      })
    },

    async fetch ({ getters, commit }) {
			const res = await AppService.getGroups(getters.queryParams)
      commit('setItems', res.data.data)
      commit('setTotal', res.data.meta.total)
      commit('setLastPage', res.data.meta.last_page)
		},

		async fetchNext ({ getters, commit }) {
      commit('incrementPage')
      const res = await AppService.getGroups(getters.queryParams)
      commit('addItems', res.data.data)
      commit('setTotal', res.data.meta.total)
      commit('setLastPage', res.data.meta.last_page)
    }
  }
}
