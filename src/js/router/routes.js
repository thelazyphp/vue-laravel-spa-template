import TheHomePage from '../components/TheHomePage'
import TheProfilePage from '../components/TheProfilePage'
import TheLoginPage from '../components/TheLoginPage'
import TheRegisterPage from '../components/TheRegisterPage'

export default [
    {
        name: 'home',
        path: '/',
        component: TheHomePage,
        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'profile',
        path: '/profile',
        component: TheProfilePage,
        meta: {
            requiresAuth: true
        }
    },
    {
        name: 'login',
        path: '/login',
        component: TheLoginPage,
        meta: {
            requiresGuest: true
        }
    },
    {
        name: 'register',
        path: '/register',
        component: TheRegisterPage,
        meta: {
            requiresGuest: true
        }
    }
]
