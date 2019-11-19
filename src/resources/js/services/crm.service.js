import Http from './Http'

export function update (file, onUploadProgress) {
    return new Promise((resolve, reject) => {
        const data = new FormData()
        data.append('file', file)

        const config = {
            headers: {
                'content-type': 'multipart/form-data'
            },
            onUploadProgress
        }

        new Http({ auth: true }).post('/crm/update', data, config)
            .then(response => resolve(response))
            .catch(error => reject(error))
    })
}
