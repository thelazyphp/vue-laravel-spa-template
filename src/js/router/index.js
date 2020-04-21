import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import Home from '../views/Home'
import SignIn from '../views/SignIn'
import SignUp from '../views/SignUp'
import Catalog from '../views/Catalog'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: `/${process.env.MIX_APP_PATH}/`,

    routes: [
        { path: '/', component: Home },
        { path: '/sign-in', component: SignIn },
        { path: '/sign-up', component: SignUp },
        { path: '/catalog/:category', component: Catalog },
        { path: '*', redirect: '/' }
    ]
})

router.beforeEach((to, from, next) => {
    if (store.getters['auth/isAuth'] && !store.state.users.current) {
        store.dispatch('users/fetchCurrent')
            .catch(error => console.log(error)).finally(() => next())
    } else {
        next()
    }
})

export default router
