export default {
    SET_LOADING (state, loading) {
        state.loading = loading
    },

    SET_AUTH_DATA (state, data) {
        state.accessToken = data.access_token
        state.refreshToken = data.refresh_token
        localStorage.setItem('access_token', data.access_token)
        localStorage.setItem('refresh_token', data.refresh_token)
    },

    REMOVE_AUTH_DATA (state) {
        state.accessToken = state.refreshToken = ''
        localStorage.removeItem('access_token');
        localStorage.removeItem('refresh_token');
    }
}
