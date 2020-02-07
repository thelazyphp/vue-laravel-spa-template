import axios from 'axios'
import store from '../store'

const request = axios.create({
    baseURL: process.env.MIX_API_URL,

    headers: {
        'accept': 'application/json',
        'content-type': 'application/json'
    }
})

request.interceptors.request.use(request => {
    if (store.getters['auth/isAuth']) {
        request.headers.common['authorization'] = `Bearer ${store.state.auth.token}`
    }

    return request
}, error => Promise.reject(error))

export default request
