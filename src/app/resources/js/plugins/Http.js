import Vue from 'vue'
import axios from 'axios'

export default {
  install (Vue, options) {
    Vue.Http = Vue.prototype.$http = axios.create({
      baseURL: options.baseURL,

      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
  },

  removeToken () {
    delete Vue.Http.defaults.headers.common['Authorization']
  },

  setToken (token) {
    Vue.Http.defaults.headers.common['Authorization'] = `Bearer ${token}`
  }
}
