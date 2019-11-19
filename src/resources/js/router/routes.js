// import pages
import LoginPage from '../pages/LoginPage'
import RegisterPage from '../pages/RegisterPage'
import HomePage from '../pages/HomePage'
import ProfilePage from '../pages/ProfilePage'
import ProfileEditPage from '../pages/ProfileEditPage'
import AccountDeletePage from '../pages/AccountDeletePage'

export default [
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
        name: 'home',
        path: '/',
        component: HomePage,
        meta: { auth: true }
    },
    {
        name: 'profile',
        path: '/profile',
        component: ProfilePage,
        meta: { auth: true }
    },
    {
        name: 'profile.edit',
        path: '/profile/edit',
        component: ProfileEditPage,
        meta: { auth: true }
    },
    {
        name: 'account.delete',
        path: '/account/delete',
        component: AccountDeletePage,
        meta: { auth: true }
    }
]
