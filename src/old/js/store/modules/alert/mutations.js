export default {
    SHOW (state, { type, message }) {
        state.show = true
        state.type = type
        state.message = message
    },

    CLOSE (state) {
        state.show = false
        state.type = undefined
        state.message = ''
    }
}
