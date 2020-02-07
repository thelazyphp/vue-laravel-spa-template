export default {
    namespaced: true,

    state: {
        show: false,
        type: null,
        message: null
    },

    mutations: {
        show (state, payload) {
            state.show = true
            state.type = payload.type
            state.message = payload.message
        },

        close (state) {
            state.show = false
        }
    }
}
