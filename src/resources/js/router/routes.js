// import pages
import Home from '../pages/Home'
import Login from '../pages/Login'
import Register from '../pages/Register'
import Profile from '../pages/Profile'
import EditProfile from '../pages/EditProfile'
import DeleteAccount from '../pages/DeleteAccount'

export default [
    {
        name: 'home',
        path: '/',
        component: Home,
        meta: { requiresAuth: true }
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'profile',
        path: '/profile',
        component: Profile,
        meta: { requiresAuth: true }
    },
    {
        name: 'profile.edit',
        path: '/profile/edit',
        component: EditProfile,
        meta: { requiresAuth: true }
    },
    {
        name: 'account.delete',
        path: '/account/delete',
        component: DeleteAccount,
        meta: { requiresAuth: true }
    }
]
