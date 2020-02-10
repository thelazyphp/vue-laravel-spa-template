import Http from '../../../utils/Http'

export default {
    upload ({ getters, commit }, id) {
        const upload = getters.getUploadById(id)

        const data = new FormData()
        data.append('file', upload.file)

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            },

            onUploadProgress (event) {
                if (event.lengthComputable) {
                    commit('SET_UPLOAD_PROGRESS', {
                        id,
                        progress: Math.round((event.loaded / event.total) * 100)
                    })
                }
            }
        }

        return new Promise((resolve, reject) => {
            new Http().post(`/nca/${upload.type}`, data, config)
                .then(response => {
                    commit('REMOVE_UPLOAD', id)
                    return resolve(response)
                })
                .catch(error => {
                    commit('SET_UPLOAD_ERROR', {
                        id,
                        error
                    })

                    return reject(error)
                })
        })
    },

    uploadAll ({ state, dispatch }) {
        state.uploads.forEach(file => {
            dispatch('upload', file.id)
        })
    }
}
