import Vue from 'vue'
import VueRouter from 'vue-router'
import routes from './routes'
import store from '../store'

Vue.use(VueRouter)

const router = new VueRouter({
  mode: 'history',
  base: '/facebook/',
  routes
})

router.beforeEach((to, from, next) => {
  if (to.meta.auth && !store.getters['auth/check']) {
    next('/sign-in')
  } else {
    next()
  }
})

router.beforeEach((to, from, next) => {
  if (store.getters['auth/check'] && !store.state.users.current) {
    store.dispatch('users/fetchCurrent')
      .then(() => next())
  } else {
    next()
  }
})

router.beforeEach((to, from, next) => {
  if (to.meta.title) {
    document.title = `${to.meta.title} | Facebook`
    next()
  } else {
    next()
  }
})

export default router
