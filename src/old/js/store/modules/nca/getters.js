export default {
    getUploadById (state) {
        return id => {
            return state.uploads.find(upload => upload.id == id)
        }
    }
}
