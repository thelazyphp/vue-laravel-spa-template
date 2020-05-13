import Vue from 'vue'
import VueRouter from 'vue-router'
import store from '../store'
import Home from '../views/Home'
import SignIn from '../views/SignIn'
import SignUp from '../views/SignUp'
import Welcome from '../views/Welcome'
import Catalog from '../views/Catalog'
import Clients from '../views/Clients'
import ClientCreate from '../views/ClientCreate'
import TheSidebar from '../components/TheSidebar'

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    base: `/${process.env.MIX_APP_PATH}/`,

    routes: [
        {
            path: '/',
            name: 'home',

            components: {
                default: Home,
                sidebar: TheSidebar,
            },

            meta: { layout: 'sidebar', requiresAuth: true },
        },
        {
            path: '/sign-in',
            name: 'signIn',
            component: SignIn,
            meta: { layout: 'default', requiresGuest: true },
        },
        {
            path: '/sign-up',
            name: 'signUp',
            component: SignUp,
            meta: { layout: 'default', requiresGuest: true },
        },
        {
            path: '/welcome',
            name: 'welcome',
            component: Welcome,
            meta: { layout: 'default', requiresGuest: true },
        },
        {
            path: '/catalog',
            name: 'catalog',

            components: {
                default: Catalog,
                sidebar: TheSidebar,
            },

            meta: { layout: 'sidebar', requiresAuth: true },
        },
        {
            path: '/clients',
            name: 'clients',

            components: {
                default: Clients,
                sidebar: TheSidebar,
            },

            meta: { layout: 'sidebar', requiresAuth: true },
        },
        {
            path: '/clients/create',
            name: 'createClient',

            components: {
                default: ClientCreate,
                sidebar: TheSidebar,
            },

            meta: { layout: 'sidebar', requiresAuth: true },
        },
        {
            path: '*',

            redirect: {
                name: 'welcome',
            },
        },
    ]
})

router.beforeEach((to, from, next) => {
    if (
        to.meta.requiresGuest
        && store.getters['auth/isAuth']
    ) {
        next({ name: 'home' })
    } else if (
        to.meta.requiresAuth
        && !store.getters['auth/isAuth']
    ) {
        next({ name: 'welcome' })
    } else {
        next()
    }
})

router.beforeEach((to, from, next) => {
    if (
        store.getters['auth/isAuth']
        && !store.state.users.current
    ) {
        store.dispatch('users/fetchCurrent')
            .catch(error => console.log(error)).finally(() => next())
    } else {
        next()
    }
})

export default router
