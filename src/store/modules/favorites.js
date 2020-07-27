import AppService from '../../app.service'

const getIndexById = (items, id) => items.findIndex(item => item.id == id)
const getItemyId = (items, id) => items.find(item => item.id == id)

export default {
	namespaced: true,

	state: {
    search: null,
    sort: '-published_at',
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

    favoriteItem(state, id) {
      const index = getIndexById(state.items, id)
      state.items[index].is_favorite = true
    },

    unfavoriteItem(state, id) {
      const index = getIndexById(state.items, id)
      state.items[index].is_favorite = false
    }
	},

	actions: {
		async fetch ({ getters, commit }) {
			const res = await AppService.getFavorites(getters.queryParams)
      commit('setItems', res.data.data)
      commit('setTotal', res.data.meta.total)
      commit('setLastPage', res.data.meta.last_page)
		},

    async fetchNext ({ getters, commit }) {
      commit('incrementPage')
      const res = await AppService.getFavorites(getters.queryParams)
      commit('addItems', res.data.data)
      commit('setTotal', res.data.meta.total)
      commit('setLastPage', res.data.meta.last_page)
    },

    async favorite ({ commit }, id) {
      await AppService.favoritePost(id)
      commit('favoriteItem', id)
      commit('incrementTotal')
    },

    async unfavorite ({ commit }, id) {
      await AppService.unfavoritePost(id)
      commit('unfavoriteItem', id)
      commit('decrementTotal')
    },

    async toggleFavorite ({ state, dispatch }, id) {
      const item = getItemyId(state.items, id)
      item.is_favorite ? await dispatch('unfavorite', id) : await dispatch('favorite', id)
    }
	}
}
