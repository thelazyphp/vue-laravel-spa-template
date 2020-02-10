import store from '../store'

export default class Http {
    constructor () {
        this.instance = axios.create({
            baseURL: process.env.MIX_API_URL || 'http://localhost/api',

            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            }
        })

        return this.init()
    }

    init () {
        const onFulfilled = function (request) {
            if (store.getters['auth/check']) {
                request.headers.common[
                    'authorization'
                ] = `Bearer ${store.state.auth.accessToken}`
            }

            return request
        }

        const onRejected = function (error) {
            return Promise.reject(error)
        }

        this.instance.interceptors.request.use(onFulfilled, onRejected)
        return this.instance
    }
}
