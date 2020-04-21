import axios from 'axios'

const serializeParams = (params, prefix = null) => {
    let serializedParams = []

    Object.keys(params).forEach(key => {
        if (
            params[key] !== null
            && params[key] !== undefined
        ) {
            const serializedKey = prefix ? `${prefix}[${key}]` : key

            if (Array.isArray(params[key])) {
                params[key].forEach(value => {
                    if (
                        value !== null
                        && value !== undefined
                    ) {
                        value = encodeURIComponent(value)

                        serializedParams.push(
                            `${serializedKey}[]=${value}`
                        )
                    }
                })
            } else if (typeof params[key] == 'object') {
                serializedParams.push(
                    serializeParams(
                        params[key], serializedKey
                    )
                )
            } else {
                serializedParams.push(
                    `${serializedKey}=${encodeURIComponent(params[key])}`
                )
            }
        }
    })

    return serializedParams.join('&')
}

const Http = {
    install (Vue, options) {
        const instance = axios.create({
            baseURL: options.baseURL,

            paramsSerializer: (params) => {
                return serializeParams(params)
            },

            headers: {
                'accept': 'application/json',
                'content-type': 'application/json',
            }
        })

        instance.interceptors.request.use(request => {
            if (
                options.auth.type == 'Bearer'
                && options.auth.token
            ) {
                request.headers.common['authorization'] = `Bearer ${options.auth.token}`
            }

            return request
        }, error => Promise.reject(error))

        Vue.Http = instance
        Vue.prototype.$http = instance
    }
}

export default Http

export {
    serializeParams,
}
