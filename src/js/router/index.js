import Vue from 'vue'
import Router from 'vue-router'
import SignInPage from '../components/pages/SignInPage'
import SignUpPage from '../components/pages/SignUpPage'
import HomePage from '../components/pages/HomePage'
import AboutPage from '../components/pages/AboutPage'
import ContactPage from '../components/pages/ContactPage'

Vue.use(Router)

export default new Router({
    mode: 'history',
    base: '/agency-parser/',

    routes: [
        { path: '/', component: HomePage },
        { path: '/login', component: SignInPage },
        { path: '/register', component: SignUpPage },
        { path: '/about', component: AboutPage },
        { path: '/contact', component: ContactPage },
        { path: '*', redirect: '/' }
    ]
})
