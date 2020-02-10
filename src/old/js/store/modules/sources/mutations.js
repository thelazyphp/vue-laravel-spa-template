export default {
    SET_LOADING (state, loading) {
        state.loading = loading
    },

    SET_ITEMS (state, items) {
        state.items = items
    },

    SET_ITEM (state, item) {
        state.item = item
    },

    REMOVE_ITEM (state, id) {
        const index = state.items.findIndex(item => item.id == id)
        state.items.splice(index, 1)
    }
}
