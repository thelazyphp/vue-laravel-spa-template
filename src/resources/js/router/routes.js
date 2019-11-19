// import pages
import HomePage from '../pages/HomePage'
import LoginPage from '../pages/LoginPage'
import RegisterPage from '../pages/RegisterPage'
import ProfilePage from '../pages/ProfilePage'
import ProfileEditPage from '../pages/ProfileEditPage'
import AccountDeletePage from '../pages/AccountDeletePage'

export default [
    {
        name: 'home',
        path: '/',
        component: HomePage,
        meta: { requiresAuth: true }
    },
    {
        name: 'login',
        path: '/login',
        component: LoginPage
    },
    {
        name: 'register',
        path: '/register',
        component: RegisterPage
    },
    {
        name: 'profile',
        path: '/profile',
        component: ProfilePage,
        meta: { requiresAuth: true }
    },
    {
        name: 'profile.edit',
        path: '/profile/edit',
        component: ProfileEditPage,
        meta: { requiresAuth: true }
    },
    {
        name: 'account.delete',
        path: '/account/delete',
        component: AccountDeletePage,
        meta: { requiresAuth: true }
    }
]
