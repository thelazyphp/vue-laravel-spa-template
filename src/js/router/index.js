import Vue from 'vue'
import Router from 'vue-router'

import routes from './routes'
import { ROUTER_BASE } from '../.env'

// import middleware
import checkAuth from './middleware/checkAuth'
import checkGuest from './middleware/checkGuest'
import getCurrentUser from './middleware/getCurrentUser'

Vue.use(Router)

const router = new Router({
    base: ROUTER_BASE,
    mode: 'history',
    routes
})

router.beforeEach(checkAuth)
router.beforeEach(checkGuest)
router.beforeEach(getCurrentUser)

export default router
