import router from '../router'
import * as AuthService from './auth.service'

export default class Http {
    constructor (config = { auth: false }) {
        this.instance = axios.create({
            baseURL: process.env.MIX_API_URL || 'http://localhost',

            headers: {
                'accept': 'application/json',
                'content-type': 'application/json'
            }
        })

        return this.init(config)
    }

    init (config = { auth: false }) {
        const auth = config && config.auth
            ? config.auth
            : false

        const onFulfilled = function (request) {
            if (auth) {
                request.headers['authorization'] = `Bearer ${AuthService.getAccessToken()}`
            }

            return request
        }

        const onRejected = function (error) {
            if (error.status == 401) {
                router.push({ name: 'login' })
            }

            return Promise.reject(error)
        }

        this.instance.interceptors.request.use(onFulfilled, onRejected)
        return this.instance
    }
}
