import Vue from 'vue'
import store from './store'
import Http from './plugins/Http'

export default {
  init () {
    Vue.use(Http, {
      baseURL: 'http://localhost/realty/api/v1'
    })

    if (!!store.state.auth.token) {
      Vue.Http.setToken(store.state.auth.token)
    }
  },

  /**
   * @param {Object} credentials
   * @returns {Object}
   */
  async signIn (credentials) {
    try {
      const res = await Vue.Http.post('/auth/login', credentials)
      Vue.Http.setToken(res.data.access_token)
      localStorage.setItem('token', res.data.access_token)

      return res
    } catch (error) {
      //

      console.log(error)
    }
  },

  async signOut () {
    try {
      await Vue.Http.post('/auth/logout')
      Vue.Http.removeToken()
      localStorage.removeItem('token')
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object} user
   * @returns {Object}
   */
  async signUp (user) {
    try {
      return await Vue.Http.post('/auth/register', user)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {string} refreshToken
   * @returns {Object}
   */
  async refreshToken (refreshToken) {
    try {
      return await Vue.Http.post('/auth/refresh-token', {
        refresh_token: refreshToken
      })
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @returns {Object}
   */
  async getCurrentUser () {
    return await this.getUser('self')
  },

  async removeCurrentUser () {
    return await this.removeUser('self')
  },

  /**
   * @param {Object} user
   * @returns {Object}
   */
  async updateCurrentUser (user) {
    return await this.updateUser('self', user)
  },

  /**
   * @returns {Object}
   */
  async getUsers () {
    try {
      return await Vue.Http.get('/users')
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Number|String} id
   * @returns {Object}
   */
  async getUser (id) {
    try {
      return await Vue.Http.get(`/users/${id}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Number|String} id
   */
  async removeUser (id) {
    try {
      await Vue.Http.delete(`/users/${id}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object} user
   * @returns {Object}
   */
  async createUser (user) {
    try {
      return await Vue.Http.post('/users', user)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Number|String} id
   * @param {Object} user
   * @returns {Object}
   */
  async updateUser (id, user) {
    try {
      return await Vue.Http.put(`/users/${id}`, user)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {File} file
   * @returns {Object}
   */
  async uploadImage (file) {
    const formData = new FormData()
    formData.append('file', file)

    try {
      return await Vue.Http.post('/images', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @returns {Object}
   */
  async getCatalog () {
    try {
      return await Vue.Http.get('/catalog')
    } catch (error) {
      //

      console.log(error)
    }
  }
}
