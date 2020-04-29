import Vue from 'vue'
import axios from 'axios'

const serializeParams = (params, prefix = null) => {
    const __ = encodeURIComponent
    let res = []

    Object.keys(params).forEach(key => {
        if (
            params[key] !== undefined
            && params[key] !== null
        ) {
            const resKey = prefix ? `${__(prefix)}[${__(key)}]` : __(key)

            if (Array.isArray(params[key])) {
                params[key].forEach(value => {
                    const resValue = __(value)

                    res.push(
                        `${resKey}[]=${resValue}`
                    )
                })
            } else if (typeof params[key] == 'object') {
                const resValue = serializeParams(
                    params[key], resKey
                )

                if (resValue) {
                    res.push(resValue)
                }
            } else {
                const resValue = __(params[key])

                res.push(
                    `${resKey}=${resValue}`
                )
            }
        }
    })

    return res.join('&')
}

const Http = {
    install (Vue, options) {
        const instance = axios.create({
            baseURL: options.baseURL,

            headers: {
                'accept': 'application/json',
                'content-type': 'application/json',
            },

            paramsSerializer: (params) => serializeParams(params)
        })

        Vue.Http = instance
        Vue.prototype.$http = instance
    },

    forgetAccessToken () {
        Vue.Http.defaults.headers.common['authorization'] = null
    },

    setAccessToken (token) {
        Vue.Http.defaults.headers.common['authorization'] = `Bearer ${token}`
    },
}

export default Http

export {
    serializeParams,
}
