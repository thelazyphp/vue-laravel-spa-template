import Vue from 'vue'
import store from './store'
import Http from './plugins/Http'

export default {
  init () {
    Vue.use(Http, {
      baseURL: 'http://localhost/facebook-feed/api/v1'
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
    try {
      return await Vue.Http.get('/users/current')
    } catch (error) {
      //

      console.log(error)
    }
  },

  async removeCurrentUser () {
    try {
      await Vue.Http.delete('/users/current')
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object} user
   * @returns {Object}
   */
  async updateCurrentUser (user) {
    try {
      return await Vue.Http.put('/users/current', user)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @returns {Object}
   */
  async getTags () {
    try {
      return await Vue.Http.get('/tags')
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {number} id
   */
  async removeTag (id) {
    try {
      await Vue.Http.delete(`/tags/${id}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {string} text
   * @returns {Object}
   */
  async createTag (text) {
    try {
      return await Vue.Http.post('/tags', { text })
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {string} url
   * @returns {Object}
   */
  async searchGroup (url) {
    try {
      return await Vue.Http.get(`/search-group?url=${url}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object=} [params={}]
   * @returns {Object}
   */
  async getGroups (params = {}) {
    try {
      return await Vue.Http.get('/groups', { params })
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @returns {number}
   */
  async getGroupsCount () {
    try {
      return (await Vue.Http.get('/groups/count')).value
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object} group
   * @returns {Object}
   */
  async createGroup (group) {
    try {
      return await Vue.Http.post('/groups', group)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {number} id
   */
  async removeGroup (id) {
    try {
      await Vue.Http.delete(`/groups/${id}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {string} id
   * @param {Object} group
   *
   * @returns {Object}
   */
  async updateGroup (id, group) {
    try {
      return await Vue.Http.put(`/groups/${id}`, group)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object=} [params={}]
   * @returns {Object}
   */
  async getPosts (params = {}) {
    try {
      return await Vue.Http.get('/posts', { params })
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {number} id
   */
  async favoritePost (id) {
    try {
      await Vue.Http.posts(`/favorites/${id}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {number} id
   */
  async unfavoritePost (id) {
    try {
      await Vue.Http.delete(`/favorites/${id}`)
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @param {Object=} [params={}]
   * @returns {Object}
   */
  async getFavorites (params = {}) {
    try {
      return await Vue.Http.get('/favorites', { params })
    } catch (error) {
      //

      console.log(error)
    }
  },

  /**
   * @returns {number}
   */
  async getFavoritesCount () {
    try {
      return (await Vue.Http.get('/favorites/count')).value
    } catch (error) {
      //

      console.log(error)
    }
  }
}
