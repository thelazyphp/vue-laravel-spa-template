export default {
    SET_ITEMS_TYPE (state, type) {
        state.itemsType = type
    },

    SET_SEARCH (state, search) {
        state.search = search
    },

    SET_FILTER (state, filter) {
        state.filter = filter
    },

    SET_SORT (state, sort) {
        state.sort = sort
    },

    SET_LOADING (state, loading) {
        state.loading = loading
    },

    SET_ITEMS (state, items) {
        state.items = items
    }
}
