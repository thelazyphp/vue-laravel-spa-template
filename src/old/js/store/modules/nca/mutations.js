export default {
    ADD_UPLOAD (state, { id, type, file }) {
        state.uploads.push({
            id,
            type,
            file,
            error: null,
            started: false,
            progress: 0
        })
    },

    REMOVE_UPLOAD (state, id) {
        const index = state.uploads.findIndex(upload => upload.id == id)
        state.uploads.splice(index, 1)
    },

    REMOVE_ALL_UPLOADS (state) {
        state.uploads = []
    },

    SET_UPLOAD_ERROR (state, { id, error }) {
        const index = state.uploads.findIndex(upload => upload.id == id)
        state.uploads[index].error = error
    },

    SET_UPLOAD_PROGRESS (state, { id, progress }) {
        const index = state.uploads.findIndex(upload => upload.id == id)

        if (state.uploads[index].started) {
            state.uploads[index].started = true
        }

        state.uploads[index].progress = progress
    }
}
